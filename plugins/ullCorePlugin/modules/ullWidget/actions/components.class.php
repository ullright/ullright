<?php

// autoloading for plugin lib actions is broken in symfony-1.0.x (not solved in 1.0.11 yet) 
require_once(sfConfig::get('sf_plugins_dir'). '/ullCorePlugin/modules/ullWidget/lib/BaseUllWidgetComponents.class.php');

class ullWidgetComponents extends BaseUllWidgetComponents
{
  
}
