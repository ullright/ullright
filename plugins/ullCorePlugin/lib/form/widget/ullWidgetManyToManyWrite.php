<?php
/**
 * This widget provides support for editing a many to many
 * relationship (i.e. where m records are linked to n records
 * from a different model via an association table).
 * 
 * It uses the 'jQuery UI MultiSelect Widget' by E. Hynds
 * <http://www.erichynds.com> to replace the default HTML multi
 * select box with a more user-friendly and filter-enabled dropdown.
 * 
 * Differences to sfWidgetFormDoctrineChoice:
 * - This widget supports Doctrine::HYDRATE_ARRAY hydration.
 * - This widget ignores the 'table_method' option (can be added if needed).
 * - When using array hydration, the 'method' and 'key_method' options
 *     are interpreted as key and value column names (i.e. instead of
 *     ->method(), [method] is used).
 * 
 * Note: A few of these lines are by K. Adryjanek <kamil.adryjanek@gmail.com>
 */
class ullWidgetManyToManyWrite extends ullWidgetFormDoctrineChoice
{
  protected 
    $cachedChoices = null
  ;
  
  protected static 
    $javaScripts = array(
      '/ullCorePlugin/js/jq/jquery-min.js',
      '/ullCorePlugin/js/jq/jquery-ui-min.js',
      '/ullCorePlugin/js/jq/jquery.multiselect-min.js',
      '/ullCorePlugin/js/jq/jquery.multiselect.filter.js',
      '/ullCorePlugin/js/ullWidgetManyToMany.js'
    ),
    $stylesheets = array(
      '/ullCorePlugin/css/jqui/jquery-ui.css' => 'all',
      '/ullCorePlugin/css/jquery.multiselect.css' => 'all',
      '/ullCorePlugin/css/jquery.multiselect.filter.css' => 'all'
    )
  ;  
  
  /**
   * Configures this widget, setting a couple of default options
   * for the multiselect widget.
   */
  protected function configure($options = array(), $attributes = array())
  {
     $this->addOption('config',
	      "{ minWidth : 400,
	         header : ' ',
	         selectedList: 5,
	         noneSelectedText : '" . __('Please select ...', null, 'common') . "' }");
    $this->addOption('filter_config',
      "{ label : '" . __('Search', null, 'common') . ":' }");
    $this->addOption('filter_results');
    $this->addRequiredOption('owner_model');
    $this->addRequiredOption('owner_relation_name');

    parent::configure($options, $attributes);
    
    //we should remove the table_method option if we do not support it, but how?
  }

  /** 
   * Get choices
   */
  public function getChoices()
  {
    if ($this->cachedChoices !== null)
    {
      return $this->cachedChoices;
    }
    
    $method = $this->getOption('method');
    $keyMethod = $this->getOption('key_method');
    
    $choices = array();
    
    if ($this->getOption('add_empty'))
    {
      $choices[''] = ''; 
    }

    $query = null === $this->getOption('query') ? Doctrine_Core::getTable($this->getOption('model'))->createQuery() : $this->getOption('query');
    
    if ($order = $this->getOption('order_by'))
    {
      $query->addOrderBy($order[0] . ' ' . $order[1]);
    }

    //is a filter string specified?
    $filter = $this->getOption('filter_results');
    if ($filter !== null)
    {
      $query->addWhere($method . ' LIKE ?', '%' . $filter . '%');
      //filtering is usually used via AJAX, where the results
      //are processes via JS (e.g. dynamic options for a select tag)
      //since more than lets say 100 entries do not make much sense
      //in such a context, we limit the query
      $query->limit(100);
    }
    
    $results = $query->execute();
  
    //was array hydration used?
    if (is_array($results))
    {
      foreach ($results as $object)
      {
        if (isset($object[$method]))
        {
          $choices[$object[$keyMethod]] = $object[$method];
        }
        // try if it is a translated column
        elseif (isset($object['Translation']))
        {
          $translation = reset($object['Translation']);
          
          $choices[$object[$keyMethod]] = $translation[$method];
        }
        else
        {
          throw new RuntimeException('Field or method not found in result'); 
        }
      }
    }
    //otherwise assume records
    else
    {
      foreach ($results as $object)
      {
        $choices[$object->$keyMethod()] = $object->$method();
      }
    }
    
    natcasesort($choices);
    
    $this->cachedChoices = $choices;
    
    return $choices;
  }
  
  /**
   * Typical widget rendering code, uses the rendering of the
   * ullWidgetFormDoctrineChoice parent class but adds some
   * javascript to call and enable the multiselect widget.
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $id = $this->generateId($name);
    
    $choicesCount = count($this->getChoices());
    $threshold = sfConfig::get('app_ull_widgets_many_to_many_write_ajax_mode_threshold', 1000);   

    //enable AJAX mode if choice count is high
    if ($choicesCount > $threshold)
    {
      $ownerModel = $this->getOption('owner_model');
      $ownerRelationName = $this->getOption('owner_relation_name');
      $ajaxUrl = url_for('ullTableTool/manyToManyFilter');
      $filterConfig = $this->getOption('filter_config');
      
      $return = parent::render($name, $value, $attributes, $errors);
      
      $return .= sprintf(<<<EOF
              <script type="text/javascript">
              	var widget_$id = {
              		selectedOptions: null, selectedValues: null,
              		selectBox: $('#$id'), xhr: null,
              		timeoutId: null, oldFilterValue: '',
              		ownerModel: '$ownerModel',
              		ownerRelationName: '$ownerRelationName',
              		ajaxUrl: '$ajaxUrl',
              		searchLabel: $filterConfig.label
    						};
            		jQuery(document).ready(function()
            		{
             		  manyToMany_setup(widget_$id, %s);
               	});

              </script>
EOF
        ,
        $this->getOption('config')
      );
      
      return $return;
    }
    else
    {
      $return = parent::render($name, $value, $attributes, $errors);
      
      $return .= sprintf(<<<EOF
	            <script type="text/javascript">
	                jQuery(document).ready(function() {
                  $("#%s").multiselect(
	                        %s
	                    ).multiselectfilter(%s); //additionally enables the filter
	                });
	            </script>
EOF
  	    ,
  	    $id,
  	    $this->getOption('config'),
  	    $this->getOption('filter_config')
	    );
	    
	    return $return;
    }
  }
  
  
  /**
   * TODO: refactor to avoid code duplication
   * 
   * @see plugins/ullCorePlugin/lib/form/widget/ullWidgetFormDoctrineChoice#renderOverlayJavascript($id, $name)
   */
  public function renderOverlayJavascript($id, $name)
  {
    $return = javascript_tag('

function ullOverlay_' . $id .'(action) {

  /* @see: http://flowplayer.org/tools/overlay/index.html */

  if (action == "create") {
    var url = "' . url_for('ullTableTool/create?table=' . $this->getOption('model')) . '";
    
  } else if (action == "edit") {
    var optionId = $("#' . $id . '").val();
    
    if (!optionId) {
      alert("' . __('Please select an entry from the list first', null, 'common') . '.");
      return false;
    }
      
    var url = "' . url_for('ullTableTool/edit?table=' . $this->getOption('model')) . 
      '/id/" + optionId;
      
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
              
              // select the new entry
              var selector = "#'. $id . ' > option[value=\'" + window.overlayId + "\']";
              $(selector).attr("selected", true);
              $("#' . $id . '").multiselect("refresh");

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
   * No edit at the moment for inline editing
   */
  public function renderEditControl($id, $name)
  {
  }  

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    return self::$javaScripts;
  }

  /**
   * Same as getJavaScripts(), but static.
   */
  public static function getJavaScriptsStatic()
  {
    return self::$javaScripts;
  }
  
  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * The array keys are files and values are the media names (separated by a ,):
   *
   *   array('/path/to/file.css' => 'all', '/another/file.css' => 'screen,print')
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return self::$stylesheets;
  }
  
  public static function getStylesheetsStatic()
  {
    return self::$stylesheets;
  }
}