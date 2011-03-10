<?php

/**
 * cms actions.
 *
 * @package    ullright
 * @subpackage ullCms
 * @author     Klemens Ullmann-Marx
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
// autoloading for plugin lib actions is broken in symfony-1.0.x (not solved in 1.0.11 yet) 
require_once sfConfig::get('sf_plugins_dir')  . '/ullCmsPlugin/modules/ullCms/lib/BaseUllCmsActions.class.php';

class ullCmsActions extends BaseUllCmsActions
{
  
}

  
