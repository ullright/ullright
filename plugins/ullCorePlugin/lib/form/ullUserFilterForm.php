<?php

class ullUserFilterForm extends ullFilterForm
{
  public function configure()
  {
    parent::configure();
    
    $this->getWidget('search')->setAttribute('title', __('Searches for name, username, email, location and department', null, 'ullCoreMessages'));
  }
}
