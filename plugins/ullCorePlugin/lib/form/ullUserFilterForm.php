<?php

class ullUserFilterForm extends ullTableToolFilterForm
{
  public function configure()
  {
    parent::configure();
    
    $this->getWidget('search')->setAttribute('title', __('Searches for name, username and email', null, 'ullCoreMessages'));
  }
}
