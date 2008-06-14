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
 */

function ull_reqpass_icon($merge_array = array(), $icon, $alt = null, $link_option = null) {
  
//  ullCoreTools::printR($array);
  
  $params = _ull_reqpass_initialize($merge_array);  

  $link = _ull_reqpass_build_url($params);
  
  return ull_icon($link, $icon, $alt, $link_option);
  
}



/**
 * Enhancement of link_to() helper 
 * Get current request params, and allows adding or overriding of specific params
 *
 * @param name string         link name to display
 * @param merge_array array   array with params to add, remove or overwrite (eg. 'page' => 2)
 * @return string             html link
 */

function ull_reqpass_link_to($name = 'link', $merge_array = array()) {
  
//  ullCoreTools::printR($array);
  
  $params = _ull_reqpass_initialize($merge_array);  

  $link = _ull_reqpass_build_url($params);
  
//  ullCoreTools::printR($link);
//  exit();
  
  return link_to($name, $link);
  
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
  
  return _ull_link_to($name, $url, $options, 'link');
  
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
  
  return _ull_link_to($name, $url, $options, 'button');
  
}


/**
 * generic enhancement for symfony xxx_to() helper
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

function _ull_link_to($name = 'link', $url = array(), $options = array(), $type = 'link') {
  
  if (is_array($url)) {
    $params = _ull_reqpass_initialize($merge_array);  
    $url = _ull_reqpass_build_url($params);
  }
  
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
      . '     return document.location.href="' . $url . '";'
      . '   } else {'
      . '     return false;'
      . '   }'
      . '} else {'
      . '   return document.location.href="' . $url . '";'
      . '}'
    ;
      
    unset($html_options['ull_js_observer_confirm']);
    
    return call_user_func($type . '_to_function', $name, $js_function, $html_options);
    
  } else {
    return call_user_func($type . '_to', $name, $url, $options);
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
  
  echo form_tag($base_link, $form_options);
  
  echo input_hidden_tag('ull_reqpass', serialize($params));
  
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
  
  // overwrite / add params
  foreach ($merge_array as $key => $value) {
    $params[$key] = $value;
  }
  
  // remove empty params
  foreach ($params as $key => $value) {
    if (!$value) {
      unset($params[$key]);
    } else{
      if (@$rawurlencode) {
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
    // first param
    $url .= '?' . key($params) . '=' . array_shift($params); 
  
    // other params
    foreach($params as $key => $value) {
      $url .= '&' . $key . '=' . $value;
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
  $html = form_tag(null, 'id=ull_js_observer');
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
        if (elements[i].value != initial_state[i]) {
          //alert("changed!");
          return true;
        }
      }
    }
  ');
  
  return $html;
  
}


?>