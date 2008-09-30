<?php

/*
 * ull helpers
 */

//use_helper('Form');

sfLoader::loadHelpers(array('Date', 'Form', 'Tag', 'Url'));


/**
 * Extends symfony's DateHelper with a specific date format
 * This is culture sensitive.
 *
 * @param string date         iso date like "2007-12-04 13:45:10"
 * @return string date        formated date like "4.12.2007" for "de"
 */

function ull_format_date($date, $culture = null) {
  
//  sfLoader::loadHelpers(array('Date'));

  if ($date) {
    if (!$culture) {
      $culture = sfContext::getInstance()->getUser()->getCulture();
    }
    
    $culture_parts = explode('_', $culture);
    $language = $culture_parts[0];
  
    switch ($language) {
      case 'de':
        $year = substr($date,0,4);
        $month = substr($date,5,2);
        $day = substr($date,8,2);
  
        if (substr($month,0,1) == 0) {
          $month = substr($month,1,1);
        }
    
        if (substr($day,0,1) == 0) {
          $day = substr($day,1,1);
        }
  
        $output =  $day.'.'.$month.'.'.$year;
        return $output;      
        break;
        
      default:
        return format_date($date, 'd', $culture); 
    }
  }
}


/**
 * Extends symfony's DateTimeHelper with a specific date/time format
 * This is culture sensitive.
 *
 * @param string date         iso date like "2007-12-04 13:45:10"
 * @return string date        formated date like "4.12.2007 13:45h" for "de"
 */

function ull_format_datetime($datetime, $culture = null) {
  
  if ($datetime) {
  
    sfLoader::loadHelpers(array('Date'));
  
    if (!$culture) {
      $culture = sfContext::getInstance()->getUser()->getCulture();
    }
    
    $culture_parts = explode('_', $culture);
    $language = $culture_parts[0];
  
    switch ($language) {
      case 'de':
        
        $date = ull_format_date($datetime);
  
        $hour = substr($datetime,11,2);
        $minute = substr($datetime,14,2);
  
        if (substr($hour,0,1) == 0) {
          $hour = substr($hour,1,1);
        }
        
        return $date.' '.$hour.':'.$minute.'h';
        break;
        
      default:
        return format_datetime($datetime, 'd', $culture); 
    }
  }
}

/**
 * Wrapper for link_to(image_tag(...) for standard icons in 
 * plugins/myTheme/web/images/action_icons...
 * 
 * automagically sets the alt and title attribute
 *
 * @param link string         symfony internal URI
 * @param icon string         name of the icon without suffix (e.g. edit for edit.png)
 * @param alt string          optional, 'alt' and 'title' caption, default = icons filename
 * @param link_option string  optional, link_to() option (3rd argument)
 * @return string             html
 */

function ull_icon($link, $icon, $alt = null, $link_option = null) {
  
  if (!$alt) {
    $alt = $icon;
  }  
  
  return link_to(
    image_tag(
      '/' 
        . sfConfig::get('app_theme', 'ullThemeDefault')
        . '/images/action_icons/' . $icon . '.png',
      'alt=' . $alt . ' title=' . $alt . ' style=vertical-align:bottom;'
    ),  
    $link,
    $link_option
  ); 
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
 */

function ull_icon_to_function($function, $icon, $alt = null, $link_option = null) {
  
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

//  ullCoreTools::printR($merge_array);
  
  $params = _ull_reqpass_initialize($merge_array);  

  $link = _ull_reqpass_build_url($params);
  
  return ull_icon($link, $icon, $alt, $link_option);
  
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

function ull_link_to($name = 'link', $url = array(), $options = array()) {

  $options = _convert_options($options);

  if (isset($options['link_new_window'])) {
    unset($options['link_new_window']);
    $options['class']   = 'link_new_window';
    $options['target']  = '_blank';
    $options['title']   = __('Link opens in a new window', null, 'common');
  }

  if (isset($options['link_external'])) {
    unset($options['link_external']);
    $options['class'] = 'link_external';
    $options['target'] = '_blank';
    $options['title']   = __('Link opens in a new window', null, 'common');
  }

  return _ull_to($name, $url, $options, 'link');

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
 * @param name string         link name to display
 * @param url mixed           can be a internal symfony url, or an array with params to add, remove or overwrite (eg. 'action' => 'list')
 * @param options mixed       string or array of options 
 * @return string             html link
 */

function ull_button_to($name = 'link', $url = array(), $options = array()) {
  
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
 * @param name string         link name to display
 * @param url mixed           can be a internal symfony url, or an array with params to add, remove or overwrite (eg. 'action' => 'list')
 * @param options mixed       string or array of options
 * @param type string         'link' or 'button' 
 * @return string             html link
 */

function _ull_to($name = 'link', $url = array(), $options = array(), $type = 'link') {
  
  if (is_array($url)) {
    $params = _ull_reqpass_initialize($url);  
    $url = _ull_reqpass_build_url($params);
  }

  $options = _convert_options($options);

  // diable ull_js_observer if user has no javascript
  if (!sfContext::getInstance()->getUser()->getAttribute('has_javascript', false)) {
  	unset($options['ull_js_observer_confirm']);
  }

  $action = sfContext::getInstance()->getActionName();
  // disable ull_js_observer if action is not edit or create
  if (!$action != 'create' && $action != 'edit') {
    unset($options['ull_js_observer_confirm']);
  }

  // disable ull_js_observer for target='_blank' (makes no sense)
  if (isset($options['target']) && $options['target'] == '_blank') {
    unset($options['ull_js_observer_confirm']);    
  }
 
  if (isset($options['ull_js_observer_confirm'])) {
    
//    ullCoreTools::printR($html_options['ull_js_observer_confirm']);
//    sfContext::getInstance()->getLogger()->info('xxx: '.gettype($options['ull_js_observer_confirm']));

    // use default msg if no custom msg
    if (is_bool($options['ull_js_observer_confirm'])) {
      $msg = __('You will loose unsaved changes! Are you sure?', null, 'common');
    } else {
      $msg = $options['ull_js_observer_confirm'];
    }

    $action = 'return document.location.href="' . url_for($url) . '";';

    // check for the existence of the ull_js_observer hidden input tag and 
    //   do the check only if the tag exists (= check if we have a page with a form)
    $js_function =
        'if (document.getElementById("ull_js_observer_initial_state") != null'
      . '   && ull_js_observer_detect_change()) { '
      . '   if (confirm("' . $msg . '")) { '
      . '     ' . $action . 'return false;'
      . '   } else {'
      . '     return false;'
      . '   }'
      . '} else {'
      . '   ' . $action . 'return false;'
      . '}'
    ;

    unset($options['ull_js_observer_confirm']);
    
    return call_user_func($type . '_to_function', $name, $js_function, $options);

  } else {
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
      $msg = __('You will loose unsaved changes! Are you sure?', null, 'common');
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
 * Enhancement of form_tag() helper 
 * Get current request params, serialize them and pass them using a hidden field
 *
 * @param merge_array array     array with params to add, remove or overwrite (eg. 'page' => 2)
 * @param form_options array    array containing html options for the <form> tag
 * @return string               html form tag and hidden field
 */

function ull_reqpass_form_tag($merge_array = array(), $form_options = array()) {
  
  $params = _ull_reqpass_initialize($merge_array);
  
//  ullCoreTools::printR($params);
  
  $base_link = _ull_reqpass_build_base_url($params);

  echo '

<form action="'.url_for($base_link).'" method="post" '.ull_tag_options(ull_parse_attributes($form_options)).'>
<input type="hidden" name="ull_reqpass" id="ull_reqpass" value="'.htmlentities(serialize($params)).'" />

';
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
 * Get current request params, and allows adding or overriding of specific params
 *
 * @param merge_array array   array with params to add, remove or overwrite (eg. 'page' => 2)
 * @return array       array containing processed params
 */

function _ull_reqpass_initialize($merge_array = array(), $rawurlencode = true) {
  
//  ullCoreTools::printR($array);
  
  $params = sfContext::getInstance()->getRequest()->getParameterHolder()->getAll();
  
  // TODO: where does sf_culture come from? appeared in functional testing...
  unset($params['sf_culture']);
//  ullCoreTools::printR($params);
  
  // overwrite / add params
  foreach ($merge_array as $key => $value) {
    $params[$key] = $value;
  }
  
//  ullCoreTools::printR(sfContext::getInstance()->getRequest()->getParameterHolder()->getAll());
  
  //TODO: doesn't work with array syntax
  foreach ($params as $key => $value) {
    // remove empty params
    if (!$value) {
      unset($params[$key]);
    } else {
      if (isset($rawurlencode)) {
        $params[$key] = rawurlencode($value);
      }
    }
  }
  
  return $params;
  
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
  if ($params) { 
    $addition = '?';
    foreach($params as $key => $value) {
      $url .= $addition . $key . '=' . $value;
      $addition = '&';
    }
  }

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


/** 
 * encodes special chars in urls
 * used mainly for encoding '.' in url params because '.' breaks the symfony rewrite rules
 * example symfony url that does not work: 'myModule/myAction/search/file.txt'
 * 
 * @param string string   
 * @return string
 */

function ull_sf_url_encode($string) 
{
  
  // replace '.' by the corresponding html entity
  return rawurlencode(str_replace('.', '&#x2E;', $string));
  
}

/** 
 * decodes special chars in urls
 * counterpart for ull_sf_url_encode
 * 
 * @param string string   
 * @return string
 */
function ull_sf_url_decode($string) 
{
  
  // TODO: check why it is necessary to do raw_url_decode on some webservers and and on some not 
  return rawurldecode(str_replace('&#x2E;', '.', $string));
  
}


/**
 * Wrapper for sf object_checkbox_tag
 *
 * adds a input hidden tag to force having a request param even if the value = null
 * in case of an ordinary checkbox tag, there's no request param if the checkbox is unchecked
 * 
 * used for ullTableTool, ullFlow, etc to save only the enabled fields (request param fields) 
 * 
 * @param object An object.
 * @param string An object column.
 * @param array Checkbox options.
 * @param bool Checkbox value.
 *
 * @return string An html string which represents a checkbox tag.
 *
 */
function ull_object_checkbox_tag($object, $method, $options = array(), $default_value = null)
{
  
//  ullCoreTools::printR($object);
//  ullCoreTools::printR($method);
//  ullCoreTools::printR($options);
//  ullCoreTools::printR($default_value);

  // use input hidden tag instead of checkbox
  $return = object_input_hidden_tag($object, $method, $options);
  
  // create dummy checkbox tag, which is only used for user interaction
  $options = _parse_attributes($options);
  
  $name = _convert_method_to_name($method, $options);
  $id = $name;
  
  //overwrite method field name with custom name from options
  if (@$options['name']) {
    $name = $options['name'];  
    $options['name'] .= '_dummy';
  }
  
  // the same for the id
  if (@$options['id']) {
    $id = $options['id'];
    $options['id'] .= '_dummy';
  }
  
  // add javascript to trigger the hidden field with the dummy checkbox input 
  // ('+' converts boolean to integer (true -> 1, false -> 0)
  $options['onclick'] = 'document.getElementById("' . $id . '").value = +this.checked';
  // ;alert(+this.checked)
  
  // dummy checkbox
  $return .= object_checkbox_tag($object, $method, $options, $default_value);
  
  return $return;

  // javascript:
}

function ull_js_add_tag() {
  
  return javascript_tag('
    function addTag(tag, field_id) {
      if (!field_id) {
        var field_id = "tags";
      }
    
      var tags = document.getElementById(field_id).value;
      
      if (tags == "") {
        var tags_arr = new Array;
      } else {
        var tags_arr = tags.split(",");
      }
      tags_arr.push(tag);
      
      
      // trim tags
      for (var i = 0; i < tags_arr.length; i++) {            
        tags_arr[i] = trim(tags_arr[i]);
        //tags_arr[i] = tags_arr[i].toLowerCase(); // problem with utf-8 umlaute etc            
      }
      
      tags_arr.sort();
      
      // remove double tags
      
      tags_clean = new Array;
      for(var i = 0; i < tags_arr.length; i++) {
        var clean = true;
        var ArrayVal = tags_arr[i];
        for(var j = i+1; j < tags_arr.length; j++) {
          if(tags_arr[j] == ArrayVal) 
           clean = false;
        }
           
        if(clean == true)
          tags_clean.push(ArrayVal)
      }
      
      //recombine array
      var out = tags_clean.join(", ");
      document.getElementById(field_id).value = out;
      
    }
    
    function trim(str) {
      return str.replace (/^\s+/, "").replace (/\s+$/, "");
    }
  ;');
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

    for (i=0; i<elements.length; i++) {
      if (elements[i]) {
        initial_state[i] = elements[i].value;
      }
    }

    document.getElementById("ull_js_observer_initial_state").value = initial_state.join("@@@");



    function ull_js_observer_detect_change() {

      var inital_state = document.getElementById("ull_js_observer_initial_state").value.split("@@@");
      var elements = document.getElementById("' . $form_id . '");

      for (i=0; i<elements.length; i++) {
        var e = elements[i];

        //detect FCKEditor Field
        if (e.id.indexOf("___Config") > -1) {
          var instance_name = e.id.replace(/___Config/, "");
          var oEditor = FCKeditorAPI.GetInstance(instance_name);
          if (oEditor.IsDirty()) {
            return true;
          }
        } else {

          //normal form field
          if (e.value != initial_state[i]) {
            //alert("Field "+e.id+" changed! Old value: "+initial_state[i]+" New value: "+e.value);
            return true;
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
  * Create a javascript debug popup
  */
function ull_trap($v, $force = false)
{
  if (SF_DEBUG || $force)
  {

    error_reporting(E_ERROR | E_PARSE);

    $key = rand(1,10000000);
    $args = debug_backtrace();
    $file = $args[0]['file'];
    $line = $args[0]['line'];

    SF_ROOT_DIR ? $file = str_replace(SF_ROOT_DIR, '/..', $file) : '';
    $file = str_replace('\\', '\\\\', $file);
    ob_start();
    print_r($v);
    $result = ob_get_contents();
    ob_end_clean();
    $or = array("\n", " "   );
    $pr = array("<br>", "&nbsp;");

    $result = str_replace($or, $pr, $result);
    $result = str_replace('=>', '<font color="00FF00">=></font>', $result);
    $result = str_replace('Array', '<font color="00FF00"><b>Array</b></font>', $result);
    $result = preg_replace("/\[(\w*)\]/i", '<font color="CACACA">[$1]</font>', $result);

    $result = preg_replace('/([^ !#$%@()*+,-.\x30-\x5b\x5d-\x7e])/e',"'\\x'.(ord('\\1')<16? '0': '').dechex(ord('\\1'))",$result);
?>

<script language="JavaScript">
  ullPopup<?php echo $key; ?> = window.open("","ullWin<?php echo $key; ?>","toolbar=no,scrollbars=yes,resizable=yes,width=700,height=400");
  ullPopup<?php echo $key; ?>.document.writeln('<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>ull_trap()</title><style>body{background-color: #000000; padding: 0; margin: 0}</style></head><body>');
  ullPopup<?php echo $key; ?>.document.writeln('<div style="background-color: #333333; font-family: courier; color: white; font-size: 8px; padding: 5px; position: fixed; width: 100%">Trap in <span style="color: #00FF00; font-weight: bold"><?php echo $file; ?></span> on line <span style="color: #00FF00; font-weight: bold"><?php echo $line; ?></span></div>');
  ullPopup<?php echo $key; ?>.document.writeln('<br><br><div style="color: yellow; font-size: 8px; font-family: courier"><?php echo $result; ?></div>');
  ullPopup<?php echo $key; ?>.document.writeln('</div> </bb> </html>');
</script>

<?
  }
  else
  {
    return null;
  }
} // end function

function _convert_array_to_string($arr) {
	$str = '';
	foreach ($arr as $key => $value) {
		$str .= ' '.$key.'="'.$value.'" ';
	}
	return $str;
}

?>