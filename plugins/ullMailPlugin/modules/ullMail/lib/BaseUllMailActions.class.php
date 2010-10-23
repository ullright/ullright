<?php

/**
 * ullMail actions.
 *
 * @package    ullright
 * @subpackage ullMail
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllMailActions extends BaseUllGeneratorActions
{
  
  public function executeSendNewsletter(sfWebRequest $request)
  {
    $message = Swift_Message::newInstance()
      ->setFrom('null@ull.at')
      ->setTo(array('k@ull.at', 'klemens@ull.at', 'null@ull.at', 'office@ull.at'))
      ->setSubject('Subject')
      ->setBody('Body')
    ;
    
//    var_dump(get_class($this->getMailer()));die;
     
    $this->ok = $this->getMailer()->batchSendQueue($message);    
    
  }  

}