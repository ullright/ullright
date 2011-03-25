<?php
/**
 * ullMetaWidgetString 
 * 
 * Used for strings
 */
class ullMetaWidgetTaggable extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      if ($this->columnConfig->getWidgetAttribute('size') == null)
      {
        $this->columnConfig->setWidgetAttribute('size', '50');
      }
      
      $this->columnConfig->setWidgetOption('typeahead-url', url_for('taggableComplete/complete'));
      $this->columnConfig->setWidgetOption('tags-label', __('Selected tags', null, 'common') . ':');
      $this->columnConfig->setWidgetOption('popular-tags-label', __('Popular tags', null, 'common') . ':');
      $this->columnConfig->setWidgetOption('popular-tags', TagTable::getPopulars());
      $this->columnConfig->setWidgetOption('add-tag-label', __('Add', null, 'common'));
      $this->columnConfig->setWidgetOption('selected_tag_markup',
        '<span class="ull_widget_taggable_selected_tag_element color_light_bg" title="%s">' .
          '<span>%s</span>' .
          '<a class="ull_widget_taggable_selected_tag_remove_tag" title="%s">' . ull_image_tag('delete', array(), 12, 12) . '</a>' .
    	  '</span>'
    	);
      // TODO: neuen Validator nehmen
    	$this->addWidget(new ullWidgetTaggable($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
      $this->addValidator(new ullValidatorTaggable($this->columnConfig->getValidatorOptions()));
    }
    else
    {
      $this->addWidget(new ullWidget($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
      $this->addValidator(new sfValidatorPass());
    }
    
  }  
}
