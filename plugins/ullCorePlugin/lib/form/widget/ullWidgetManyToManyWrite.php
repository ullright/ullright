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
class ullWidgetManyToManyWrite extends sfWidgetFormDoctrineChoice
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
    
    natsort($choices);
    
    $this->cachedChoices = $choices;
    
    return $choices;
  }
  
  /**
   * Typical widget rendering code, uses the rendering of the
   * sfWidgetFormDoctrineChoice parent class but adds some
   * javascript to call and enable the multiselect widget.
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $id = $this->generateId($name);
    $choicesCount = count($this->getChoices());
 
    //enable AJAX mode if choice count is high
    if ($choicesCount > 1000)
    {
      $ownerModel = $this->getOption('owner_model');
      $ownerRelationName = $this->getOption('owner_relation_name');
      $ajaxUrl = url_for('ullTableTool/manyToManyFilter');
      $filterConfig = $this->getOption('filter_config');
      
      return parent::render($name, $value, $attributes, $errors).
      sprintf(<<<EOF
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
      $this->getOption('config'));
    }
    else
    {
      return parent::render($name, $value, $attributes, $errors).
	      sprintf(<<<EOF
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
    }
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