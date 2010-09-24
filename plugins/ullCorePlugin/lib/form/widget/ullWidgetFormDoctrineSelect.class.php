<?php 

/**
 * Enhancement to sfWidgetFormDoctrineSelect
 * 
 * Adds 
 *   * support for js search box which filters the select options
 *   * inline adding of select box entries
 *    
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullWidgetFormDoctrineSelect extends sfWidgetFormDoctrineSelect
{
  
  /**
   * Constructor.
   *
   * @see sfWidgetFormSelect
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addOption('show_search_box', false);
    $this->addOption('enable_inline_adding', false);
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
    
    $return .= $this->renderInlineAdding($id, $name);
    
    return $return;
  }  
  
  
  /**
   * Sort the choices in a natural way
   *
   * @return array An array of choices
   */
  public function getChoices()
  {
    $choices = parent::getChoices();
    
    if (!$this->getOption('order_by'))
    {
      natcasesort($choices);
    }

    return $choices;
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
      '/ullCorePlugin/js/jq/jquery.add_select_filter.js',
      '/ullCorePlugin/js/jq/jquery.tools.min.js',
    );   
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
   * Renders javascripts for inline addition of select box entries
   * 
   * @param string $id
   * @param string $name
   * @return string
   */
  protected function renderInlineAdding($id, $name)
  {
    $return = '';
    
    if ($this->getOption('enable_inline_adding') == true)
    {
      $return .= ' <span class="ull_widget_form_doctrine_select">' .
        link_to('+', 'ullTableTool/create?table=' . $this->getOption('model'), array('rel'  => '#overlay')) . 
        '</span>';
        
      $return .= '<div class="overlay" id="overlay"> <div class="overlayContentWrap"></div> </div>';
  
      $return .= javascript_tag('
    
$(function() {

  /* Documentation @http://flowplayer.org/tools/overlay/index.html */
  $("a[rel]").overlay({

    fixed: false,
    mask: {
      color: "#666666",
      loadSpeed: 200,
      opacity: 0.9
    },

    onBeforeLoad: function() {
      // grab wrapper element inside content
      var wrap = this.getOverlay().find(".overlayContentWrap");

      // load the page specified in the trigger
      wrap.load(this.getTrigger().attr("href"));
    },
    
    onClose: function () {
      // check trigger if we want to ajax save the form on close
      if (window.overlaySaveOnClose == true) {
        $.ajax({  
          url: "' . ull_url_for(array('field' => $name)) . '",  
          /* The ajax call returns the updated widget as html and we replace the old one */
          success: function(data) {  
            $("#' . $id . '").parent().replaceWith(data);
            // Select added entry
            $("#' . $id . '").val(window.overlayId);
          }  
        });
      }
    } 

  });
});

');
    }    
    
    return $return;
  }
  
}
