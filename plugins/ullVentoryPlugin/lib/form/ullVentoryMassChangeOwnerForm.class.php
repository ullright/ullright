<?php
class ullVentoryMassChangeOwnerForm extends sfForm
{
  public function configure()
  {
    $cc = new ullColumnConfiguration();
    $cc->setOption('entity_classes', array('UllVentoryStatusDummyUser', 'UllUser'));
    $cc->setWidgetOption('add_empty', true);
    $cc->setValidatorOption('required', true);
    $newOwnerWidget = new ullMetaWidgetUllEntity($cc, $this);
    $newOwnerWidget->addToFormAs('ull_new_owner_entity_id');
    
    $commentWidget = new ullMetaWidgetString(new ullColumnConfiguration(), $this);
    $commentWidget->addToFormAs('ull_change_comment');
    
    $this->widgetSchema->setLabels(array(
      'ull_new_owner_entity_id' => __('New owner', null, 'ullVentoryMessages'),
      'ull_change_comment' => __('Comment', null, 'common'),
    ));
    
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
    $this->getWidgetSchema()->setFormFormatterName('ullTable');
  }
}