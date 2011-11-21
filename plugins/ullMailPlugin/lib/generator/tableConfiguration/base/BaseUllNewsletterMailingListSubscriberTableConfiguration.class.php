<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class BaseUllNewsletterMailingListSubscriberTableConfiguration extends UllTableConfiguration
{
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Newsletter subscribers', null, 'ullMailMessages'));
    $this->setSearchColumns(array('UllUser->display_name', 'UllUser->email'));
    $this->setOrderBy('UllUser->display_name, UllUser->email');
    $this->setFilterColumns(array('ull_newsletter_mailing_list_id' => null));
    $this->setListColumns(array(
      'ull_newsletter_mailing_list_id',
      'ull_user_id',
      'UllUser->email',
      'UllUser->num_email_bounces',
    ));
    $this->setCustomRelationName('UllUser', ' ');
    
    $this->setPlugin('ullMailPlugin');
    $this->setBreadcrumbClass('ullNewsletterBreadcrumbTree');
    
  }
}