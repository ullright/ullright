<?php

class ullPhoneGenerator extends ullTableToolGenerator
{
  public function __construct()
  {
    $columns = array(
      'last_name',
      'first_name',
      'phone_extension',
      'fax_extension',
      'email',
      'UllCompany->name',
      'UllDepartment->name',
      'UllLocation->name',
    );
    
    parent::__construct('UllUser', 'r', 'list', $columns);
  }

  protected function buildColumnsConfig()
  {
    parent::buildColumnsConfig();
    
    $this->columnsConfig['email']->setWidgetOption('show_icon_only', true);
  }
}
