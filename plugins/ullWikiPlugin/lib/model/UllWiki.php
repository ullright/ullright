<?php

/**
 * Subclass for representing a row from the 'ull_wiki' table.
 *
 * 
 *
 * @package plugins.ullWikiPlugin.lib.model
 */ 
class UllWiki extends BaseUllWiki
{
  
  public function getNextFreeDocid() {
    
    $c = new Criteria();
//    $c->addSelectColumn(WikiPeer::DOCID);
    $c->addDescendingOrderByColumn(UllWikiPeer::DOCID);
    
    $ullwiki = UllWikiPeer::doSelectOne($c);
    
//    weflowTools::printR($wiki);

    if (!$ullwiki) {
      $docid = 1;
    } else {
      $docid = $ullwiki->getDocid() + 1;
//      $docid++;
    }   
//    die("### $docid ###");
    return $docid;
    
  }
  
  public function setOldDocsNonCurrent() {
    //echo "###",$this->docid,'###';
    
    $c = new Criteria();
    $c->add(UllWikiPeer::DOCID, $this->docid);
    $ullwikis = UllWikiPeer::doSelect($c);
    foreach ($ullwikis as $ullwiki) {      
      $ullwiki->setCurrent(false);
      $ullwiki->save();
    }
  }  
  
}

sfPropelBehavior::add('UllWiki', array('sfPropelActAsTaggableBehavior'));