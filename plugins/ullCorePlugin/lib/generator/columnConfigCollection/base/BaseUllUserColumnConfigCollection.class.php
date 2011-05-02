<?php 

class BaseUllUserColumnConfigCollection extends UllEntityColumnConfigCollection
{
  
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    $this->disable(array('type', 'is_virtual_group', 'display_name', 'last_name_first', 'parent_ull_user_id', 'selected_culture', 'num_email_bounces'));
    
    $this['sex']->setMetaWidgetClassName('ullMetaWidgetSex');
    
    $this['birth_date']
      ->setLabel(__('Day of birth', null, 'ullCoreMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetDate')
    ;
    
    $this['photo']
      ->setMetaWidgetClassName('ullMetaWidgetPhoto')
      ->setInjectIdentifier(true)
    ;
    
    $this['is_photo_public']
      ->setLabel(__('Show photo', null, 'ullCoreMessages'))
      ->setHelp(__('If unchecked, the photo will be visible in administrative areas only.', null, 'ullCoreMessages'))
    ;
    
    $this['first_name']
      ->setMetaWidgetClassName('ullMetaWidgetStringRegex')
      ->setValidatorOption('pattern', ullCoreTools::getRegexForNames())
    ;
    
    $this['username']
      ->setIsRequired(true)
      ->setMetaWidgetClassName('ullMetaWidgetStringRegex')
      ->setValidatorOption('pattern', '/^[a-z0-9_]+$/i')
      ->setValidatorOption('required', true)
    ;
    
    $this['password']
      ->setMetaWidgetClassName('ullMetaWidgetPassword')
      ->setWidgetOption('render_pseudo_password', true)
      ->setWidgetAttribute('autocomplete', 'off')
    ;
    
    
    
    $this['mobile_number']
      ->setLabel(__('Mobile number', null, 'ullCoreMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetPhoneNumber')
      ->setHelp(__('Format: +43 664 1234567', null, 'ullCoreMessages'))
    ;
    $this['phone_number']
      ->setLabel(__('Phone number', null, 'ullCoreMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetPhoneNumber')
      ->setHelp(__('Format: +43 2236 1234567', null, 'ullCoreMessages'))
    ;
    $this['fax_number']
      ->setLabel(__('Fax number', null, 'ullCoreMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetPhoneNumber')
      ->setHelp(__('Format: +43 2236 1234567-30', null, 'ullCoreMessages'))
    ;        
    
    $this['country']
      ->setMetaWidgetClassName('ullMetaWidgetCountry')
      ->setWidgetOption('add_empty', true);
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
    $this['num_email_bounces']
      ->setLabel(__('Number of bounced emails', null, 'ullCoreMessages'))
    ;
    
    $this['duplicate_tags_for_search']
      ->setLabel('Tags')
      ->setMetaWidgetClassName('ullMetaWidgetTaggable')
    ;
    
    $this->useManyToManyRelation('UllGroup');
    $this['UllGroup']
      ->setLabel(__('Group memberships', null, 'ullCoreMessages'))
    ;  
    
    $this->useManyToManyRelation('UllNewsletterMailingList');
    $this['UllNewsletterMailingList']
      ->setLabel(__('Newsletter', null, 'ullMailMessages'))
    ;
    
    // Set default subscriptions for new objects
    $newsletters = Doctrine::getTable('UllNewsletterMailingList')->findByIsSubscribedByDefault(true);
    $this['UllNewsletterMailingList']
      ->setDefaultValue($newsletters->getPrimaryKeys())
    ;    
    
    $this->order(array(
      'id',
      'personal' => array(
        'title',
        'first_name',
        'last_name',
        'birth_date',
        'sex',
        'photo',
        'is_photo_public',
      ),
      'it' => array(
        'username',
        'password',
        'email',
        'type',
        'UllGroup'
      ),
      'personal_contact' => array(
        'mobile_number',
        'phone_number',
        'fax_number',
        'website',
        'street',
        'post_code',
        'city',
        'country',
      ),  
      'phone_book' => array(
        'is_show_in_phonebook',
        'phone_extension',
        'is_show_extension_in_phonebook',
        'alternative_phone_extension',
        'fax_extension',
        'is_show_mobile_number_in_phonebook',
        'UllNewsletterMailingList'
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
      'human_resources' => array(
        'personnel_number',
        'ull_employment_type_id',
        'entry_date',
        'deactivation_date',
        'separation_date',
        'ull_user_status_id',
      ),
      'comment' => array(
        'duplicate_tags_for_search',
        'comment',
      )
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
        'UllGroup',
        'UllNewsletterMailingList',
        'title',
        'birth_date',
        'street',
        'post_code',
        'city',
        'country',
        'phone_number',
        'fax_number',
        'website',
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
  
  
  /**
   * Adjusts the columns configuration for the sign up and edit account action
   * 
   * Can be customized in a custom columnsConfig class in apps/frontend/...
   * 
   * @param UllUser $user
   */
  public function adjustColumnConfigForEditAccount(UllUser $user)
  {
    $showColumns = array(
      'first_name',
      'last_name',
      'email',
      'username',
      'password',
      'UllNewsletterMailingList',    
    );
    $this->disableAllExcept($showColumns);
    $this->order($showColumns);
    foreach ($showColumns as $column)
    {
      $this[$column]->setAccess('r');
    }
    
    if (!$user->exists())
    {
      $editColumns = array(
        'first_name',
        'last_name',
        'email',
        'username',
        'password',
        'UllNewsletterMailingList',    
      );
    }
    else
    {
      $editColumns = array(
        'first_name',
        'last_name',
        'email',
        'password',    
        'UllNewsletterMailingList',
      );
    }
    
    foreach ($editColumns as $column)
    {
      $this[$column]->setAccess('w');
    }    
    
    $this->setIsRequired(array(
      'first_name',
      'last_name',
      'email',
      'username',
      'password',    
    ));
    
    $this->adjustColumnConfigForEditAccountCommon($user);
  }  
  
  
  /**
   * Common settings for the edit user account columnsConfig
   * 
   * @param UllUser $user
   */
  protected function adjustColumnConfigForEditAccountCommon(UllUser $user)
  {
     $this['id']
      ->setAccess('r')
      ->setAutoRender(false)
    ;
    
    $this['email']
      ->setHelp(__('A valid email address is important for functions like sending you a new password in case you forgot yours', null, 'ullCoreMessages') . '.')
    ;

    // Passwort needs to be retained during creation
    if (!$user->exists())
    {
      $this['password']
        ->setWidgetOption('render_pseudo_password', false)
        ->setWidgetOption('always_render_empty', false)
      ;
    }
  }  
 
}