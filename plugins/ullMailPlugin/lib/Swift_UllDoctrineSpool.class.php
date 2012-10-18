<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Swift_DoctrineSpool is a spool that uses Doctrine.
 *
 * Example schema:
 *
 *  MailMessage:
 *   actAs: { Timestampable: ~ }
 *   columns:
 *     message: { type: clob, notnull: true }
 *
 * @package    symfony
 * @subpackage mailer
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: Swift_DoctrineSpool.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class Swift_UllDoctrineSpool extends Swift_DoctrineSpool
{
  protected
    $mailsPerMinute = 240
  ;
  
  /**
   * Constructor.
   *
   * @param string The Doctrine model to use to store the messages (MailMessage by default)
   * @param string The column name to use for message storage (message by default)
   * @param string The method to call to retrieve the query to execute (optional)
   * @param integer Throttle: Limit the amount of mails sent per minute
   */
  public function __construct(
    $model = 'UllMailQueuedMessage', 
    $column = 'message', 
    $method = 'querySpooledMessages',
    $mailsPerMinute = 240
  )
  {
    parent::__construct($model, $column, $method);
    
    $this->mailsPerMinute = $mailsPerMinute;
  }  
  
  /**
   * Stores a message in the queue.
   * 
   * Reduces memory usage
   *
   * @param Swift_Mime_Message $message The message to store
   */
  public function queueMessage(Swift_Mime_Message $message)
  {
    $object = new $this->model;

    if (!$object instanceof Doctrine_Record)
    {
      throw new InvalidArgumentException('The mailer message object must be a Doctrine_Record object.');
    }
    
    $queuedStatus = $message->getIsQueued();
    $message->setIsQueued(true);

    $object->{$this->column} = serialize($message);
    $object->save();
    
    // Reset in case the message is used multiple times
    $message->setIsQueued($queuedStatus);
    
    $object->free(true);
  }  

  /**
   * Sends messages using the given transport instance.
   *
   * @param Swift_Transport $transport         A transport instance
   * @param string[]        &$failedRecipients An array of failures by-reference
   *
   * @return int The number of sent emails
   */
  public function flushQueue(Swift_Transport $transport, &$failedRecipients = null)
  {
    $messageLimit = $this->getMessageLimit();
    $messageLimit = ($messageLimit !== null) ? $messageLimit : 0;
     
    if (!$transport->isStarted())
    {
      $transport->start();
    }

    //retrieve ids of mails to send, up to $messageLimit
    $table = Doctrine_Core::getTable($this->model);
    $ids = $table
      ->{$this->method}()
      ->select('id')
      ->limit($messageLimit)
      ->execute(array(), DOCTRINE::HYDRATE_NONE)
    ;
    
    $this->log("Beginning to process " . count($ids) . " messages\n");
    
    //important note concerning Doctrine locking
    //the locking manager has a serious bug: row-level locking is not possible,
    //every time an object is locked the whole table (= model) gets locked.
    //the upside: for our purposes this behavior is 'good enough'
    
    //create locking manager and unique id for locking
    //pid + random string should be unique enough
    $lockingManager = new Doctrine_Locking_Manager_Pessimistic(Doctrine_Manager::connection());
    $uniqueId = uniqid(getmypid(), true);
    
    //remove old mail locks (> 5 min)
    $lockingManager->releaseAgedLocks(300, $table->getComponentName());
       
    $count = 0;
    $time = time();

    foreach ($ids as $id)
    {
      //retrieve actual mail object
      $mailObject = $table->findOneById($id[0]);
      if (!$mailObject)
      {
        continue;
      }

      //request exclusive lock for this mail object
      try
      {
        if (!$lockingManager->getLock($mailObject, $uniqueId))
        {
          $this->log('Could not retrieve lock for queued message with id: ' .
            $mailObject['id'] . "\n");
            
          continue;
        }
      }
      catch (Doctrine_Locking_Exception $e)
      {
        echo $e->getMessage();
        
        continue;
      }

      //now that we have the lock, handle the message
      try
      {
        $message = unserialize($mailObject->{$this->column});
        $count += $transport->send($message, $failedRecipients);
        
        $a = $message->getTo();
        $to = reset($a);
        
        $this->log('Sending to  ' . $to . '<' . key($a) . ">\n");
        
        $mailObject->delete();
        unset($message);
      }
      catch (Exception $e)
      {
        $this->log($e->getMessage());
        //TODO: add proper error handling
      }

      //message was handled, release lock
      try
      {
        $lockingManager->releaseLock($mailObject, $uniqueId);
      }
      catch(Doctrine_Locking_Exception $e)
      {
        $this->log($e->getMessage());
      }

      //free memory used by the mail objects
      $mailObject->free(true);

      //total time limit reached?
      if ($this->getTimeLimit() && (time() - $time) >= $this->getTimeLimit())
      {
        break;
      }

      //throttle sending if necessary
      if ($this->mailsPerMinute)
      {
        usleep(self::calculateSleepTime($this->mailsPerMinute));
      }

      $this->log(UllMailQueuedMessageTable::countUnsentMessages() . " mails left\n");
    }

    return $count;
  }
  
  /**
   * Calculates sleep time in usecs for throtteling
   * 
   * @param integer $mailsPerMinute > 0
   * @return integer usecs
   */
  public static function calculateSleepTime($mailsPerMinute)
  {
    if (!is_int($mailsPerMinute))
    {
      throw new InvalidArgumentException('Input must be an integer');
    }
    
    if ($mailsPerMinute < 1)
    {
      throw new OutOfRangeException('Input must be an integer > 0');  
    }
    
    return floor(60 / $mailsPerMinute * 1000000);  
  }
  
  
  /**
   * Set number of mails to send per minute
   * 
   * @param integer $mailsPerMinute
   * @return self
   */
  public function setMailsPerMinute($mailsPerMinute)
  {
    if (!is_int($mailsPerMinute))
    {
      throw new InvalidArgumentException('Input must be an integer');
    }
    
    if ($mailsPerMinute < 1)
    {
      throw new OutOfRangeException('Input must be an integer > 0');  
    }    
    
    $this->mailsPerMinute = $mailsPerMinute;

    return $this;
  }
  
  
  /**
   * Get number of mails to send per minute
   * 
   * @return integer
   */
  public function getMailsPerMinute()
  {
    return (int) $this->mailsPerMinute;
  }

  /**
   * Workaround method to output debugging information
   * 
   * We like to get some stats when running "php symfony project:send-email"
   * but we cannot use the symfony tasks' log output here
   * 
   * Temp. workaround is using echo, but we cannot do this in the test environment
   * because it confuses the lime test harness parsing
   * 
   * @param string $msg
   */
  public function log($msg)
  {
    if (sfConfig::get('sf_environment') != 'test')
    {
      echo $msg;
    }
  }
}
