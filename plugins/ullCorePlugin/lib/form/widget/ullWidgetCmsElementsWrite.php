<?php

class ullWidgetCmsElementsWrite extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addRequiredOption('elements');
    
    parent::__construct($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $elements = $this->getOption('elements');
    
    if (!is_array($elements))
    {
      throw new InvalidArgumentException('List of elements must be given as array');
    }
    
    $elementsData = $this->extractElementsData($value);
    
    foreach ($elementsData as &$elementData)
    {
      $elementData['html'] = $this->renderElementPartial($elementData);
    }
    
    var_dump($elementsData);
    
    
    return '<textarea cols="80" rows="20">' . $value . '</textarea>';
  }
  
  /**
   * Extract the element's data values from the input/type=hidden fields
   * 
   * @param string $value
   */
  protected function extractElementsData($value)
  {
    $qp = new QueryPath($value);
    $dataFields = $qp->find('input[type="hidden"]');
    
    $data = array();
    
    foreach($dataFields as $dataField)
    {
      $data[] = json_decode($dataField->attr('value'), true);
    }

    return $data;
  }
  
  
  /**
   * Render element html markup
   * The templates are symfony partials.
   * 
   * Naming convention for the partial:
   *   '_element' . $camelCasedElementName . '.php
   * Path: apps/frontend/modules/ullCms/templates/
   *   
   * Example: apps/frontend/modules/ullCms/templates/_elementTextWithImage.php
   * 
   * Available variables in the partial:
   *  - $element - element type
   *  - $id      - a unique id
   *  - $values  - array of field values e.g. "headline", "body", "image", ... 
   * 
   * @param array $elementData cms element data array
   * @return string
   */
  protected function renderElementPartial($elementData)
  {
    $partialName = 'ullCms/' . 'element' . 
      sfInflector::classify($elementData['element']);
    
    $html = get_partial($partialName, array(
      'element'  => $elementData['element'],
      'id'       => $elementData['id'],
      'values'   => $elementData['values'],
    ));
    
    // Decorate with a div
    $html = '<div class="cms_element element_' . $elementData['element'] . '" '.
      'id="element_' . $elementData['id'] . '" >' .
      $html . '</div>';
    
    return $html;
  }
}
