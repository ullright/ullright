<?php

abstract class Mail2TicketBaseTask extends sfBaseTask
{
  protected
    $imapConnectionString = '',
    $imapUsername = '',
    $imapPassword = '',
    
    $ullFlowAppSlug = '',
    $ullEntityDisplayName = '',
    $ullFlowStepSlug = '',
    $ullFlowActionSlug = '', 
    
    $deleteProcessedMessage = false,
    
    $messageData = array()
  ;
  
  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection($this->name, 'Initializing');
    
    $configuration = ProjectConfiguration::getApplicationConfiguration(
	  $arguments['application'], $arguments['env'], true);
    
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('ull'));
    
    $databaseManager = new sfDatabaseManager($configuration);

    
    
    $this->logSection($this->name, 'Connecting to mailbox ' . $this->imapConnectionString . ' as ' . $this->imapUsername);
    
    if ($mbox = imap_open($this->imapConnectionString, $this->imapUsername, $this->imapPassword)) 
    {
//      $list = imap_headers($mbox);
//      var_dump($list);
      
      // get number of messages
      $check = imap_check($mbox);
      $numOfMsgs = $check->Nmsgs;   

      $this->logSection($this->name, $numOfMsgs . ' messages found');
      
      $overview = imap_fetch_overview($mbox, '1:' . $numOfMsgs);
      
      foreach ($overview as $msg) 
      {
        if (!$msg->seen) 
        {
          // reset messageData
          $this->messageData = array();
          
          $msgNo = $msg->msgno;
          
          $this->parseHeader($mbox, $msgNo);
          
          $this->logSection($this->name, 'Processing unseen message: ' . $this->messageData['subject']);
          
          $this->parseBodyTxt($mbox, $msgNo);
          
          $this->parseAttachements($mbox, $msgNo);
          
          $this->createAndSaveObject();
          
          $this->cleanUp($mbox, $msgNo);
          
        } // end of if not seen
  
      } // end of of each message
      
      imap_expunge($mbox);
      imap_close($mbox);
    } 
    else 
    {
      $this->logBlock('Error: Could not connect to mail server!', 'ERROR');
    }
    
    $this->logSection($this->name, 'Done!');
  }

  
  /**
   * Parse the imap header elements and return the utf-8 encoded subject
   * 
   * @param unknown_type $mbox
   * @param integer $msgNo
   * @return none
   */
  protected function parseHeader($mbox, $msgNo)
  {
    $header = imap_headerinfo($mbox, $msgNo);
    $this->messageData['header'] = $header;
    
    // Decode MIME message header extensions that are non ASCII text
    $subjectElements = imap_mime_header_decode($header->subject);
    
    $subject = '';
    
    foreach($subjectElements as $element) 
    {
      $subject .= $element->text;
    }
    
    $subject = utf8_encode($subject);

    $this->messageData['subject'] = $subject;
  }
  
  
  /**
   * Parse the imap txt body
   * 
   * @param unknown_type $mbox
   * @param integer $msgNo
   * @return unknown_type
   */
  protected function parseBodyTxt($mbox, $msgNo)
  {
    $this->messageData['body_txt'] =  utf8_encode(get_part($mbox, $msgNo, "TEXT/PLAIN"));    
  }
  
  
  /**
   * Parse imap attachements
   * 
   * Stores files in the /tmp dir and populates an array with the list of files
   * 
   * @param unknown_type $mbox
   * @param integer $msgNo
   * @return unknown_type
   */
  protected function parseAttachements($mbox, $msgNo)
  {
    $attachments = array();
     
    $struct = imap_fetchstructure($mbox, $msgNo);
    
    if (@$struct->parts) 
    {
      $parts = $struct->parts;
      
      foreach ($parts as $partNum => $part) 
      {
        $params = $part->parameters;
        
        foreach ($params as $param) 
        {
          if ($param->attribute == 'name') 
          {
            $filename = imap_utf8($param->value);
            
            $content = imap_fetchbody($mbox , $msgNo, $partNum+1);
            
            // handle encoding
            if ($part->encoding == 3) 
            {
              $content = imap_base64($content);
            } 
            elseif($part->encoding == 4) 
            {
              $content = imap_qprint($content);
            }
            
            $attachments[$filename] = $content;
          }
        }
      } // end of foreach $part
    } // end of if parts    
    
    $this->messageData['attachments'] =  $attachments;
  }
  
  
  /**
   * Create the UllFlowDoc Object
   * 
   * @return none
   */
  protected function CreateAndSaveObject()
  {
    $doc = new UllFlowDoc();
    
    $doc->UllFlowApp = UllFlowAppTable::findBySlug($this->ullFlowAppSlug); 
    $doc->UllEntity = Doctrine::getTable('UllEntity')->findOneByDisplayName($this->ullEntityDisplayName);
    $doc->UllFlowStep = Doctrine::getTable('UllFlowStep')->findOneBySlug($this->ullFlowStepSlug);
    $doc->UllFlowAction = Doctrine::getTable('UllFlowAction')->findOneBySlug($this->ullFlowActionSlug);
    
    $columnSubjectSlug = UllFlowColumnConfigTable::findSubjectColumnSlug($doc->UllFlowApp->id);
    $doc->$columnSubjectSlug = $this->messageData['subject'];
    $columnPrioritySlug = UllFlowColumnConfigTable::findPriorityColumnSlug($doc->UllFlowApp->id);
    $doc->$columnPrioritySlug = 3; // normal priority

    // Necessary to save here to generate an id e.g. for the upload path
    $doc->save();
    
    $this->updateObject($doc);
  }
  
  
  /**
   * Use this method to perform custom logic on the object
   * 
   * @param UllFlowDoc $doc
   * @return none
   */
  abstract protected function updateObject(UllFlowDoc $doc);

  
  /**
   * Clean up
   * 
   * @param unknown_type $mbox
   * @param integer $msgNo
   * @return none
   */
  protected function cleanUp($mbox, $msgNo)
  {
    if ($this->deleteProcessedMessage)
    {
      imap_delete($mbox, $msgNo);
    }
  }
  
  
  /**
   * Save the attachements in the filesystem and build a ullFlowUpload data array
   * 
   * @param ullFlowDoc $doc
   * @return array
   */
  protected function saveAttachements(ullFlowDoc $doc)
  {
    // attachments
    if ($attachments = $this->messageData['attachments']) 
    {
      $path = sfConfig::get('sf_upload_dir') 
        . '/ullFlow/'
        . $doc->UllFlowApp->slug . '/'
        . $doc->id
      ;
      
      mkdir($path, 0777, true);
      
      $rows = array();
      
      foreach($attachments as $filename => $content) 
      {
        $fullPath = $path 
          . '/'
          . date('Y-m-d-H-i-s_')
          . $filename; 
          
        $fp=fopen($fullPath, 'w+');
        $check=fwrite($fp, $content);
        fclose($fp);
        
        $webPath = str_replace(sfConfig::get('sf_web_dir'), '', $fullPath);
        
        $rows[] = $filename . ';' . $webPath . ';' . '' . ';' . 1 . ';' . date('Y-m-d H:i:s');
      }
      
      return implode("\n", $rows);
    }    
  }
  
}
