<?php

class AddUllWikiSlug extends Doctrine_Migration_Base
{
  
  public function migrate($direction)
  {
    $this->column($direction, 'ull_wiki', 'slug', 'string', '255',
      array('unique' => true));
  }
  
  public function postUp()
  {
    //select sluggable record listener from the table's listener chain
    //(is there a better way to do this?)
    $listenerChain = Doctrine::getTable('UllWiki')->getRecordListener();
    $listener = new Doctrine_Null();
    
    for ($i = 0; $listener !== null ; $i++)
    {
      $listener = $listenerChain->get($i);
          
      if($listener instanceof Doctrine_Template_Listener_Sluggable)
      {
        break;
      }
    }
    
    if ($listener === null)
    {
      throw new RuntimeException('Sluggable listener not found on UllWiki table');
    }
    
    //now that we have the listener, we can use it to generate
    //unique slugs for every ullWiki document
    
    $docs = Doctrine::getTable('UllWiki')->findAll();
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    foreach ($docs as $doc)
    {
      $stm = $dbh->prepare('UPDATE ull_wiki SET slug = :slug WHERE id = :id');
          $stm->bindValue(':slug', $listener->getUniqueSlug($doc, $doc['subject']), PDO::PARAM_STR);
          $stm->bindValue(':id', $doc['id'], PDO::PARAM_INT);
          $stm->execute();
    }
  }
}
