<?php

class ullMetaWidgetFloat extends ullMetaWidget
{
  protected function configure()
  {
    $maxDecimalsOption = $this->columnConfig->getOption('max_decimals');
    $suffixOption = $this->columnConfig->getOption('suffix');

    if ($suffixOption)
    {
      $this->columnConfig->setWidgetOption('suffix', $suffixOption);
    }

    if ($maxDecimalsOption)
    {
      $this->columnConfig->setValidatorOption('max_decimals', $maxDecimalsOption);
    }
  }

  protected function configureWriteMode()
  {
    $this->addWidget(new ullWidgetFloatWrite($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new ullValidatorNumberI18n($this->columnConfig->getValidatorOptions()));
  }

  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetFloatRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }

  public function getSearchType()
  {
    return 'range';
  }

  /**
   * Formats a float value adhering to the rules of the currently
   * set culture.
   *
   * e.g. 14000.150 to 14.000,150 (german style)
   *
   * @param $value A float value
   * @return The formatted value
   */
  public static function formatNumber($value)
  {
    $currentCulture = sfContext::getInstance()->getUser()->getCulture();
    $numberFormatInfo = sfNumberFormatInfo::getInstance($currentCulture);
    $numberFormat = new sfNumberFormat($numberFormatInfo);

    return $numberFormat->format($value, ',##0.#');
  }
  
  /**
   * Overriden because this meta widget represents
   * numeric values
   * 
   * @return true
   */
  public static function isNumeric()
  {
    return true;
  } 
}