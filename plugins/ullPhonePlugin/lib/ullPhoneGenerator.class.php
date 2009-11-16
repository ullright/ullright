<?php

class ullPhoneGenerator extends ullTableToolGenerator
{
  public function __construct()
  {
    $columns = sfConfig::get('app_ull_user_phone_book_columns', array(
//      'display_name',
      'last_name',
      'first_name',
      'phone_extension',
      'fax_extension',
      'mobile_number',
      'email',
      'UllCompany->name',
      'UllDepartment->name',
      'UllLocation->name',
    ));
    
    parent::__construct('UllUser', 'r', 'list', $columns);
  }

  protected function buildColumnsConfig()
  {
    parent::buildColumnsConfig();
    
    $this->columnsConfig['email']->setWidgetOption('show_icon_only', true);
    $this->columnsConfig['last_name']
      ->setInjectIdentifier(true)
      ->setMetaWidgetClassName('ullMetaWidgetForeignKey')
      ->setWidgetOption('show_ull_entity_popup', true)
    ;
    $this->columnsConfig['first_name']
      ->setInjectIdentifier(true)
      ->setMetaWidgetClassName('ullMetaWidgetForeignKey')
      ->setWidgetOption('show_ull_entity_popup', true)
    ;
//    $this->columnsConfig['display_name']
//      ->setInjectIdentifier(true)
//      ->setMetaWidgetClassName('ullMetaWidgetForeignKey')
//      ->setWidgetOption('show_ull_entity_popup', true)
//    ;    
  }
}
