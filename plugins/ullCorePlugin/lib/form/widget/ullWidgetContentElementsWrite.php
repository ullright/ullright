<?php

class ullWidgetContentElementsWrite extends ullWidget
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
    
    $return = '';
    
    foreach ($elementsData as &$elementData)
    {
      $elementData['controls'] = $this->renderElementControls($elementData);
      $elementData['html'] = $this->renderElementPartial($elementData);
      $elementData['form'] = $this->renderElementForm($elementData);
      
      $return .= $elementData['controls'];
      $return .= $elementData['html'];
      $return .= $elementData['form'];
      
    }

    $return .= '<textarea cols="80" rows="20">' . $value . '</textarea>';
    
//     var_dump($return);die;
    
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
      'element'  => $elementData['element'],
      'id'       => $elementData['id'],
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
   *  - $element - element type
   *  - $id      - a unique id
   *  - $values  - array of field values e.g. "headline", "body", "image", ... 
   * 
   * @param array $elementData cms element data array
   * @return string
   */
  protected function renderElementPartial($elementData)
  {
    $html = get_partial('ullTableTool/contentElementHtml', array(
      'element'  => $elementData['element'],
      'id'       => $elementData['id'],
      'values'   => $elementData['values'],
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
      'id'         => $elementData['id'],
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
