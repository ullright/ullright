<?php

/**
 * This meta widget provides support for many to many relationships.
 * Both widget and validator need the 'model' option set.
 */
class ullMetaWidgetManyToMany extends ullMetaWidget
{
  protected function configureWriteMode()
  {
    //since this is a widget for many to many relationships,
    //multiple is always true
    $defaultOptions = array('multiple' => true);

    $this->addWidget(new ullWidgetManyToManyWrite(
      array_merge($defaultOptions, $this->columnConfig->getWidgetOptions())));
    $this->addValidator(new sfValidatorDoctrineChoice(
      array_merge($defaultOptions, $this->columnConfig->getValidatorOptions())));
  }

  protected function configureReadMode()
  {
    $options = $this->columnConfig->getWidgetOptions();
    unset ($options['owner_model']);
    unset ($options['owner_relation_name']);
    $this->addWidget(new ullWidgetManyToManyRead($options));
    $this->addValidator(new sfValidatorPass());
  }
}