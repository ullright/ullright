<?php

class ullFlowTestBrowser extends ullTestBrowser
{

  public function loginAsHelpdeskUser()
  {
    $this->loginAs('helpdesk_user', 'test');

    return $this;
  }  
  
}

