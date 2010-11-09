<?php

/**
 * Taggable widget
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullWidgetTaggable extends ullWidget
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $html = '';
    $html .= $this->renderTag('input', array_merge(array('type' => 'text', 'name' => $name, 'value' => $value), $attributes));

    $tags_pop = TagTable::getPopulars();

    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Tags', 'I18N', 'ull'));
    $html .= '<br />' . __('Popular tags', null, 'common') . ':';
    $id = $this->generateId($name);
    $html .= tag_cloud($tags_pop, 'addTag("%s","' . $id . '")', array('link_function' => 'link_to_function'));
    $html .= ull_js_add_tag();
    
    var_dump($html);
    
    return $html;
  }

}
