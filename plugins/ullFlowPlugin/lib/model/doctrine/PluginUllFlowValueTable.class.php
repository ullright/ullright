<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllFlowValueTable extends UllRecordTable
{

  public static function findByDocIdAndSlug($ullFlowDocId, $slug)
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllFlowValue v, v.UllFlowDoc d, v.UllFlowColumnConfig cc')
      ->where('d.id = ?', $ullFlowDocId)
      ->addWhere('cc.slug = ?', $slug)
    ;
    $result = $q->execute()->getFirst();
//    var_dump($result->toArray());

    if ($result == null)
    {
      throw new InvalidArgumentException("One or both of the arguments ull_flow_doc_id ($ullFlowDocId) and slug ($slug) are invalid");
    }
    
    return $result;    
  }
    
}