<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class BaseUllMailLoggedMessageTableConfiguration extends UllTableConfiguration
{
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Logged Mail Messages', null, 'ullMailMessages'));
    $this->setSearchColumns(array('to_list'));
    $this->setOrderBy('sent_at DESC');
    $this->setListColumns(array(
      'sender',
      'to_list',
      'subject',
      'sent_at',
    ));
//    $this->setFilterColumns(array(
//      'ull_newsletter_edition_id' => ''
//    ));
    $this->setPlugin('ullMailPlugin');
    $this->setBreadcrumbClass('ullNewsletterBreadcrumbTree');     
    $this->setCustomRelationName('MainRecipient', __('Recipient', null, 'ullMailMessages'));
    
    $query = sfContext::getInstance()->getRequest()->getParameter('query');
    
    switch ($query) 
    {
      case 'read':
        $this->setName(__('Readers of newsletter', null, 'ullMailMessages'));
        $this->setListColumns(array(
          'MainRecipient->display_name',
          'MainRecipient->email',
          'first_read_at',
          'num_of_readings',
          'last_user_agent',
        ));
        $this->setOrderBy('num_of_readings DESC, MainRecipient->display_name, MainRecipient->email');
//        $this->setFilterColumns(array(
//          'ull_newsletter_edition_id' => ''
//        ));               
        break;
    }
    
    
  }  
  
}