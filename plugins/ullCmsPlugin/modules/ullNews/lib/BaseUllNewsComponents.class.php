<?php

/**
 * News components.
 * 
 *
 * @package    ullright
 * @subpackage ullCms
 * @author     Klemens Ullmann-Marx
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllNewsComponents extends sfComponents
{  

  public function executeNewsList(sfRequest $request)
  {
    $this->newsEntries = Doctrine::getTable('UllNews')->findActiveNews();
  }
}
