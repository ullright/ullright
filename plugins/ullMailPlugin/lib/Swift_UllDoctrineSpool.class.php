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
    $mailsPerMinute = 120
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
    $mailsPerMinute = 120
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
    
    $message->setIsQueued(true);

    $object->{$this->column} = serialize($message);
    $object->save();
    
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
    $table = Doctrine_Core::getTable($this->model);
    
    $messageLimit = $this->getMessageLimit();
    $messageLimit = ($messageLimit !== null) ? $messageLimit : 0;
    
    $ids = $table->{$this->method}()->select('id')->limit($messageLimit)->execute(array(), DOCTRINE::HYDRATE_NONE);
    
    if (!$transport->isStarted())
    {
      $transport->start();
    }

    $count = 0;
    $time = time();
    
    foreach ($ids as $id)
    {
      $object = $table->findOneById($id[0]); 
      $message = unserialize($object->{$this->column});
      
      try
      {
        $count += $transport->send($message, $failedRecipients);
        
        $object->is_sent = true;
        $object->save();
      }
      catch (Exception $e)
      {
        var_dump($e->getMessage());
      }
      
      $object->free(true);
      
      if ($this->getTimeLimit() && (time() - $time) >= $this->getTimeLimit())
      {
        break;
      }
      
      // Throtteling
      if ($this->mailsPerMinute)
      {
        usleep(self::calculateSleepTime($this->mailsPerMinute));
      }
      
      //var_dump(UllMailQueuedMessageTable::countUnsentMessages() . ' mails left');
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
}
