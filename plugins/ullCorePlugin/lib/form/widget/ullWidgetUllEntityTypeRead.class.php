<?php

class ullWidgetUllEntityTypeRead extends ullWidget
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
  	switch ($value) {
  		case 'user': return __('User', null, 'common');
  		case 'group': return __('Group', null, 'common');
      case 'clone_user': return __("Clone user", null, 'ullCoreMessages');
      case 'origin_dummy': return __("Inventory origin dummy user", null, 'ullVentoryMessages');
      case 'status_dummy': return __("Inventory status dumy user", null, 'ullVentoryMessages');
  	}
  }
}
