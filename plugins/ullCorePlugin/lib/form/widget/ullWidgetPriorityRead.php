<?php

class ullWidgetPriorityRead extends ullWidget
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
  	switch ($value) {
  		case 1: return __("Very high", null, 'common');
  		case 2: return __("High", null, 'common');
  		case 3: return __("Medium", null, 'common');
  		case 4: return __("Low", null, 'common');
  		case 5: return __("Very low", null, 'common');
  		default: throw new InvalidArgumentException('Priority must be 1, 2, 3, 4 or 5.');
  	}
  }
}

?>