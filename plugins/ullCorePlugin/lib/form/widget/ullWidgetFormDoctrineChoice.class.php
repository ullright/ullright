<?php 

/**
 * Enhancement to sfWidgetFormDoctrineSelect
 * 
 * Adds 
 *   * support for js search box which filters the select options
 *   * inline adding and editing of select box entries
 *    
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullWidgetFormDoctrineChoice extends sfWidgetFormDoctrineChoice
{
  
  /**
   * @see sfWidget
   */
  // Todo: check if this can be moved to ullWidgetFormChoiceUllEntity
//  public function __construct($options = array(), $attributes = array())
//  {
//    // Optionaly support choices because sfWidgetFormDoctrineChoice removes them
//    $choices = array();
//    if (isset($options['choices']))
//    {
//      $choices = $options['choices'];
//    }
//    
//    parent::__construct($options, $attributes);
//    
//    if ($choices)
//    {
//      $this->setOption('choices', $choices);
//    }
//  }
  
  /**
   * Constructor.
   *
   * @see sfWidgetFormSelect
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    // prepend a searchbox to simplify finding options in the select box
    $this->addOption('show_search_box', false);
    // enable ajax inline editing of related records 
    $this->addOption('enable_inline_editing', false);
  }  

  /**
   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/widget/sfWidgetFormSelect#render()
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');
    
    $return = '';
    
    $return .= $this->renderSearchBox($id);

    $return .= parent::render($name, $value, $attributes, $errors);
    
    $return .= $this->renderInlineEditing($id, $name);
    
    return $return;
  }  
  
  
  /**
   * Sort the choices in a natural way
   *
   * @return array An array of choices
   */
  public function getChoices()
  {
    if ($choices = $this->getOption('choices'))
    {
      return $choices;
    }
    
    $choices = parent::getChoices();
    
    if (!$this->getOption('order_by'))
    {
      natcasesort($choices);
    }

    return $choices;
  }
  
  
  /**
   * Renders search box field for select boxes
   * @param string $id
   * @return string
   */
  protected function renderSearchBox($id)
  {
    $return = '';
    
    if ($this->getOption('show_search_box') == true)
    {
      $return .= javascript_tag('
$(document).ready(function()
{
  $("#' . $id . '").addSelectFilter();
});
      ');      
    } 

    return $return;
  }
  
  
  /**
   * Renders javascripts for inline editing/adding of select box entries
   * 
   * @param string $id
   * @param string $name
   * @return string
   */
  protected function renderInlineEditing($id, $name)
  {
    if (!$this->getOption('enable_inline_editing'))
    {
      return;
    }
    
    $return = '';
    
    $return .= $this->renderControls($id, $name);
      
    $return .= $this->renderOverlayJavascript($id, $name);
    
    return $return;
  }
  
  /**
   * Render inline editing controls (add, edit buttons)
   */
  public function renderControls($id, $name)
  {
    $return = '';

    $return .= $this->renderAddControl($id, $name);
    
    $return .= $this->renderEditControl($id, $name);
    
    return $return;
  }

  
  /**
   * Render add button
   */
  public function renderAddControl($id, $name)
  {
    $return = '';
    
    $return .= ' <span class="ull_widget_form_doctrine_select">';
    $return .= link_to_function(
      '+', 
      'ullOverlay_' . $id .'("create")' 
    ); 
    $return .= '</span>';
    
    return $return;
  }
  
  
  /**
   * Render edit button
   */
  public function renderEditControl($id, $name)
  {
    $return = '';
    
    $return .= ' <span class="ull_widget_form_doctrine_select">';
    $return .= link_to_function(
        ull_image_tag('edit'),
        'ullOverlay_' . $id .'("edit")'
      );
    $return .= '</span>';

    return $return;
  }
  
  public function renderOverlayJavascript($id, $name)
  {
    $return = javascript_tag('

function ullOverlay_' . $id .'(action) {

  /* @see: http://flowplayer.org/tools/overlay/index.html */

  if (action == "create") {
    var url = ' . $this->renderCreateUrl($id, $name) . ';
    
  } else if (action == "edit") {
    var optionId = $("#' . $id . '").val();
    
    if (!optionId) {
      alert("' . __('Please select an entry from the list first', null, 'common') . '.");
      return false;
    }
      
    var url = ' . $this->renderEditUrl($id, $name) . ';
      
  } else {
    throw new exception ("Invalid action given");
  }
  
  // grab wrapper element inside content
  var wrap = $("#overlay").find(".overlayContentWrap");

  // load the page specified in the trigger
  wrap.load(url, function (response, status, xhr) {
  
    if (status == "error") {
      alert("Sorry, an error occured. Please try again! (" + xhr.status + " " + xhr.statusText + ")");
    } 
    
    if (!wrap.html())
    {
      alert("Sorry, an error occured. Please try again! (Load failure)");
    }
  
    $("#overlay").overlay({
  
      fixed: false,
      mask: {
        color: "#666666",
        loadSpeed: 1000,
        opacity: 0.7
      },
      load: true,
  
      onClose: function () {
      
        // Check if the widget data was modified (create/edit)
        //   and if so reload the widget markup  
        if (window.overlayIsModified == true) {
        
          // call the current action to request the updated widget
          // the action must support this manually
          var url = "' . ull_url_for(array('field' => $name)) . '"; 
          
          $.ajax({  
            url: url,  
            timeout: 5000,
            /* The ajax call returns the updated widget as html and we replace the old one */
            success: function(data) {  
              $("#' . $id . '").parents("td").html(data);
              
              ' . $this->renderPostWidgetReload($id, $name) . '
            },
            error: function(msg){
              alert("Sorry, an error occured. Please try again! (" + msg + ")");
            }
            
          });
        }
      } 
  
    }).load();  
    
    $(this).scrollTop(0);
  
  });
  
  
}
');
    
  return $return;
    
  }

  /**
   * Render javascript code to be executed after the widget has been reloaded
   * 
   * @param string $id
   * @param string $name
   */
  public function renderPostWidgetReload($id, $name)
  {
    $return = '
// Set / select added entry
$("#' . $id . '").val(window.overlayId);
// trigger change event (e.g. used by ullWidgetUllEntityAjaxWrite) 
$("#' . $id. '").triggerHandler("change");
';
    
    return $return;
  }

  /**
   * Render the create URL for inline editing
   * 
   * @param unknown_type $id
   * @param unknown_type $name
   */
  public function renderCreateUrl($id, $name)
  {
    $return = '"' . url_for('ullTableTool/create?table=' . $this->getOption('model')) . '"';
    
    return $return;
  }
  
  
  /**
   * Render the edit URL for inline editing
   * 
   * @param unknown_type $id
   * @param unknown_type $name
   */
  public function renderEditUrl($id, $name)
  {
    $return = '"' . url_for('ullTableTool/edit?table=' . $this->getOption('model')) . 
      '/id/" + optionId';
    
    return $return;    
  }
  
  
  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    $return = parent::getJavaScripts();
    
    $return = array_merge($return, array(
      '/ullCorePlugin/js/jq/jquery-min.js', 
      '/ullCorePlugin/js/jq/jquery.add_select_filter.js',
      '/ullCorePlugin/js/jq/jquery.tools.min.js', // for overlay
    ));   
    
    return $return;
  }  
}
