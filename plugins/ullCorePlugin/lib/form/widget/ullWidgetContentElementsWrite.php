<?php

class ullWidgetContentElementsWrite extends sfWidgetFormTextarea
{
  protected 
    $elementJavascripts,
    $elementStylesheets
  ;
  
  public function __construct($options = array(), $attributes = array())
  {
    $this->addRequiredOption('element_types');
    $this->addOption('css_class');
    $this->addOption('stylesheet');
    
    parent::__construct($options, $attributes);
    
    $this->manageElementAssets($this->getOption('element_types'));
    
    if ($stylesheet = $this->getOption('stylesheet'))
    {
      $this->elementStylesheets[$stylesheet] = 'all';
    }
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
    $this->setAttribute('class', 'content_elements_value');
    
    
  }  
  

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    // populate field's html id attribute
    $this->setAttribute('name', $name);
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    
    $elementTypes = $this->getOption('element_types');
    
    if (!is_array($elementTypes))
    {
      throw new InvalidArgumentException('List of elements must be given as array');
    }
    
    $elementsData = $this->extractElementsData($value);
    
    $field = parent::render($name, $value, $attributes, $errors);
    
    $return = get_partial('ullWidget/ullContentElements', array(
      'field_id'       => $this->getAttribute('id'),
      'field'          => $field,
      'element_types'  => $elementTypes,
      'elements_data'  => $elementsData,
      'css_class'      => $this->getOption('css_class'),
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
      // attr(value) is the value of the input type=hidden field
      $data[] = json_decode($dataField->attr('value'), true);
    }

    return $data;
  }
  
  /**
   * Load javascripts and stylesheets from the used elements
   * 
   * @param array $types
   */
  protected function manageElementAssets($types)
  {
    $this->elementJavascripts = array();
    $this->elementStylesheets = array();
    
    foreach ($types as $type => $name)
    {
      $generator = new ullContentElementGenerator(
        $type,
        'id'
      );
      $generator->buildForm(new UllContentElement());
      $form = $generator->getForm();
      
      $this->elementJavascripts = array_merge(
        $this->elementJavascripts,
        $form->getJavaScripts()
      );  
      
      $this->elementStylesheets = array_merge(
        $this->elementStylesheets,
        $form->getStylesheets()
      );      
    }      
  }
  
  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    $javascripts = array(
      '/ullCorePlugin/js/jq/jquery-min.js',
      '/ullCorePlugin/js/ullWidgetContentElements.js',
    );
    
    $javascripts = array_merge(
      $javascripts,
      $this->elementJavascripts
    );
    
    return $javascripts;
  }  
  
  public function getStylesheets()
  {
    $stylesheets = array(
      '/ullCorePlugin/css/ullWidgetContentElements.css' => 'all',
    );
    
    $stylesheets = array_merge(
      $stylesheets,
      $this->elementStylesheets
    );
    
    return $stylesheets;    
  }  
}
