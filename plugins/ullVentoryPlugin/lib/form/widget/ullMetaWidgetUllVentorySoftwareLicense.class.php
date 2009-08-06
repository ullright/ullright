<?php
/**
 * ullMetaWidgetUllVentoryItemModel
 *
 */
class ullMetaWidgetUllVentorySoftwareLicense extends ullMetaWidget
{
  
  protected function configureWriteMode()
  {
    
    if (!$this->getColumnConfig()->getOption('ull_ventory_software_id'))
    {
      throw new InvalidArgumentException('The option "ull_ventory_software_id" must be set');
    }
    
    $q = new Doctrine_Query();
    $q
      ->select('x.id, x.license_key, x.quantity, COUNT(i.id) as num')
      ->from('UllVentorySoftwareLicense x, x.UllVentoryItemSoftware i')
      ->where('x.ull_ventory_software_id = ?', $this->getColumnConfig()->getOption('ull_ventory_software_id'))
      ->groupBy('x.id')
      ->orderBy('x.license_key')
    ;
    
//    printQuery($q->getSql());
//    var_dump($q->getParams());
    
    $result = $q->execute();
    
//    var_dump($result->toArray());
    
    $choices = array();
    
    foreach ($result as $license)
    {
      $choices[$license->id] = $license->license_key . ' (' . __('%1% of %2% used', array('%1%' => $license->num, '%2%' => $license->quantity), 'ullVentoryMessages') . ')';
    }

    if ($choices)
    {
      $choices = array('' => '') + $choices;
      $this->addWidget(new sfWidgetFormSelect(
        array_merge(array('choices' => $choices), $this->columnConfig->getWidgetOptions()),
        $this->columnConfig->getWidgetAttributes()
      ));
    }
    else
    {
      $this->addWidget(new sfWidgetFormInputHidden(
        $this->columnConfig->getWidgetOptions(), 
        $this->columnConfig->getWidgetAttributes()
      ));
    }
    
    $this->addValidator(new sfValidatorChoice(
      array_merge(array('choices' => array_keys($choices)), $this->columnConfig->getValidatorOptions()))
    );
  }
  
}
