<?php

class ullWidgetContentElementsWrite extends sfWidgetFormTextarea
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addRequiredOption('elements');
    
    parent::__construct($options, $attributes);
  }
  
  /**
   * Configures the current widget.
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->setAttribute('rows', 20);
    $this->setAttribute('cols', 80);
  }  
  

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    // populate field's html id attribute
    $this->setAttribute('name', $name);
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    
    $elements = $this->getOption('elements');
    
    if (!is_array($elements))
    {
      throw new InvalidArgumentException('List of elements must be given as array');
    }
    
    $elementsData = $this->extractElementsData($value);
    
    $elementsMarkup = '';
    
    foreach ($elementsData as $elementData)
    {
      $element_id = $elementData['element_id'];
      $elementsMarkup[$element_id] = '';
      $elementsMarkup[$element_id] .= $this->renderElementControls($elementData);
      $elementsMarkup[$element_id] .= $this->renderElementHtml($elementData);
      $elementsMarkup[$element_id] .= $this->renderElementForm($elementData);
    }
    
    $return = get_partial('ullTableTool/contentElements', array(
      'field_id'       => $this->getAttribute('id'),
      'field'          => parent::render($name, $value, $attributes, $errors),
      'value'          => $value,
      'elements_markup'=> $elementsMarkup,
    ));
    
    return $return;
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
   * Render element controls
   * 
   * @param array $elementData cms element data array
   * @return string
   */
  protected function renderElementControls($elementData)
  {
    $html = get_partial('ullTableTool/contentElementControls', array(
      'element'    => $elementData['element'],
      'element_id' => $elementData['element_id'],
      'field_id'   => $this->getAttribute('id'),
    ));    
    
    return $html;
  }  
  
  /**
   * Render element html markup
   * The templates are symfony partials.
   * 
   * Naming convention for the partial:
   *   '_element' . $camelCasedElementName . '.php
   * Path: apps/frontend/modules/ullTableTool/templates/
   *   
   * Example: apps/frontend/modules/ullTableTool/templates/_elementTextWithImage.php
   * 
   * Available variables in the partial:
   *  - $element    - element type
   *  - $element_id - a unique id foreach element
   *  - $values     - array of field values e.g. "headline", "body", "image", ... 
   * 
   * @param array $elementData cms element data array
   * @return string
   */
  protected function renderElementHtml($elementData)
  {
    $html = get_partial('ullTableTool/contentElementHtml', array(
      'element'    => $elementData['element'],
      'element_id' => $elementData['element_id'],
      'values'     => $elementData['values'],
    ));
    
    return $html;
  }
  
  
  /**
   * Render element edit form
   * 
   * An element's form is configured by a columnsConfig
   * 
   * @see BaseUllContentElementColumnConfigCollection
   * 
   * @param array $elementData cms element data array
   * @return string
   */
  protected function renderElementForm($elementData)
  {
    $generator = new ullContentElementGenerator($elementData['element']);
    $generator->buildForm(new UllContentElement());
    
    $form = $generator->getForm();
    $form->setDefaults($elementData['values']);
    
    $html = get_partial('ullTableTool/contentElementForm', array(
      'generator'  => $generator,
      'element'    => $elementData['element'],
      'element_id' => $elementData['element_id'],
      'field_id'   => $this->getAttribute('id'),
    ));    
    
    return $html;
  }
  
  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    return array(
      '/ullCorePlugin/js/jq/jquery-min.js',
      '/ullCorePlugin/js/content_elements.js',
    );
  }  
}
