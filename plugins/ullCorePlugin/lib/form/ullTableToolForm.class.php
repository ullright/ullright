<?php
/**
 * sfForm for the table tool
 *
 */
class ullTableToolForm extends ullGeneratorForm
{

  /**
   * Override getting the default values form the object
   *
   */
  protected function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();
    
    if ($this->isI18n()) 
    {
      $defaults = $this->getDefaults();

      if (isset($defaults[@$this->cultures[0]])) 
      {

        $i18nFields = $defaults[$this->cultures[0]];
        unset($i18nFields['id']);
        unset($i18nFields['lang']);

        foreach ($i18nFields as $fieldName => $value)
        {
          foreach ($this->cultures as $culture)
          {
            $newFieldName = $fieldName . '_translation_' . $culture; 
            $defaults[$newFieldName] = $defaults[$culture][$fieldName];
          }
        }
      }

      $this->setDefaults($defaults);
    }
  }

  /**
   * Override the update functionality of the object
   *
   * @return Doctrine_Record
   */
  public function updateObject()
  {
    $values = $this->getValues();
    
//    var_dump($values);die;

    // remove special columns that are updated automatically
    unset($values['id'], $values['updated_at'], $values['updated_on'], $values['created_at'], $values['created_on']);

    foreach ($values as $fieldName => $value)
    {
      if (strstr($fieldName, '_translation_'))
      {
        unset($values[$fieldName]);

        $parts = explode('_', $fieldName);
        $culture = array_pop($parts);
        array_pop($parts);
        $realFieldName = implode('_', $parts);

        $values['Translation'][$culture]['lang'] = $culture;
        $values['Translation'][$culture][$realFieldName] = $value;
      }
    }

    $this->object->fromArray($values);

    return $this->object;
  }

}