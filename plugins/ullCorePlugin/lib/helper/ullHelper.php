<?php

/*
 * ull helpers
 */

sfContext::getInstance()->getConfiguration()->loadHelpers(array(
  'Asset', 
  'Date', 
  'Form', 
  'JavascriptBase', 
  'Tag', 
  'Url'
));

/**
 * Parses a numeric string representing a human date,
 * e.g. 18051980 => 18.05.1980. This is culture sensitive and
 * supports 'de', every other culture results in English
 * date parsing.
 * 
 * The input string has to be exactly 6 or 8 characters long.
 *
 * Important note: Uses strptime, which is not available on
 * Windows systems! PHP 5.3.0 would provide alternatives.
 *
 * TODO: Update to PHP 5.3.0 when applicable
 *
 * @param string $numericString string to parse, must be numeric
 * @return array return value of strptime, false in case of error
 */
function ull_parse_date_without_separators($numericString)
{
  $length = strlen($numericString);
  if ($length != 6 && $length != 8)
  {
    return false;  
  }
  
  $culture = sfContext::getInstance()->getUser()->getCulture();
  $culture_parts = explode('_', $culture);
  $language = $culture_parts[0];

  switch ($language)
  {
    case 'de':
      $format = '%d%m%' . (($length == 8) ? 'Y' : 'y');
      break;

    default:
      //English pattern
      $format = '%' . (($length == 8) ? 'Y' : 'y') . '%m%d';
  }

  return strptime($numericString, $format);
}

function ull_date_pattern($zeroPadding = true, $php_format = false, $showWeekday = false)
{
  $culture = sfContext::getInstance()->getUser()->getCulture();
  $culture_parts = explode('_', $culture);
  $language = $culture_parts[0];
  
  // For an explanation of this cryptic patterns 
  // @see sfDateFormat::getPattern(), sfDateFormat::$tokens
  
  if ($php_format)
  {
    $dayPattern = $zeroPadding ? 'd' : 'j';
    $monthPattern = $zeroPadding ? 'm' : 'n';
    $yearPattern = 'Y';
  }
  else
  {
    $dayPattern = $zeroPadding ? 'dd' : 'd';
    $monthPattern = $zeroPadding ? 'MM' : 'M';
    $yearPattern = 'yyyy';
  }
  
  $resultPattern = ($showWeekday) ? 'EEEE, ' : '';

  switch ($language)
  {
    case 'de':
      $resultPattern .= $dayPattern . '.' . $monthPattern . '.' . $yearPattern;
      break;
    
    default:
      $resultPattern .= $monthPattern . '/' . $dayPattern . '/' . $yearPattern;
  }
  
  return $resultPattern;
}

/**
 * Extends symfony's DateHelper with a specific date format
 * This is culture sensitive.
 * 
 * @param string $date          iso date like "2007-12-04 13:45:10"
 * @param boolean $zeroPadding  show leading zeros    
 * @param boolean $showWeekday  show the current weekday name
 * 
 * @return string
 */
function ull_format_date($date = null, $zeroPadding = true, $showWeekday = false) 
{
  if (!$date)
  {
    $date = time();
  }
    
  $pattern = ull_date_pattern($zeroPadding, false, $showWeekday);
  
  return format_datetime($date, $pattern);
}


/**
 * Extends symfony's DateTimeHelper with a specific date/time format
 * This is culture sensitive.
 *
 * @param string date         iso date like "2007-12-04 13:45:10"
 * @return string date        formated date like "4.12.2007 13:45h" for "de"
 */
function ull_format_datetime($date = null, $zeroPadding = true, $showSeconds = true) 
{
  if ($date == null)
  {
    $date = time();
  }
    
  $timePattern = ($showSeconds) ? 'HH:mm:ss' : 'HH:mm';
  return format_datetime($date, ull_date_pattern($zeroPadding) . ' ' . $timePattern);
}


/**
 * Extends symfony's DateTimeHelper with a specific time format
 * This is culture sensitive.
 *
 * @param string date         iso time like "13:45:10"
 * @return string date        formated time like "13:45" for "de"
 */
function ull_format_time($time, $showSeconds = false) 
{
  $fakeDate = date('Y-m-d');
  
  $date = $fakeDate . ' ' . $time;
  
  $timePattern = ($showSeconds) ? 'HH:mm:ss' : 'HH:mm';
  
  return format_datetime($date, $timePattern);
}


/**
 * Build path for default action_icons in the correct color for the current module
 * 
 * @param $type
 * @param $width
 * @param $height
 * @param $plugin
 */
function ull_image_path($type, $width = null, $height = null, $plugin = null)
{
  $width = ($width === null) ? 16 : $width;
  $height = ($height === null) ? 16 : $height;
  $plugin = ($plugin === null) ? sfContext::getInstance()->getRequest()->getParameter('module') : $plugin;
  
  $path =  '/' . $plugin . 'Theme' . sfConfig::get('app_theme_package', 'NG') .
           'Plugin/images/action_icons/' . $type . '_' . $width . 'x' . $height;
    
  $actual_file_path = sfConfig::get('sf_root_dir') . '/web' . $path . '.png';

  if (file_exists($actual_file_path))
  {
    return $path;
  }
  // Fallback to default icon
  else
  {
    return '/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') .
           'Plugin/images/action_icons/' . $type . '_' . $width . 'x' . $height;
  }
}

/**
 * Image tag helper for default action_icons in the correct color for the current module
 * 
 * @param $type
 * @param $options
 * @param $width
 * @param $height
 * @param $plugin
 */
function ull_image_tag($type, $options = array(), $width = null, $height = null, $plugin = null)
{
  $mergedOptions = array_merge(
    array(
      'alt' => __(ucfirst($type), null, 'common'),
      'title' => __(ucfirst($type), null, 'common'),
      'class' => 'ull_action_icon',
    ),
    $options
  );
  
	return image_tag(
    ull_image_path($type, $width, $height, $plugin),
    $mergedOptions 
  );
}


/**
 * @param unknown_type $link
 * @param unknown_type $type
 * @param unknown_type $linkOptions
 * @param unknown_type $imageOptions
 * @param unknown_type $plugin
 * 
 * @deprecated  Is this still in use? Current function is ull_image_tag()
 */
function ull_icon($link, $type, $linkOptions = array(), $imageOptions = array(), $plugin = null) 
{
  return link_to(ull_image_tag($type, $imageOptions, null, null, $plugin), $link, $linkOptions);
}

/**
 * Wrapper for link_to_function(image_tag(...) for standard icons in 
 * plugins/myTheme/web/images/action_icons...
 * 
 * automagically sets the alt and title attribute
 *
 * @param function string     javascript function
 * @param icon string         name of the icon without suffix (e.g. edit for edit.png)
 * @param alt string          optional, 'alt' and 'title' caption, default = icons filename
 * @param link_option string  optional, link_to() option (3rd argument)
 * @return string             html
 * 
 * @deprecated  Is this still in use?
 */

function ull_icon_to_function($function, $icon, $alt = null, $link_option = null) 
{
  if (!$alt) {
    $alt = $icon;
  }

  $alt = __($alt, null, 'common');
  
  return link_to_function(
    image_tag(
      '/' 
        . sfConfig::get('app_theme', 'ullThemeDefault')
        . '/images/action_icons/' . $icon . '.png',
      'alt=' . $alt . ' title=' . $alt
    ),  
    $function,
    $link_option
  ); 
}


/**
 * Render ajax indicator
 */
function ull_image_tag_indicator($options = null)
{
  $path =  '/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') .
           'Plugin/images/indicator.gif';

  return image_tag($path, $options);
}

/**
 * Enhancement of ull_icon() helper 
 * Get current request params, and allows adding or overriding of specific params
 *
 * @param merge_array array   array with params to add, remove or overwrite (eg. 'page' => 2)
 * @param icon string         name of the icon without suffix (e.g. edit for edit.png)
 * @param alt string          optional, 'alt' and 'title' caption, default = icons filename
 * @param link_option string  optional, link_to() option (3rd argument)
 * @return string             html
 * 
 * TODO: refactor into ull_icon() function and possibly ull_icon to use _ull_link_to() 
 */

function ull_reqpass_icon($merge_array = array(), $icon, $alt = null, $link_option = null) {

  $params = _ull_reqpass_initialize($merge_array);  

  $link = _ull_reqpass_build_url($params);
  
  return ull_icon($link, $icon, $alt, $link_option);
  
}


/**
 * Like image_tag(), but supports inline resizing of the source image
 *   by giving the options width and/or height as interger (pixel)
 *   
 * Supports caching of the resized image
 *   
 * @param string $source
 * @param array $options
 */
function ull_image_tag_resize($source, $options)
{
  // Fallback to original image_tag
  if (!key_exists('width', $options) && !key_exists('height', $options))
  {
    return image_tag($source, $options);
  }
  
  $webPath = image_path($source);  // image_path() is a symfony helper!
  $path = ullCoreTools::webToAbsolutePath($webPath);
  
  $newPath = ull_image_resize_build_filename($path, $options);
  
  if (!file_exists($newPath))
  {
    ull_image_resize($path, $options);  
  }
  
  $newWebPath = ullCoreTools::absoluteToWebPath($newPath);
  
  unset($options['width']);
  unset($options['height']);
  
  return image_tag($newWebPath, $options);
}

/**
 * Resize an image with cache function
 * 
 * @param string $path
 * @param array $options
 * @return string
 */
function ull_image_resize($path, $options)
{
  $image = new sfImage($path, 'image/jpg');
  
  $width = (isset($options['width'])) ? $options['width'] : 0;
  $height = (isset($options['height'])) ? $options['height'] : 0;
  
  // Preserve aspect ration when giving both width and height
  if ($width && $height)
  {
    if (($image->getWidth() / $width) > ($image->getHeight() / $height))
    {
      $height = 0;
    }
    else
    {
      $width = 0;
    }      
  }
  
  //Not jpg necessarly. Just a mandatory sfImage option
  $image->resize($width, $height);
  
  $newPath = ull_image_resize_build_filename($path, $options);
  
  $dir = dirname($newPath);
  
  if (!file_exists($dir))
  {
    mkdir($dir, 0777);
  }
  
  $image->saveAs($newPath);
  
  return $newPath;
}


/**
 * Build the filename for ull_image_resize()
 * 
 * @param string $path    Absolute path
 * @param array $options  Containing options "width" and or "height"
 * 
 * @return string         Absolute path
 */
function ull_image_resize_build_filename($path, $options)
{
  $width = (isset($options['width'])) ? $options['width'] : 0;
  $height = (isset($options['height'])) ? $options['height'] : 0;
  
  $suffixParts = array();
  
  if ($width)
  {
    $suffixParts[] = 'width_' . $width;
  }
  
  if ($height)
  {
    $suffixParts[] = 'height_' . $height;
  }  
  
  $suffix = '_' . implode('_', $suffixParts);

  $suffixPath = ull_path_add_filename_suffix($path, $suffix);
  $subDirPath = ull_path_add_sub_directory($suffixPath, 'resized_images');

  return $subDirPath;
} 


/**
 * Helper to add a suffix to a filename
 * 
 * Example: 
 *   $path   = '/var/www/xyz/web/images/logo.png'
 *   $suffix = '_resized'
 *   
 *   result  = '/var/www/xyz/web/images/logo_resized.png'
 * 
 * @param string $path
 * @param string $suffix
 */
function ull_path_add_filename_suffix($path, $suffix)
{
  $parts = pathinfo($path);

  $return = 
    $parts['dirname'] .
    DIRECTORY_SEPARATOR .
    $parts['filename'] .
    $suffix .
    '.' .
    $parts['extension']
  ;

  return $return;
}

/**
 * Helper to add subdirectory(ies) to a path
 * 
 * Example:
 *   $path = '/var/www/xyz/web/images/logo.png'
 *   $directory = 'cache/foo'
 *   
 *   result = '/var/www/xyz/web/images/cache/foo/logo.png'
 * 
 * @param unknown_type $path
 * @param unknown_type $directory
 */
function ull_path_add_sub_directory($path, $directory)
{
  $parts = pathinfo($path);

  $return = 
    $parts['dirname'] .
    DIRECTORY_SEPARATOR .
    $directory .
    DIRECTORY_SEPARATOR .
    $parts['filename'] .
    '.' .
    $parts['extension']
  ;

  return $return;  
  
}


/**
 * Enhancement of symfony link_to() helper
 *  
 * - Supports ull_reqpass (Request passing):
 *     Get current request params, and allows adding or overriding of specific params
 * - It supports ull_js_observer_confirm (detect form changes)
 *     -> Make sure you that you load the ull_js_observer($form_id) helper at the end of your template
 * 
 * options:
 *   ull_js_observer_confirm  (boolean) 'true' for default msg or (string) a custom message
 *    
 *
 * @param name string         link name to display
 * @param url mixed           can be a internal symfony url, or an array with params to add, remove or overwrite (eg. 'action' => 'list')
 * @param options mixed       string or array of options 
 * @return string             html link
 */

function ull_link_to($name = 'link', $url = array(), $options = array())
{

  $options = _convert_options($options);

  if (isset($options['link_new_window'])) 
  {
    unset($options['link_new_window']);
    $options['class']   = 'link_new_window';
    $options['target']  = '_blank';
    $options['title']   = __('Link opens in a new window', null, 'common');
  }

  if (isset($options['link_external'])) 
  {
    unset($options['link_external']);
    $options['class'] = 'link_external';
    $options['target'] = '_blank';
    $options['title']   = __('Link opens in a new window', null, 'common');
  }

  return _ull_to($name, $url, $options, 'link');

}

function ull_navigation_link($img_source, $internal_uri, $link_text, $options = array())
{
	$options = _convert_options($options);
  $options['alt'] = isset($options['alt']) ? $options['alt'] : $link_text;
  
//  $link = '<a href="' . $internal_uri . '">' .
//    '<img src="' . $img_source . '.png" alt="' . $img_alt . '" />' . '<br />' .
//    $link_text . '</a>';
//  
  
	$link = ull_link_to(image_tag($img_source, $options), $internal_uri, 'ull_js_observer_confirm=true') .
	        '<br />' . ull_link_to($link_text, $internal_uri, 'ull_js_observer_confirm=true');
  
  return $link;
}

function ull_tc_task_link($img_source, $internal_uri, $link_text, $options = array())
{
	$options = _convert_options($options);
	$options['size'] = '24x24';
  $options['alt'] = isset($options['alt']) ? $options['alt'] : $link_text;
  $options['title'] = isset($options['title']) ? $options['title'] : $link_text;
  $link_options['title'] = $options['title'];
    
	$link = '<div class="float_left">' .
	        ull_link_to(image_tag($img_source, $options), $internal_uri) .
	        '</div>' .
	        '<div>' . 
          ull_link_to($link_text, $internal_uri, $link_options) . '</div>' .
          '<div class="clear_left"></div>';
	
	return $link;
}


/**
 * Enhancement of symfony button_to() helper
 *  
 * - Supports ull_reqpass (Request passing):
 *     Get current request params, and allows adding or overriding of specific params
 * - It supports ull_js_observer_confirm (detect form changes)
 *     -> Make sure you that you load the ull_js_observer($form_id) helper at the end of your template
 * 
 * options:
 *   ull_js_observer_confirm  (boolean) 'true' for default msg or (string) a custom message
 *    
 *
 * @param string name         link name to display
 * @param mixed url           can be a internal symfony url, or an array with params to add, remove or overwrite (eg. 'action' => 'list')
 * @param array options       array of options 
 * 
 * @return string html
 */

function ull_button_to($name = 'link', $url = array(), $options = array()) 
{
  
  return _ull_to($name, $url, $options, 'button');
}


/**
 * generic enhancement for symfony [link|button]_to() helper
 *  
 * - Supports ull_reqpass (Request passing):
 *     Get current request params, and allows adding or overriding of specific params
 * - It supports ull_js_observer_confirm (detect form changes)
 *     -> Make sure you that you load the ull_js_observer($form_id) helper at the end of your template
 * 
 * options:
 *   ull_js_observer_confirm  (boolean) 'true' for default msg or (string) a custom message
 *    
 *
 * @param string name         link name to display
 * @param mixed url           can be a internal symfony url, or an array with params to add, remove or overwrite (eg. 'action' => 'list')
 * @param mixed options       array or string of options
 * @param string type         'link' or 'button'
 *  
 * @return string             html
 */

function _ull_to($name = 'link', $url = array(), $options = array(), $type = 'link') 
{
  if (is_array($url)) 
  {
    $params = _ull_reqpass_initialize($url);  
    $url = _ull_reqpass_build_url($params);
  }

  $options = _convert_options($options);

  // diable ull_js_observer if user has no javascript
  if (!sfContext::getInstance()->getUser()->getAttribute('has_javascript')) 
  {
  	unset($options['ull_js_observer_confirm']);
  }

  $action = sfContext::getInstance()->getActionName();
  // disable ull_js_observer if action is not edit or create
  // this is an ugly workaround to at least do graceful degradation for all other actions
  if (!in_array($action, array('create', 'edit'))) 
  {
    unset($options['ull_js_observer_confirm']);
  }

  // disable ull_js_observer for target='_blank' (makes no sense)
  if (isset($options['target']) && $options['target'] == '_blank') 
  {
    unset($options['ull_js_observer_confirm']);    
  }
 
  if (isset($options['ull_js_observer_confirm'])) 
  {
    // use default msg if no custom msg
    if (is_bool($options['ull_js_observer_confirm'])) 
    {
      $msg = __('You will lose unsaved changes! Are you sure?', null, 'common');
    } 
    else 
    {
      $msg = $options['ull_js_observer_confirm'];
    }

    $call = 'return document.location.href="' . url_for($url) . '";';

    // check for the existence of the ull_js_observer hidden input tag and 
    //   do the check only if the tag exists (= check if we have a page with a form)
    $js_function =
        'if (document.getElementById("ull_js_observer_initial_state") != null'
      . '   && ull_js_observer_detect_change()) { '
      . '   if (confirm("' . $msg . '")) { '
      . '     ' . $call . 'return false;'
      . '   } else {'
      . '     return false;'
      . '   }'
      . '} else {'
      . '   ' . $call . 'return false;'
      . '}'
    ;

    unset($options['ull_js_observer_confirm']);
    
    return call_user_func($type . '_to_function', $name, $js_function, $options);

  } 
  else 
  {
    // graceful degradation for button_to in case of no javascript availabel
    if (!sfContext::getInstance()->getUser()->getAttribute('has_javascript') &&
        $type == 'button')
    {
      return link_to($name, $url, $options);
    }
    
    return call_user_func($type . '_to', $name, $url, $options);
  }
  
}


/**
 * Enhancement of symfony link_to_function() helper
 *  
 * - It supports ull_js_observer_confirm (detect form changes)
 *     -> Make sure you that you load the ull_js_observer($form_id) helper at the end of your template
 * 
 * options:
 *   ull_js_observer_confirm  (boolean) 'true' for default msg or (string) a custom message
 *    
 *
 * @param name string         link name to display
 * @param function string     javascript function
 * @param options mixed       string or array of options 
 * @return string             html
 */

function ull_link_to_function($name, $function, $options = array()) {
  
  return _ull_to_function($name, $function, $options, 'link');
  
}


/**
 * Enhancement of symfony button_to_function() helper
 *  
 * - It supports ull_js_observer_confirm (detect form changes)
 *     -> Make sure you that you load the ull_js_observer($form_id) helper at the end of your template
 * 
 * options:
 *   ull_js_observer_confirm  (boolean) 'true' for default msg or (string) a custom message
 *    
 *
 * @param name string         name to display
 * @param function string     javascript function
 * @param options mixed       string or array of options 
 * @return string             html
 */

function ull_button_to_function($name, $function, $options = array()) {
  
  return _ull_to_function($name, $function, $options, 'button');
  
}


/**
 * generic enhancement for symfony [link|button]_to_function() helper
 *  
 * - It supports ull_js_observer_confirm (detect form changes)
 *     -> Make sure you that you load the ull_js_observer($form_id) helper at the end of your template
 * 
 * options:
 *   ull_js_observer_confirm  (boolean) 'true' for default msg or (string) a custom message
 *    
 *
 * @param name string         name to display
 * @param function string     javascript function
 * @param options mixed       string or array of options
 * @param type string         'link' or 'button' 
 * @return string             html
 */

function _ull_to_function($name = 'link', $function, $options = array(), $type = 'link') {

  $html_options = _convert_options($options);
  if (isset($html_options['ull_js_observer_confirm'])) {
    
//    ullCoreTools::printR($html_options['ull_js_observer_confirm']);
//    sfContext::getInstance()->getLogger()->info('xxx: '.gettype($html_options['ull_js_observer_confirm']));

    // use default msg if no custom msg
    if (is_bool($html_options['ull_js_observer_confirm'])) {
      $msg = __('You will lose unsaved changes! Are you sure?', null, 'common');
    } else {
      $msg = $html_options['ull_js_observer_confirm'];
    }

    // check for the existence of the ull_js_observer hidden input tag and 
    //   do the check only if the tag exists (= check if we have a page with a form)
    $js_function =
        'if (document.getElementById("ull_js_observer_initial_state") != null'
      . '   && ull_js_observer_detect_change()) { '
      . '   if (confirm("' . $msg . '")) { '
      . '     '.$function
      . '   } else {'
      . '     return false;'
      . '   }'
      . '} else {'
      . '   '.$function
      . '}'
    ;

    unset($html_options['ull_js_observer_confirm']);

    return call_user_func($type . '_to_function', $name, $js_function, $html_options);
    
  } else {
    return call_user_func($type . '_to_function', $name, $function, $options);
  }
  
}



/**
 * enhancement of submit_tag() helper 
 * @see submit_tag()
 * 
 * adds an option to display a link instead of a button (with javascript)
 * supports graceful degradation without javascript (displays a submit button)
 * TODO: support real unobstrusive javascript using jquery without ->getUser()->getAttribute('has_javascript')
 *  
 * options:
 *   boolean display_as_link  displays a link instead of a button if javascript is available
 *   string name              the tag's name attribute (required for option "display_as_link)
 *   string form_id           the id of the form to submit (required for option "display_as_link)
 *    
 * Crazy stuff: using this function with the option display_as_link links failed in 
 *   Internet Exporer 8 with error message "Object doesn’t support this property or method"
 *   because there is an html id and a javascript function of the same name.
 *   Solution: renamed javascript function
 *
 * @param string $value       field value (title of submit button)
 * @param array $options      array of options
 * 
 * @return string XHTML compliant <input> tag with type="submit"
 */

function ull_submit_tag($value = 'Save changes', $options = array()) 
  {
	
	if (isset($options['display_as_link']) && (!isset($options['name']) || !isset($options['form_id']))) 
	{
		throw new InvalidArgumentException('option "display_as_link" requires options "name" and "form_id"');
	}

  //js not enabled or not a link
	if (isset($options['display_as_link']) &&
	   sfContext::getInstance()->getUser()->getAttribute('has_javascript'))
	{
    $js_function_name = str_replace(array('|', '='), array('_', '_'),
      $options['name']) . '_cheersIE8()'; 
	  
    $return = input_hidden_tag($options['name'], null, array('id' => str_replace(array('|', '='), '_', $options['name']))) . "\n";
    //note: do not call submit() immediately, but use jQuery
    //there might be submit() handlers listening
    $return .= javascript_tag('function ' . $js_function_name . ' 
{
  document.getElementById("' . str_replace(array('|', '='), '_', $options['name']) . '").value = 1;
  $("#' . $options['form_id'] . '").submit();
}') . "\n"; 
    
    unset($options['name']);
    unset($options['form_id']);
    unset($options['display_as_link']);
    $return .= ull_link_to_function($value, $js_function_name, $options) . "\n";

    return $return;
	}
	else
	{
    unset($options['form_id']);
    unset($options['display_as_link']);	  
	  
		return submit_tag($value, $options);
	}
}

/**
 * Counterpart to ull_submit_tag
 * 
 * returns the submit 'name' attribute as string if it was prefixed with 'submit_'
 * 
 * Example: <input type="submit" name="submit_save_only" value="Save only" />
 * => returns "save_only"
 * 
 * uses the default symfony request params if no array with params is given
 *
 * @param array $params
 * @return string the submit mode
 */
function ull_submit_tag_parse($params = null)
{
  if ($params === null)
  {
    $params = sfContext::getInstance()->getRequest()->getParameterHolder()->getAll();
  }
  
  $return = '';
  
  foreach ($params as $key => $value)
  {
    if (strstr($key, 'submit_') and $value)
    {
      $return = substr($key, 7);
    }
  }
  
  return $return;
}

/**
 * Enhancement of form_tag() helper 
 * supports giving a merge_array instead of a symfony url 
 *
 * @param mixed url             symfony url or array with params to add, remove or overwrite (eg. 'page' => 2)
 * @param array form_options    array containing html options for the <form> tag
 * @return string               html form tag and hidden field
 */

function ull_form_tag($url = array(), $form_options = array()) 
{
  if (is_array($url)) 
  {
    $params = _ull_reqpass_initialize($url);  
    $url = _ull_reqpass_build_url($params);
  }
  
  return form_tag($url, $form_options);
}


/**
 * some tag helper 
 * Taken from symfony/lib/helper/TagHelper.php, now deprecated in symfony 1.1
 */
function ull_tag_options($options = array())
{
  $options = _parse_attributes($options);

  $html = '';
  foreach ($options as $key => $value)
  {
    $html .= ' '.$key.'="'.escape_once($value).'"';
  }

  return $html;
}


/**
 * some tag helper 
* Taken from symfony/lib/helper/TagHelper.php, now deprecated in symfony 1.1
 */
function ull_parse_attributes($string)
{
  return is_array($string) ? $string : sfToolkit::stringToArray($string);
}





/** 
 * Gets current request params and allows adding or overriding of specific params
 *
 * @param array merge_array   array with params to add, remove or overwrite (e.g. 'page' => 2)
 * @return array              array containing processed params
 */
function _ull_reqpass_initialize($merge_array = array(), $rawurlencode = true) 
{
  $params = sfContext::getInstance()->getRequest()->getParameterHolder()->getAll();
  
  // overwrite / add params
  $params = sfToolkit::arrayDeepMerge($params, $merge_array);
  
  // clean params
  $params = _ull_reqpass_clean_array($params);
  
  return $params;
}


/**
 * deeply cleans an array of empty values
 *
 * @param array $array            multi dimensional array to be cleaned
 * @param boolean $rawurlencode   rawurlencode the value (true per default)
 * @return array
 */
function _ull_reqpass_clean_array($array, $rawurlencode = true)
{
  $blacklist = array(
    'ull_req_pass', // remove the array with the original request params
    'sf_culture',   // TODO: where does sf_culture come from? appeared in functional testing...
    'commit',       // we don't want the submit buttons...
    'x',            // image submit buttons coordinates
    'y',            // image submit buttons coordinates
    'commit_x',     // image submit buttons coordinates
    'commit_y',     // image submit buttons coordinates
  );

  // convert [] options to array format
  foreach ($array as $key => $value) 
  {
    if (preg_match('/([^\[]+)[\[]([^\]]+)[\]]/', $key, $matches)) 
    {
      unset($array[$key]);
      $key = $matches[1];
      $value = array($matches[2] => $value);
      
      if (isset($array[$key]))
      {
        $array[$key] = array_merge($array[$key], $value);   
      }
      else
      {
        $array[$key] = $value;
      }
    }  
  }
  
  
  foreach ($array as $key => $value) 
  {
    // recurse
    if (is_array($value))
    {
      $value = _ull_reqpass_clean_array($value); 
    }    
    
    // remove empty or blacklisted elements
    if (empty($value) or in_array($key, $blacklist))
    {
      unset($array[$key]);
    }
    else
    {
      // TODO: what's the usecase for rawurlencode?
      if ($rawurlencode and !is_array($value)) 
      {
        $array[$key] = ullCoreTools::urlDotEncode($value);        
      }
      else
      {      
      $array[$key] = $value;
      }
    }
  }
  
  return $array; 
}


/** 
 * build a valid symfony url using the supplied params
 *
 * @param params array    array containing request params
 * @return string         symfony url
 */

function _ull_reqpass_build_url($params) {
  
  // module
  $url = $params['module'];
  unset($params['module']);

  // action
  $url .= '/' . $params['action'];
  unset($params['action']);

  // check if any params left...
  if ($params) 
  { 
    $addition = '?';
    foreach($params as $paramName => $paramValue) 
    {
      // move array (first layer at the moment) to "[]" syntax 
      if (is_array($paramValue))
      {
        foreach ($paramValue as $key => $value)
        {
          $key = $paramName . '[' . $key . ']';
          $url .= $addition . $key . '=' . $value;
          $addition = '&';
        }
      }
      else 
      {
        $url .= $addition . $paramName . '=' . $paramValue;
        $addition = '&';
      }
    }
  }

  return $url;
}

/**
 * Enhances url_for with ull_reqpass functionality
 *
 * @param mixed $url
 * @return string
 */
function ull_url_for($url = array())
{
  if (is_array($url)) 
  {
    $params = _ull_reqpass_initialize($url);  
    $url = _ull_reqpass_build_url($params);
  }
  
  $url = url_for($url);
  
  // "un-escape" "reqpass" array syntax with square braces
  // example: "filter[search]"
  // TODO: this should be patched in url_for...
  $url = str_replace('%5B', '[', $url);
  $url = str_replace('%5D', ']', $url);

  return $url;
}


/** 
 * build a valid symfony base url (module/action) using the supplied params
 * used for <form> 'action' param 
 * 
 * @param params array    array containing request params
 * @return string         symfony url
 */

function _ull_reqpass_build_base_url($params) {
  
  // module
  $url = $params['module'];
  
  // action
  $url .= '/' . $params['action'];
  
  return $url; 
}

/*
 * The following 2 functions are from http://www.linuxscope.net/articles/mailAttachmentsPHP.html
 */

function get_mime_type(&$structure) {
  $primary_mime_type = array("TEXT", "MULTIPART","MESSAGE", "APPLICATION", "AUDIO","IMAGE", "VIDEO", "OTHER");
 
  if($structure->subtype) {
    return $primary_mime_type[(int) $structure->type] . '/' .$structure->subtype;
  }
    
  return "TEXT/PLAIN";
}

   
function get_part($stream, $msg_number, $mime_type, $structure = false, $part_number = false) {
   
  if(!$structure) {
    $structure = imap_fetchstructure($stream, $msg_number);
  }
    
  if($structure) {
    if($mime_type == get_mime_type($structure)) {
      if(!$part_number) {
        $part_number = "1";
      }
      $text = imap_fetchbody($stream, $msg_number, $part_number);
      if($structure->encoding == 3) {
        return imap_base64($text);
      } elseif($structure->encoding == 4) {
        return imap_qprint($text);
      } else {
        return $text;
      }
    }
   
    if($structure->type == 1) /* multipart */ {
      while(list($index, $sub_structure) = each($structure->parts)) {
        if($part_number) {
          $prefix = $part_number . '.';
        } else {
          $prefix = '';
        }
        $data = get_part($stream, $msg_number, $mime_type, $sub_structure,$prefix .    ($index + 1));
        if($data) {
          return $data;
        }
      } // END OF WHILE
    } // END OF MULTIPART
  } // END OF STRUTURE
  return false;
  
} // END OF FUNCTION


function ull_js_observer($form_id) {

  // js_observer form to store initial form values
  $a = sfContext::getInstance()->getActionName();
  $m = sfContext::getInstance()->getModuleName();
  $html = form_tag($a . '/' . $m, 'id=ull_js_observer');
  $html .= input_hidden_tag('ull_js_observer_initial_state');
  $html .= '</form>';

  $html .= javascript_tag('
    var elements = document.getElementById("' . $form_id . '");
    var initial_state = new Array();
    
    for (i = 0; i < elements.length; i++) {
      if (elements[i] && elements[i].id != "") {
        if (elements[i].type == "checkbox")  
          initial_state[elements[i].id] = elements[i].checked;
        else
          initial_state[elements[i].id] = elements[i].value;
      }
    }

    function ull_js_observer_detect_change() {

      var elements = document.getElementById("' . $form_id . '");

      for (i=0; i < elements.length; i++) {
        var newElement = elements[i];
              
        if (newElement.id.indexOf("___Config") > -1) {
          var instance_name = newElement.id.replace(/___Config/, "");
          var oEditor = FCKeditorAPI.GetInstance(instance_name);
          if (oEditor.IsDirty()) {
            //alert("FCKeditor modified");
            return true;
          }
        }

        var oldElement = initial_state[newElement.id];
       
        if (typeof(oldElement) == \'undefined\') {
          //skip this element, since it seems to be new
          continue;  
        }

        if (newElement.className == \'hasDatepicker\') {
          if (window[newElement.id + "_initial_date"] != newElement.value) {
            //alert("date different! Old value: " + window[newElement.id + "_initial_date"] + " New value: " + newElement.value);  
            return true;
          }
          else {
            continue;
          }
        }

        if (newElement.type == "checkbox") {
          if (newElement.checked != oldElement) {
            //alert("Checkbox " + newElement.id + " changed! Old checked: " + oldElement + " New checked: " + newElement.checked);
            return true;
          }
          else {
            continue;
          }
        }
        
        if (newElement.value != oldElement) {
           //alert("Field " + newElement.id + " changed! Old value: " + oldElement + " New value: " + newElement.value);
           return true;
        }
      }
    }
    
    function ull_js_observer_update_initial_state() {
      var elements = document.getElementById("' . $form_id . '");
      initial_state = new Array();
  
      for (i = 0; i < elements.length; i++) {
        if (elements[i] && elements[i].id != "") {
          if (elements[i].type == "checkbox")  
            initial_state[elements[i].id] = elements[i].checked;
          else
            initial_state[elements[i].id] = elements[i].value;
          if (elements[i].id.indexOf("___Config") > -1) {
            var instance_name = elements[i].id.replace(/___Config/, "");
            var oEditor = FCKeditorAPI.GetInstance(instance_name);
            oEditor.ResetIsDirty();
          }
        }
      }
    }
  ');
  
/*
 * Comment for finding FCKEditor-Fields
 *
 * For each rich-textfield (with FCKEditor), there is created an additional field.
 * In our example, the field is 'body', the additional field is called 'body___Config'.
 * In a loop, i'm searching for fields with the suffix ___Config, so i can get the name
 * of the FCKEditor-field out of the prefix.
 *
 * e            = HTML-Element
 * e.id         = ID of the HTML-Element
 * e.id.indexOf = is looking for ___Config in the string. If found, return value is greater than -1
*/

  return $html;
}

/**
 * Helper to load the "hide advanced form fields" js
 * 
 */
function hide_advanced_form_fields()
{
  // Disable this functionalty for edit pages loaded via ajax (overlay)
  // because it has sideeffects on the originating page
  // TODO: implement proper solution which works also for overlays
  if (!sfContext::getInstance()->getRequest()->isXmlHttpRequest())
  {
    use_javascript('/ullCorePlugin/js/formHideAdvancedOptions.js');
    
    return javascript_tag('
  formHideAdvancedOptions("' . __('Show more fields', null, 'common') . '");
  ');
  }
}

function _convert_array_to_string($arr) {
	$str = '';
	foreach ($arr as $key => $value) {
		$str .= ' '.$key.'="'.$value.'" ';
	}
	return $str;
}


/**
 * Format SQL query
 * 
 * @deprecated
 * @param unknown_type $query
 */
function printQuery($query) 
{
  ullCoreTools::printQuery($query);
}


/**
 * Orders the top level of an associative array by a given array
 * Keys which are not defined by $order remain unchanged at the end of return array
 * See ullHelperTest.php for examples
 *  
 * @param $array array to order
 * @param $order array defining the expected order
 * @return array
 * @deprecated use ullCoreTools::orderArrayByArray
 */
function ull_order_array_by_array(array $array, array $order)
{
  return ullCoreTools::orderArrayByArray($array, $order);
}


/**
 * Returns a link to Google Maps with the search string
 * added as query parameter
 * 
 * @param $link text/image part of the link
 * @param $searchString query url for Google Maps - will be urlencoded()
 * @param $options additional <a> tag options
 * @return unknown_type the link
 */
function link_to_google_maps($link, $searchString, $options)
{
  return link_to($link, 'http://maps.google.com/maps?q=' . urlencode($searchString), $options);
}


/**
 * Returns a link to a QR code generated by Google
 * 
 * @param unknown_type $content should not be urlencoded
 * @param unknown_type $size in format wxh, default is 120x120
 * @return unknown_type the url to Google
 */
function ull_url_for_google_qrcode($content, $size = '120x120')
{
//  return 'http://chart.apis.google.com/chart?cht=qr&chs=' . $size . '&chl=' . urlencode($content) . '&chld=Q|0';
  return 'http://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($content) . '&size=' . $size . '&margin=0&ecc=L';
}


/**
 * Returns a link to a barcode generated by barcodesinc
 * 
 * @param unknown_type $content 
 * @param unknown_type $width
 * @param unknown_type $height
 * @param $font Text size. 1 = x-small, 2= small, 100 = medium
 * @param $style
 */
function ull_url_for_barcode($content, $width = 200, $height = 70, $font=100, $style = 198)
{
  return 'http://www.barcodesinc.com/generator/image.php?code=' . $content . '&style=' . $style . '&type=C39&width=' . $width . '&height=' . $height . '&xres=1&font=' . $font;
}


/**
 * Returns true if the Pager has more pages on the right end as displayed
 * 
 * @param $pager
 * @param $sliding_range
 * @return unknown_type
 */
function pager_has_more_right_pages($pager, $sliding_range)
{
  $right_pages = floor(($sliding_range - 1) / 2);
  if (
    (($pager->getPage() + $right_pages) < $pager->getLastPage()) 
    && 
    ($pager->getLastPage() > $sliding_range)
   )
  {
    return true;
  }
}


/**
 * Returns true if the Pager has more pages on the left end as displayed
 * 
 * @param $pager
 * @param $sliding_range
 * @return unknown_type
 */
function pager_has_more_left_pages($pager, $sliding_range)
{
  $left_pages = floor($sliding_range / 2);
  if (
    (($pager->getPage() - $left_pages) > $pager->getFirstPage()) 
    && 
    ($pager->getLastPage() > $sliding_range)
   )
  {
    return true;
  }
}


/**
 * Retrieves the entity popup vertical size from configuration
 * 
 * @return the configured vertical size or 720 if not set
 */
function ull_entity_popup_height()
{
  $verticalSize = sfConfig::get('app_ull_user_user_popup_vertical_size', 720);

  if (!is_int($verticalSize))
  {
    throw new UnexpectedValueException('user_popup_vertical_size in app.yml must be an integer.');
  }
  
  return $verticalSize;
}


/**
 * Links a string to the ull entity popup for the specified id
 * 
 * @param unknown_type $value the string to wrap the link around
 * @param unknown_type $entityId id of the entity the popup should show
 * @return unknown_type the linked value
 */
function ull_link_entity_popup($value, $entityId)
{
  
  if (sfConfig::get('app_ull_user_enable_user_popup', true))
  {
    $popupUri = 'ullUser/show?username=' . $entityId;
  
    return link_to($value, $popupUri, array(
          'title' => __('Show business card', null, 'ullCoreMessages'),
          'onclick' => 'this.href="#";popup(
            "' . url_for($popupUri) . '",
            "Popup' . $entityId . '",
            "width=720,height=' . ull_entity_popup_height() . ',scrollbars=yes,resizable=yes"
          );'
          ));
  }
  else
  {
    return $value;
  }
}


/**
 * Returns an icon link to the ull entity popup for the specified id
 * 
 * @param unknown_type $entityId id of the entity the popup should show
 * @return unknown_type the icon link
 */
function ull_link_entity_icon_popup($entityId)
{
  if (sfConfig::get('app_ull_user_enable_user_popup', true))
  {  
    $popupUri = 'ullUser/show?username=' . $entityId;
    
    $icon = '/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') .
             'Plugin/images/ull_user_16x16';
    
    return link_to(image_tag($icon, array('class' => 'ull_user_popup_icon')), $popupUri, array(
          'title' => __('Show business card', null, 'ullCoreMessages'),
          'onclick' => 'this.href="#";popup(
            "' . url_for($popupUri) . '",
            "Popup ' . $entityId . '",
            "width=720,height=' . ull_entity_popup_height() . ',scrollbars=yes,resizable=yes"
          );'
          ));
  }
}


/**
 * Adds stylesheets for the given widget class to the response object.
 * Note: The widget will most likely need some modification!
 * It must have a static method named getStylesheetsStatic().
 * The default method (getStylesheets) is not static.
 *
 * @param string $widgetClass class name of a widget 
 */
function use_stylesheets_for_widget($widgetClass)
{
  if (!method_exists($widgetClass, 'getStylesheetsStatic'))
  {
    throw new InvalidArgumentException($widgetClass . ' has no getStylesheetStatic() method');
  }
  
  $response = sfContext::getInstance()->getResponse();
  
  foreach (call_user_func(array($widgetClass, 'getStylesheetsStatic')) as $file => $media)
  {
    $response->addStylesheet($file, '', array('media' => $media));
  }
}


/**
 * Adds javascripts for a specific widget to the response object.
 * Note: The widget will most likely need some modification!
 * It must have a static method named getJavaScriptsStatic().
 * The default method (getJavaScripts) is not static.
 *
 * @param string $widgetClass class name of a widget
 */
function use_javascripts_for_widget($widgetClass)
{
  if (!method_exists($widgetClass, 'getJavaScriptsStatic'))
  {
    throw new InvalidArgumentException($widgetClass . ' has no getJavaScriptsStatic() method');
  }
  
  $response = sfContext::getInstance()->getResponse();
  foreach (call_user_func(array($widgetClass, 'getJavaScriptsStatic')) as $file)
  {
    $response->addJavascript($file);
  }
}

