<?php
class ullMassChangeSuperiorForm extends sfForm
{
  public function configure()
  {
    $q = new Doctrine_Query;
    $q
      ->select('u.id, u.first_name, u.last_name')
      ->from('UllUser u')
      ->orderBy('u.last_name, u.first_name');
    
    $wo = array('model' => 'UllEntity', 'query' => $q, 'method' => 'getLastNameFirst');

    $tempCC = new ullColumnConfiguration();
    $oldSuperiorWidget = new ullMetaWidgetUllUser($tempCC, $this);
    $newSuperiorWidget = new ullMetaWidgetUllUser($tempCC, $this);
    
    $oldSuperiorWidget->addToFormAs('old_superior');
    $newSuperiorWidget->addToFormAs('new_superior');
    
//    $this->setWidgets(array(
//        'old_superior'    => new ullWidgetUllUser($wo),
//        'new_superior'   => new ullWidgetUllUser($wo),
//    ));

    $this->widgetSchema->setLabels(array(
      'old_superior'  => __('Current superior:', null, 'common'),
      'new_superior'  => __('Replacing superior:', null, 'common')
    ));
    
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
    $this->getWidgetSchema()->setFormFormatterName('ullTable');
    $this->validatorSchema->setOption('allow_extra_fields', true);
    
  }
}