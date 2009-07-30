<?php 

class UllEntityColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['entry_date']
      ->setLabel(__('Entry date', null, 'ullCoreMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetDate');
    $this['deactivation_date']
      ->setLabel(__('Deactivation date', null, 'ullCoreMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetDate');
    $this['separation_date']
      ->setLabel(__('Separation date', null, 'ullCoreMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetDate');
    $this['ull_employment_type_id']
      ->setLabel(__('Employment type', null, 'ullCoreMessages'))
      ->setWidgetOption('add_empty', true);
    $this['ull_job_title_id']
      ->setLabel(__('Job title', null, 'ullCoreMessages'))
      ->setWidgetOption('add_empty', true);
    $this['ull_company_id']->setWidgetOption('add_empty', true);
    $this['ull_department_id']->setWidgetOption('add_empty', true);
    $this['ull_location_id']->setWidgetOption('add_empty', true);
    $this['superior_ull_user_id']->setWidgetOption('add_empty', true);
    $this['phone_extension']->setLabel(__('Phone extension', null, 'ullCoreMessages'));
    $this['is_show_extension_in_phonebook']->setLabel(__('Show phone ext. in phone book', null, 'ullCoreMessages'));
    $this['fax_extension']->setLabel(__('Fax extension', null, 'ullCoreMessages'));
    $this['is_show_fax_extension_in_phonebook']->setLabel(__('Show fax ext. in phone book', null, 'ullCoreMessages'));
    $this['email']->setMetaWidgetClassName('ullMetaWidgetEmail');
    $this['password']->setMetaWidgetClassName('ullMetaWidgetPassword');
    $this['sex']->setMetaWidgetClassName('ullMetaWidgetSex');

    $this->disable(array('version', 'is_virtual_group', 'display_name'));
    
    if ($this->isListAction())
    {
      $this->disableAllExcept(array('id', 'first_name', 'last_name', 'username', 'email'));
    } 
  }
}