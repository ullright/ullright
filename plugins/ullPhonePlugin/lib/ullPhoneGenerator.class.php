<?php

class ullPhoneGenerator extends ullTableToolGenerator
{
  public function __construct()
  {
    $columns = sfConfig::get('app_ull_user_phone_book_columns', array(
      'last_name_first',
      'phone_extension',
      'mobile_number',
      'email',
      'UllLocation->name',
      'UllDepartment->name',
      'UllJobTitle->name',
    ));
    
    parent::__construct('UllUser', 'r', 'list', $columns);
  }

  
  protected function customizeTableConfig()
  {
    // Override default orderby
    $this->tableConfig->setOrderBy('last_name_first');
  }   
  
  
  protected function customizeColumnsConfig()
  {
    $this->columnsConfig['email']
      ->setWidgetOption('show_icon_only', true)
      ->setLabel(' ')
    ;
    $this->columnsConfig['phone_extension']
      ->setLabel(__('Ext.', null, 'ullPhoneMessages'))
    ;        
    $this->columnsConfig['mobile_number']
      ->setLabel(__('Mobile', null, 'ullPhoneMessages'))
      ->setWidgetOption('nowrap', true)
    ;

    // Create columnConfig for artificial "last_name_first" column
    $this->getColumnsConfig()
      ->create('last_name_first')
      ->setLabel(__('Name', null, 'common'))
      ->setMetaWidgetClassName('ullMetaWidgetForeignKey')
      ->setWidgetOption('show_ull_entity_popup', true)
      ->setInjectIdentifier(true)
      ->setWidgetOption('nowrap', true)
    ;
  }

  
  protected function customizeRelationColumns()
  {
    $this->columnsConfig['UllLocation->name']
      ->setWidgetOption('nowrap', true)
    ;
  }  
}
