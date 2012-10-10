<?php

class ullWidgetNewsLinkWrite extends sfWidgetFormInput
{
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/widget/sfWidgetFormInput#render($name, $value, $attributes, $errors)
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $this->setAttribute('name', $name);
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');       
    
    $choices[''] = ''; 
    
    foreach(UllCmsItemTable::getRootNodeSlugs() as $slug)
    {
      $navigation = UllCmsItemTable::getMenuTree($slug);
      $renderer = new ullTreeMenuSelectRenderer($navigation, 'slug');
      $choices += $renderer->render();      
    }
    
    $return = '';
    
    $return .= '<div class="ull_widget_news_link_write_page_selection" style="display: none;">';
    $return .= '  ' . __('Select a CMS page', null, 'ullNewsMessages');
    $return .= '  <br />';
    
    $return .= '  <select>';
    
    foreach ($choices as $key => $text)
    {
      $return .= '    <option value="' . $key . '">' . $text . '</option>';
    }
    
    $return .= '  </select>';
    
    $return .= '  <br />';
    $return .= '  ' . __('or enter an internet address (URL). Example:', null, 'ullNewsMessages') . ' http://www.example.com';
    
    $return .= '</div>';
    
    $return .= parent::render($name, $value, $attributes, $errors);

    $return .= javascript_tag('
$(document).ready(function() {
  $(".ull_widget_news_link_write_page_selection").show();
  
  $(".ull_widget_news_link_write_page_selection select").change(function() {
  
    if ($(this).val() == "")
    {
      var url = "";     
    }
    else
    {
      var url = "' . url_for('@ull_cms_show?slug=replace_me') . '";
      var url = url.replace("replace_me", $(this).val());
    }
  
    $("#' . $id .'").val(url);
    
  });
})

');    
    
    return $return;
  } 
}