<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllWikiTable extends UllRecordTable
{

  public static function findByDocid($docid)
  {
    $q = new Doctrine_Query;
    $q->from('UllWiki w')
      ->where('w.docid = ?', $docid)
    ;
    return $q->execute()->getFirst();
  }

  public static function setOldDocsDeleted($docid) {
    $q = new Doctrine_Query;
    $q->delete('UllWiki w')
      ->from('UllWiki w')
      ->where('w.docid = ?', $docid)
      ->execute();
  }

  public static function getNextFreeDocid() {

    $q = new Doctrine_Query;
    $q->from('UllWiki w')
      ->orderBy('w.docid DESC')
      ->limit(1)
    ;
    $ullwiki = $q->execute()->getFirst();


    if (!$ullwiki) {
      $docid = 1;
    } else {
      $docid = $ullwiki->getDocid() + 1;
    }
    return $docid;

  }
}
