<?php

class ullFlowMailActionReject extends ullFlowMail
{
   
  public function send() {
    
    $mail = new ullsfMail();
    $mail->initialize();
    $mail->setFrom($this->user_email, $this->user_name);
    
    $next_user = UllUserPeer::retrieveByPK($this->doc->getAssignedToUllUserId());
    $next_group = UllGroupPeer::retrieveByPK($this->doc->getAssignedToUllGroupId());
    
    // group
    if ($next_group) {
      
      // check for a group email address
      if ($group_email = $next_group->getEmail()) {
        $mail->addAddress($group_email, $next_group->getCaption());
         
      // if no group email -> get list of users and send to their email addresses
      } else {
      
        $c = new Criteria();
        $c->add(UllUserGroupPeer::ULL_GROUP_ID, $next_group->getId());
        $usergroups = UllUserGroupPeer::doSelect($c);
        
        foreach ($usergroups as $usergroup) {
          $user = UllUserPeer::retrieveByPK($usergroup->getUllUserId());
          $user_name   = $user->__toString();
          $user_email  = $user->getEmail();
          
          if ($user_email) {
            $mail->addAddress($user_email, $user_name);
          }
        }
      }
      
      $greeting = __('Group', null, 'common') . ' ' . $next_group->__toString();

    // user
    } else {
      $user_name   = $next_user->__toString();
      $user_email  = $next_user->getEmail();
      
      if ($user_email) {
        $mail->addAddress($user_email, $user_name);
      }
      
      $greeting = $user_name;
    }
    
      $subject = 
        $this->app_doc_caption 
        . ' "'
        . $this->doc_title
        . '" '
        . __('has been %1%', array('%1%' => $this->ull_flow_action_caption_lower))
      ;
      
      $mail->setSubject($subject);
      
      $comment = ($this->comment) ? __('Comment') . ': ' . $this->comment . "\n\n" : '';
      
      $mail->setBody(
        __('Hello') . ' ' . $this->creator_name . ",\n"
        . "\n"
        . $subject . ".\n"
        . "\n"
        . $comment
        . __('Kind regards') . ",\n"
        . $this->user_name . "\n"
        . "\n"
        . $this->buildEditLink()
      );
    
 
    // send the email
    $mail->send();
    
  }
  
  
}

?>