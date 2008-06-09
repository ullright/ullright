<?php

/**
 * Subclass for performing query and update operations on the 'ull_wiki' table.
 *
 * 
 *
 * @package plugins.ullWikiPlugin.lib.model
 */ 
class UllWikiPeer extends BaseUllWikiPeer
{
  
  public static function retrieveByDocid($docid) {
    
    $c = new Criteria();
    $c->add(self::CURRENT, true);
    $c->add(self::DOCID, $docid);
    
    return self::doSelectOne($c);    
  }  
  
}
