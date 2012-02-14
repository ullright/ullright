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
//    $this->addOption('BasePath', '/CKeditor/');
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

//    $js = javascript_tag('
//CKEDITOR.replace("' . $id . '", 
//  {
//    customConfig :  "' . $this->getOption('CustomConfigurationsPath') . '", 
//    width:          "' . $this->getOption('width') . '",
//    height:         "' . $this->getOption('height') . '"
//  }
//);
//
//');
    
    $js = javascript_tag('

$("#' . $id . '").ckeditor(
  function() { /* callback code */ }, 
  { 
    customConfig :  "' . $this->getOption('CustomConfigurationsPath') . '", 
    width:          "' . $this->getOption('width') . '",
    height:         "' . $this->getOption('height') . '"
  }  
);

');
    
    
// var data = $( 'textarea.editor' ).val();

    
  	
//    $js = sprintf(<<<EOF
//<script type="text/javascript">
//  var oCKeditor = new CKeditor( '%s' , '%s', '%s');
//  oCKeditor.BasePath = "%s" ;
//  oCKeditor.Config["CustomConfigurationsPath"] = "%s";
//  oCKeditor.ReplaceTextarea();
//
////without this, IsDirty() returns true in the
////wiki->create view, which causes the js observer
////to warn about changes where there aren't any
//
//function CKeditor_OnComplete(editorInstance)
//{
//  editorInstance.ResetIsDirty();
//}
//
//
//</script>
//EOF
//    ,
//      $id,
//      $this->getOption('width'),
//      $this->getOption('height'),
//      $this->getOption('BasePath'),
//      $this->getOption('CustomConfigurationsPath')
//    );

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
