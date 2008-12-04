<?php

class ullWidgetTaggable extends ullWidget
{
  
//  protected function configure($options = array(), $attributes = array())
//  {
////    $this->addRequiredOption('object');
//
//    parent::configure($options, $attributes);
//  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    
    $html = '';
    
    
    
//    $tags_out = sfContext::getInstance()->getRequest()->getParameter('tags');
//      if (!$tags_out) {
//        $tags = $sf_data->getRaw('doc')->getTags();
//        $tags_out = implode(', ', array_keys($tags));
//      }

    
    $html .= $this->renderTag('input', array_merge(array('type' => 'text', 'name' => $name, 'value' => $value), $attributes));

    $tags_pop = TagTable::getPopulars();

    sfLoader::loadHelpers(array('Tags', 'I18N', 'ull'));
    $html .= '<br />' . __('Popular tags', null, 'common') . ':';
    $id = $this->generateId($name);
    $html .= tag_cloud($tags_pop, 'addTag("%s","' . $id . '")', array('link_function' => 'link_to_function'));
    $html .= ull_js_add_tag();
    
    return $html;
  }

}

?>