<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormTextareaFCKEditor represents a FCKEditor widget.
 *
 * You must include the FCKEditor JavaScript file by yourself.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Denny Reeh <denny.reeh@gmail.com>
 * @version    SVN: $Id:  $
 */
class sfWidgetFormTextareaFCKEditor extends sfWidgetFormTextarea
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * width:                     width of FCKEditor frame
   *  * height:                    height of FCKEditor frame
   *  * base_path:                 path to FCKEditor
   *  * CustomConfigurationsPath:  CustomConfigurationsPath for FCKEditor
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('width', '100%');
    $this->addOption('height', '200');
    $this->addOption('BasePath', '/fckeditor/');
    $this->addOption('CustomConfigurationsPath', '/fckconfig.js');
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
    //cols and rows are only specified for XHTML compliancy
  	$textarea = parent::renderContentTag('textarea', $value, array_merge(
        array('name' => $name, 'rows' => '8', 'cols' => '80'), $attributes));
        
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');        
  	
    $js = sprintf(<<<EOF
<script type="text/javascript">
  var oFCKeditor = new FCKeditor( '%s' , '%s', '%s');
  oFCKeditor.BasePath = "%s" ;
  oFCKeditor.Config["CustomConfigurationsPath"] = "%s";
  oFCKeditor.ReplaceTextarea();

//without this, IsDirty() returns true in the
//wiki->create view, which causes the js observer
//to warn about changes where there aren't any

function FCKeditor_OnComplete(editorInstance)
{
  editorInstance.ResetIsDirty();
}


</script>
EOF
    ,
      $id,
      $this->getOption('width'),
      $this->getOption('height'),
      $this->getOption('BasePath'),
      $this->getOption('CustomConfigurationsPath')
    );

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
      '/ullCorePlugin/js/fckeditor/fckeditor.js',
    );   
  }  
}
