<?php 

class BaseUllEntityColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->disable(array('version', 'is_virtual_group', 'display_name', 'parent_ull_user_id'));
    
    $this['display_name']->setMetaWidgetClassName('ullMetaWidgetUllEntity');
    
    $this['sex']->setMetaWidgetClassName('ullMetaWidgetSex');
    
    $this['photo']
      ->setMetaWidgetClassName('ullMetaWidgetPhoto')
      ->setInjectIdentifier(true)
      ->setShowSpacerAfter(true)
    ;
    
    $this['password']->setMetaWidgetClassName('ullMetaWidgetPassword');
    $this['email']
      ->setMetaWidgetClassName('ullMetaWidgetEmail')      
    ;
    
    $this['type']
      ->setMetaWidgetClassName('ullMetaWidgetUllEntityType')
      ->setShowSpacerAfter(true)
    ;
      
    $this['ull_company_id']
      ->setWidgetOption('add_empty', true)
      ->setOption('show_search_box', true)
    ;
    $this['ull_location_id']
      ->setWidgetOption('add_empty', true)
      ->setOption('show_search_box', true)
    ;
    $this['ull_department_id']
      ->setWidgetOption('add_empty', true)
      ->setOption('show_search_box', true)
    ;
    $this['ull_job_title_id']
      ->setLabel(__('Job title', null, 'ullCoreMessages'))
      ->setWidgetOption('add_empty', true)
      ->setOption('show_search_box', true)
    ;
    $this['superior_ull_user_id']
      ->setWidgetOption('add_empty', true)
      ->setOption('show_search_box', true)
      ->setShowSpacerAfter(true)
    ;
    
    $this['phone_extension']->setLabel(__('Phone extension', null, 'ullCoreMessages'));
    $this['is_show_extension_in_phonebook']->setLabel(__('Show phone ext. in phone book', null, 'ullCoreMessages'));
    $this['fax_extension']->setLabel(__('Fax extension', null, 'ullCoreMessages'));
    $this['mobile_number']->setLabel(__('Mobile number', null, 'ullCoreMessages'));
    $this['is_show_mobile_number_in_phonebook']
      ->setLabel(__('Show mobile number in phone book', null, 'ullCoreMessages'))
      ->setShowSpacerAfter(true)
    ;
    
    $this['ull_employment_type_id']
      ->setLabel(__('Employment type', null, 'ullCoreMessages'))
      ->setWidgetOption('add_empty', true)
    ;
    $this['entry_date']
      ->setLabel(__('Entry date', null, 'ullCoreMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetDate')
    ;
    $this['deactivation_date']
      ->setLabel(__('Deactivation date', null, 'ullCoreMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetDate')
    ;
    $this['separation_date']
      ->setLabel(__('Separation date', null, 'ullCoreMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetDate')
    ;
    $this['ull_user_status_id']->setShowSpacerAfter(true);
    
    
    $this->order(array(
      'id',
      'first_name',
      'last_name',
      'sex',
      'photo',
      'username',
      'password',
      'email',
      'type',
      'ull_company_id',
      'ull_location_id',
      'ull_department_id',
      'ull_job_title_id',
      'superior_ull_user_id',
      'phone_extension',
      'is_show_extension_in_phonebook',
      'fax_extension',
      'mobile_number',
      'is_show_mobile_number_in_phonebook',
      'ull_employment_type_id',
      'entry_date',
      'deactivation_date',
      'separation_date',
      'ull_user_status_id',
      'comment'
    ));
    
    if ($this->isListAction())
    {
      $this->disableAllExcept(array('id', 'first_name', 'last_name', 'username', 'email'));
    }

    if ($this->isShowAction())
    {
      $this->disable(array(
        'sex', 
        'password',
        'ull_location_id',
        'is_show_extension_in_phonebook',
        'is_show_mobile_number_in_phonebook',      
        'entry_date',
        'deactivation_date',
        'separation_date',
        'comment'
      ));
      
      $this['photo']->setAutoRender(false);
      $this['last_name']->setAutoRender(false);
      $this['first_name']->setAutoRender(false);
      
      $this['phone_extension']
        ->setMetaWidgetClassName('ullMetaWidgetPhoneNumber')
        ->setOption('show_base_number', true)
        ->setInjectIdentifier(true)
      ;
      $this['fax_extension']
        ->setMetaWidgetClassName('ullMetaWidgetPhoneNumber')
        ->setOption('show_base_number', true)
        ->setInjectIdentifier(true)
      ;
    }    
  }
}