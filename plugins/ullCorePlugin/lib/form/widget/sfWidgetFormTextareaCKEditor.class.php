<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormTextareaCKEditor represents a CKEditor widget.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Klemens Ullmann-Marx k@ull.at
 * @version    SVN: $Id:  $
 */
class sfWidgetFormTextareaCKEditor extends sfWidgetFormTextarea
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * width:                     width of CKEditor frame<
   *  * height:                    height of CKEditor frame
   *  * base_path:                 path to CKEditor
   *  * CustomConfigurationsPath:  CustomConfigurationsPath for CKEditor
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('width', '100%');
    $this->addOption('height', '200px');
    $this->addOption('CustomConfigurationsPath', '/config.js');
  }

  
  /**
   * @param  string $name        The element name
   * @param  string $value       The value selected in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (is_array($value) && array_key_exists('value', $value))
    {
      $value = $value['value'];
    }
    
    //cols and rows are only specified for XHTML compliancy
  	$textarea = parent::renderContentTag('textarea', $value, array_merge(
        array('name' => $name, 'rows' => '8', 'cols' => '80'), $attributes));
        
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id'); 

    $js = javascript_tag('
        
     // TODO: 2012-10-02 check if this is still needed....
  // Check if an instance is already running. If so, destroy it
  // This is necessary if an CKEditor instance is replaced via ajax              
//   instance = CKEDITOR.instances["' . $id . '"];
//   if (instance) { 
//     instance.destroy(true);    
//   }
  
  $("#' . $id . '").ckeditor(
    function() { /* callback code */ }, 
    { 
      customConfig :  "' . $this->getOption('CustomConfigurationsPath') . '", 
      width:          "' . $this->getOption('width') . '",
      height:         "' . $this->getOption('height') . '"
    }  
  );

');

    return $textarea . $js;
  }
  
  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    return array(
      '/ullCorePlugin/js/ckeditor/ckeditor.js',
      '/ullCorePlugin/js/ckeditor/adapters/jquery.js',
    );   
  }  
}
