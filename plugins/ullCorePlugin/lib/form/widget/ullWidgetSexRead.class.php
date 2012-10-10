<?php

class ullWidgetSexRead extends ullWidget
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
  	switch ($value) {
  		case 'm': return __("Male", null, 'common');
  		case 'f': return __("Female", null, 'common');
      case '': return __("", null, 'common');
  		default: throw new InvalidArgumentException("Sex must be 'm', 'f' or ''.");
  	}
  }
}
