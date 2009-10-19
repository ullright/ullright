<?php

class ullPhoneGenerator extends ullTableToolGenerator
{
  public function __construct()
  {
    parent::__construct('UllUser', 'r', 'list');
  }

  protected function buildColumnsConfig()
  {
    $this->columnsConfig = ullColumnConfigCollection::buildFor($this->modelName, $this->defaultAccess, $this->requestAction);

    $this->columnsConfig['phone_extension']->enable();
    $this->columnsConfig['fax_extension']->enable();
    $this->columnsConfig['ull_company_id']->enable();
    $this->columnsConfig['ull_location_id']->enable();
    $this->columnsConfig['ull_department_id']->enable();
    
    $this->columnsConfig['username']->disable();
    
    $this->columnsConfig['email']->setWidgetOption('show_icon_only', true);

    $this->columnsConfig->order(array(
      'last_name',
      'first_name',
      'phone_extension',
      'fax_extension',
      'email',
      'ull_company_id',
      'ull_department_id',
      'ull_location_id',
    ));
  }
}
