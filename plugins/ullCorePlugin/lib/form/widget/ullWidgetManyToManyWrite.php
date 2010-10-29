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
 * This widget supports Doctrine::HYDRATE_ARRAY hydration.
 * This widget ignores the 'table_method' option (can be added if needed).
 * When using array hydration, the 'method' and 'key_method' options
 *   are interpreted as key and value column names (i.e. instead of
 *   ->method(), [method] is used).
 * 
 * Note: A few of these lines are by K. Adryjanek <kamil.adryjanek@gmail.com>
 */
class ullWidgetManyToManyWrite extends sfWidgetFormDoctrineChoice
{
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
      "{  /*placeholder : 'Filter ...',*/
          label : '" . __('Search', null, 'common') . ":' }");

    parent::configure($options, $attributes);
    
    //we should remove the table_method option if we do not support it, but how?
  }

  public function getChoices()
  {
    $choices = array();
    if (false !== $this->getOption('add_empty'))
    {
      $choices[''] = true === $this->getOption('add_empty') ? '' : $this->translate($this->getOption('add_empty'));
    }

    $query = null === $this->getOption('query') ? Doctrine_Core::getTable($this->getOption('model'))->createQuery() : $this->getOption('query');
    if ($order = $this->getOption('order_by'))
    {
      $query->addOrderBy($order[0] . ' ' . $order[1]);
    }
   
    $objects = $query->execute();

    $method = $this->getOption('method');
    $keyMethod = $this->getOption('key_method');
  
    //was array hydration used?
    if (is_array($objects))
    {
      foreach ($objects as $object)
      {
        $choices[$object[$keyMethod]] = $object[$method];
      }
    }
    //otherwise assume records
    else
    {
      foreach ($objects as $object)
      {
        $choices[$object->$keyMethod()] = $object->$method();
      }
    }
    
    return $choices;
  }
  
  /**
   * Typical widget rendering code, uses the rendering of the
   * sfWidgetFormDoctrineChoice parent class but adds some
   * javascript to call and enable the multiselect widget.
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
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
    $this->generateId($name),
    $this->getOption('config'),
    $this->getOption('filter_config')
    );
  }

  protected static $javaScripts = array(
    '/ullCorePlugin/js/jq/jquery-min.js',
    '/ullCorePlugin/js/jq/jquery-ui-min.js',
    '/ullCorePlugin/js/jq/jquery.multiselect-min.js',
    '/ullCorePlugin/js/jq/jquery.multiselect.filter.js'
  );
  
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
    return array(
      '/ullCorePlugin/css/jqui/jquery-ui.css' => 'all',
      '/ullCorePlugin/css/jquery.multiselect.css' => 'all',
      '/ullCorePlugin/css/jquery.multiselect.filter.css' => 'all'
      );
  }
  
  public static function getStylesheetsStatic()
  {
    return array(
      '/ullCorePlugin/css/jqui/jquery-ui.css' => 'all',
      '/ullCorePlugin/css/jquery.multiselect.css' => 'all',
      '/ullCorePlugin/css/jquery.multiselect.filter.css' => 'all'
      );
  }
}