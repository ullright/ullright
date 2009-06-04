<?php
/**
 * ullMetaWidgetUllSelect
 *
 *
 * available options:
 *  'ull_select' => string, slug of a UllSelect
 *  'add_empty'  => boolean, true to include an empty entry
 */
class ullMetaWidgetUllSelect extends ullMetaWidget
{
  public function __construct($columnConfig, sfForm $form)
  {
    parent::__construct($columnConfig, $form);

    if ($this->columnConfig->getWidgetOption('ull_select') == null)
    {
      throw new InvalidArgumentException('option "ull_select" is required');
    }
  }

  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      // query only children for the given ull select box
      $ullSelect = $this->columnConfig->getWidgetOption('ull_select');
      $this->columnConfig->removeWidgetOption('ull_select');
      $q = new Doctrine_Query;
      $q
        ->from('UllSelectChild a')
        ->where('a.UllSelect.slug = ?', $ullSelect)
      ;

      $this->columnConfig->setWidgetOption('model', 'UllSelectChild');
      $this->columnConfig->setWidgetOption('order_by', array('sequence', 'asc'));
      $this->columnConfig->setWidgetOption('query', $q);

      $this->columnConfig->setValidatorOption('model', 'UllSelectChild');
      $this->columnConfig->setValidatorOption('query', $q);


      $this->addWidget(new sfWidgetFormDoctrineSelect($this->columnConfig->getWidgetOptions(),
        $this->columnConfig->getWidgetAttributes()));
      $this->columnConfig->setWidgetOption('ull_select', $ullSelect);
      $this->addValidator(new sfValidatorDoctrineChoice($this->columnConfig->getValidatorOptions()));
    }
    else
    {
      $this->columnConfig->removeWidgetOption('add_empty');

      $this->addWidget(new ullWidgetUllSelect($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
      $this->addValidator(new sfValidatorPass());
    }

    //    var_dump($columnConfig);die;
  }
  
  public function getSearchPrefix()
  {
    return 'foreign';
  }
}