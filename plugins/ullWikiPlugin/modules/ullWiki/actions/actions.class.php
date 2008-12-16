<?php

/**
 * wiki actions.
 *
 * @package    ull_at
 * @subpackage wiki
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */

/*
 * empty ullWiki actions class to allow overriding/customizing in
 * /apps/frontend/modules/ullWikiPlugin/actions/actions.class.php
 * 
 * With this architecture, you can add or override only the methods
 * you need.
 * 
 */

// autoloading for plugin lib actions is broken in symfony-1.0.x (not solved in 1.0.11 yet) 
require_once(sfConfig::get('sf_plugins_dir'). '/ullWikiPlugin/modules/ullWiki/lib/BaseUllWikiActions.class.php');

class ullWikiActions extends BaseUllWikiActions
{
  
}
