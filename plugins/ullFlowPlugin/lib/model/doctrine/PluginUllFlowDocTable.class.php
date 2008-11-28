<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllFlowDocTable extends UllRecordTable
{
  /**
   * adds access checks to the query
   * 
   * @param Doctrine_Query $q
   * @param $app empty or a UllFlowApp
   * @return Doctrine_Query
   * @throws InvalidArgumentException
   */
	public static function queryAccess(Doctrine_Query $q, $app)
	{
		if ($app)
		{
		  if(!$app instanceof UllFlowApp)
		  {
		    throw new InvalidArgumentException('Please supply a valid UllFlowApp');
		  }
		}
		
    $global_access = false;
    
    // masteradmin
    if (UllUserTable::hasGroup('MasterAdmins'))
    {
      $global_access = true;
    }
    
    // app-specific global read access
    if ($app and !$global_access)
    {
      if (UllUserTable::hasPermission('UllFlow_' . $app->slug . '_global_read'))
      {
        $global_access = true;
      }
    }

    //normal access check
    if (!$global_access) 
    {
    	$userId = sfContext::getInstance()->getUser()->getAttribute('user_id');
    	
      // assigned to
//      $q->addWhere('(');
      $q->leftJoin('x.UllEntity e');
//      $q->addQueryPart('where','foobar = 666');
      
      $q->leftJoin('e.UllEntityGroup aeg');
//      $q->orWhere('aeg.ull_entity_id = ?', $userId);
      
      // memory:
      $q->leftJoin('x.UllFlowMemories m');
//      $q->orWhere('m.creator_ull_entity_id = ?', $userId);
      $q->leftJoin('m.CreatorUllEntity.UllEntityGroup meg');
//      $q->orWhere('meg.ull_entity_id = ?', $userId);
      
      // global read access:
      $q->leftJoin('x.UllFlowApp.UllPermission p');
      $q->leftJoin('p.UllGroup.UllUser gru');
//      $q->orWhere('gru.id = ? AND p.slug LIKE ?', array($userId, '%_global_read'));
      
      // moved all where clauses into one statement to properly set the braces
      $q->addWhere('
        e.id = ? 
        OR aeg.ull_entity_id = ? 
        OR m.creator_ull_entity_id = ? 
        OR meg.ull_entity_id = ?
        OR gru.id = ? AND p.slug LIKE ?',
        array($userId, $userId, $userId, $userId, $userId, '%_global_read')
      );
    }

    return $q;
	}
	
}