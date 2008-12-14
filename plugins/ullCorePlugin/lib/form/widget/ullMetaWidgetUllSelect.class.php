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

    if (!isset($this->columnConfig['widgetOptions']['ull_select']))
    {
      throw new InvalidArgumentException('option "ull_select" is required');
    }
  }

  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      // query only children for the given ull select box
      $ullSelect = $this->columnConfig['widgetOptions']['ull_select'];
      unset($this->columnConfig['widgetOptions']['ull_select']);
      $q = new Doctrine_Query;
      $q
        ->from('UllSelectChild a')
        ->where('a.UllSelect.slug = ?', $ullSelect)
      ;

      $this->columnConfig['widgetOptions']['model'] = 'UllSelectChild';
      $this->columnConfig['widgetOptions']['order_by'] = array('sequence', 'asc');
      $this->columnConfig['widgetOptions']['query'] = $q;

      $this->columnConfig['validatorOptions']['model'] = 'UllSelectChild';
      $this->columnConfig['validatorOptions']['query'] = $q;


      $this->addWidget(new sfWidgetFormDoctrineSelect(
        $this->columnConfig['widgetOptions'],
        $this->columnConfig['widgetAttributes']
      ));
      $this->addValidator(new sfValidatorDoctrineChoice($this->columnConfig['validatorOptions']));
    }
    else
    {
      unset($this->columnConfig['widgetOptions']['add_empty']);

      $this->addWidget(new ullWidgetUllSelect($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }

    //    var_dump($columnConfig);die;
  }
}

?>