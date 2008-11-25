<?php

class ullFlowMailNotifyNext extends ullFlowMail
{

  /**
   * custom prepare method
   *
   */
  public function prepare() 
  {
    $this->setFrom($this->user->email, $this->user->display_name);
    
    $nextEntity = $this->doc->UllEntity;
    
    if ($nextEntity->type == 'group') 
    {
      // check for a group email address
      if ($email = $nextEntity->email) 
      {
        $this->addAddress($email, $nextEntity->__toString());
      } 
      else 
      {
        // if no group email -> get list of users and send to their email addresses
        $userGroups = Doctrine::getTable('UllEntityGroup')->findByUllGroupId($nextEntity->id);
        
        
        
        foreach ($userGroups as $userGroup) 
        {
          $user = Doctrine::getTable('UllUser')->findOneById($userGroup->ull_entity_id);
          $name = $user->__toString();
          $email= $user->email;
          
//          var_dump($name);var_dump($email);die;
          
          if ($email) 
          {
            $this->addAddress($email, $name);
          }
        }
      }
      
//      $greeting = __('Group', null, 'common') . ' ' . $next_group->__toString();

    
    } 
    else 
    {
      // user
      $name = $nextEntity->__toString();
      $email = $nextEntity->email;
      
      if ($email) 
      {
        $this->addAddress($email, $name);
      }
      
//      $greeting = $user_name;
    }

//    var_dump($this->doc->UllFlowApp->toArray)=;die;
    
    $subject =
        $this->doc->UllFlowApp->doc_label . 
        ': "' .
        $this->doc->title .
        '"'
    ;
    
    $request =
        __('Please take care of') .
        ' ' .
        $this->doc->UllFlowApp->slug . 
        ' "' .
        $this->doc->title .
        '"'
    ;
    
    $this->setSubject($subject);
    
    $comment = ($this->doc->memory_comment) ? __('Comment') . ': ' . 
        $this->doc->memory_comment . "\n\n" : ''; 
    
    $this->setBody(
      __('Hello') . ' ' . $nextEntity . ",\n" .
      "\n" .
      $request . ".\n" .
      "\n" .
      $comment .
      $this->getEditLink() . "\n" .
      "\n" .
      __('Kind regards') . ",\n" .
      $this->user . "\n"
    );
  }
  
}
