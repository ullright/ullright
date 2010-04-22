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
    $this->generator = new ullTableToolGenerator('UllNews', 'r', 'list', array('image_upload', 'activation_date', 'title', 'abstract', 'link_name', 'link_url'));
    
    $this->docs = Doctrine::getTable('UllNews')->findActiveNews();
    
    $this->generator->buildForm($this->docs);
    
    $this->setVar('generator', $this->generator, true);
    
    $this->allow_edit = UllUserTable::hasPermission('ull_news_edit');
  }
  
  public function executeRssFeed(sfRequest $request)
  {
    $this->lang = $this->getUser()->getCulture();
  }
}
