<?php

class ullWidgetNewsLinkWrite extends sfWidgetFormInput
{
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/widget/sfWidgetFormInput#configure($options, $attributes)
   */
  protected function configure($options = array(), $attributes = array())
  {

  }
    
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/widget/sfWidgetFormInput#render($name, $value, $attributes, $errors)
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $pages = Doctrine::getTable('UllCmsItem')->findByType('page');
    
    $select = '<option value="" selected="selected"></option>';
    foreach ($pages as $page)
    {
      $select .= '<option value="ullCms/show?slug=' . $page['slug'] . '">' . $page['full_path'] . '</option>';
    }
    
    $return = javascript_tag('$(document).ready(function()
    {
      obj = document.getElementsByTagName("div");
      obj["pages_to_link"].style.display = "";
    })
    
    function changeLinkUrl(id, value)
    {
      obj = document.getElementsByTagName("input");
      obj[id].value = value;
    }
    ');
    
    $return .= '<div id="pages_to_link" style="display: none;">';
    $return .= __('Select a CMS page', null, 'ullNewsMessages');
    $return .= '<br />';
    $SelectWidget =  new sfWidgetFormInput();
    $return .= $SelectWidget->renderContentTag('select', 
      $select, 
      array('id' => 'fields_link_url_pages', 'onchange' => 'changeLinkUrl("fields_link_url", value)')
    );
    $return .= '<br />';
    $return .= __('or enter an internet address (URL). Example:', null, 'ullNewsMessages') . ' http://www.example.com';
    $return .= '</div>';
    $return .= parent::render($name, $value, $attributes, $errors);
    
    return $return;
  } 
}