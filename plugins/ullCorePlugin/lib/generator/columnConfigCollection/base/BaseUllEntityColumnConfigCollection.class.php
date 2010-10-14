<?php 

class BaseUllEntityColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->disable(array('version', 'is_virtual_group', 'display_name', 'last_name_first', 'parent_ull_user_id'));
    
    $this['display_name']->setMetaWidgetClassName('ullMetaWidgetUllEntity');

    $this['sex']->setMetaWidgetClassName('ullMetaWidgetSex');
    
    $this['photo']
      ->setMetaWidgetClassName('ullMetaWidgetPhoto')
      ->setInjectIdentifier(true)
    ;
    
    $this['is_photo_public']
      ->setLabel(__('Show photo', null, 'ullCoreMessages'))
      ->setHelp(__('If unchecked, the photo will be visible in administrative areas only.', null, 'ullCoreMessages'))
    ;
    
    $this['username']
      ->setIsRequired(true);
    
    $this['password']
      ->setMetaWidgetClassName('ullMetaWidgetPassword')
      ->setWidgetOption('render_pseudo_password', true)
      ->setWidgetAttribute('autocomplete', 'off')
    ;
    
    $this['email']
      ->setMetaWidgetClassName('ullMetaWidgetEmail')      
    ;
    
    $this['type']
      ->setMetaWidgetClassName('ullMetaWidgetUllEntityType')
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
    
    $this['cost_center']->setLabel(__('Cost center', null, 'ullCoreMessages'));
    
    $this['ull_job_title_id']
      ->setLabel(__('Job title', null, 'ullCoreMessages'))
      ->setWidgetOption('add_empty', true)
      ->setOption('show_search_box', true)
    ;
    $this['superior_ull_user_id']
      ->setWidgetOption('add_empty', true)
      ->setOption('show_search_box', true)
      ->setOption('entity_classes', array('UllUser', 'UllCloneUser'))
    ;
    
    $this['is_assistant']->setLabel(__('Is assistant', null, 'ullCoreMessages'));
    
    $this['is_superior']->setLabel(__('Is superior', null, 'ullCoreMessages'));
    
    $this['is_show_in_phonebook']
      ->setLabel(__('Show in phone book', null, 'ullCoreMessages'))
      ->setHelp(__('If unchecked, none of the user\'s contact data will ' .
        'be listed in the phone book.', null, 'ullCoreMessages'))
    ;
    
    $this['is_show_in_orgchart']
      ->setLabel(__('Show in orgchart', null, 'ullCoreMessages'))
    ;    
    
    $this['phone_extension']->setLabel(__('Phone extension', null, 'ullCoreMessages'));
    $this['alternative_phone_extension']->setLabel(__('Alternative phone extension', null, 'ullCoreMessages'));
    $this['is_show_extension_in_phonebook']
      ->setLabel(__('Show phone ext. in phone book', null, 'ullCoreMessages'))
      ->setHelp(__('If unchecked, the alternative phone extension ' .
        'replaces the regular one.', null, 'ullCoreMessages'))
    ;
    $this['fax_extension']->setLabel(__('Fax extension', null, 'ullCoreMessages'));
    $this['mobile_number']
      ->setLabel(__('Mobile number', null, 'ullCoreMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetPhoneNumber')
      ->setHelp(__('Format: +43 664 1234567', null, 'ullCoreMessages'))
      ->setOption('default_country_code', '+43')
    ;
    $this['is_show_mobile_number_in_phonebook']
      ->setLabel(__('Show mobile number in phone book', null, 'ullCoreMessages'))
    ;
    
    $this['personnel_number']->setLabel(__('Personnel number', null, 'ullCoreMessages'));
    
    $this['ull_employment_type_id']
      ->setLabel(__('Employment type', null, 'ullCoreMessages'))
      ->setWidgetOption('add_empty', true)
    ;
    $this['entry_date']
      ->setLabel(__('Entry date', null, 'ullCoreMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetDate')
      ->setHelp(__('Users are automatically activated on the entry day', null, 'ullCoreMessages'))
    ;
    $this['deactivation_date']
      ->setLabel(__('Deactivation date', null, 'ullCoreMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetDate')
      ->setHelp(__('Optional date if a user should be deactivated before the separation date. Example: exemption', null, 'ullCoreMessages'))
    ;
    $this['separation_date']
      ->setLabel(__('Separation date', null, 'ullCoreMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetDate')
      ->setHelp(__('Users are automatically deactivated after the separation day ends', null, 'ullCoreMessages'))
    ;
    $this['ull_user_status_id']
      ->setMetaWidgetClassName('ullMetaWidgetUllUserStatus')
    ;
    
    $this->order(array(
      'id',
      'personal' => array(
        'first_name',
        'last_name',
        'sex',
        'photo',
        'is_photo_public',
      ),
      'it' => array(
        'username',
        'password',
        'email',
        'type',
      ),
      'organizational' => array(
        'ull_company_id',
        'ull_location_id',
        'ull_department_id',
        'cost_center',
        'ull_job_title_id',
        'superior_ull_user_id',
        'is_superior',
        'is_assistant',
        'is_show_in_orgchart',
      ),
      'phone_book' => array(
        'is_show_in_phonebook',
        'phone_extension',
        'is_show_extension_in_phonebook',
        'alternative_phone_extension',
        'fax_extension',
        'mobile_number',
        'is_show_mobile_number_in_phonebook',
      ),
      'human_resources' => array(
        'personnel_number',
        'ull_employment_type_id',
        'entry_date',
        'deactivation_date',
        'separation_date',
        'ull_user_status_id',
      ),
      'comment'
    ));
    
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
        'comment',
        'is_photo_public',
        'alternative_phone_extension',
        'is_show_in_phonebook',
        'personnel_number',
        'is_show_in_orgchart',
        'is_superior',
        'is_assistant',
        
      ));
      
      $this['photo']->setAutoRender(false);
      $this['last_name']->setAutoRender(false);
      $this['first_name']->setAutoRender(false);
      
      $this['phone_extension']
        ->setMetaWidgetClassName('ullMetaWidgetPhoneExtension')
        ->setOption('show_base_number', true)
        ->setInjectIdentifier(true)
      ;
      $this['fax_extension']
        ->setMetaWidgetClassName('ullMetaWidgetPhoneExtension')
        ->setOption('show_base_number', true)
        ->setInjectIdentifier(true)
      ;
    }    
  }
}