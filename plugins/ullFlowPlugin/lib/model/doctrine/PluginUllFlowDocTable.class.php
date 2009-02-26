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
		
    // masteradmin
    if (UllUserTable::hasGroup('MasterAdmins'))
    {
      return $q;
    }
    
    // app-specific global read access
    if ($app)
    {
      if (UllUserTable::hasPermission('UllFlow_' . $app->slug . '_global_read'))
      {
        return $q;
      }
    }
    
  	$userId = sfContext::getInstance()->getUser()->getAttribute('user_id');
  	
  	// check is_public
  	if (!$userId)
  	{
  	  $q->addWhere('x.UllFlowApp.is_public = ?', true);
  	  
  	  return $q;
  	}

  	//normal access check
  	
    // assigned to
//      $q->addWhere('(');
    $q->leftJoin('x.UllEntity e');
    
//      $q->addQueryPart('where','foobar = 666');
      
    $q->leftJoin('e.UllEntityGroupsAsGroup aeg');
//      $q->orWhere('aeg.ull_entity_id = ?', $userId);

    
    
    
    // memory:
    $q->leftJoin('x.UllFlowMemories m');
//      $q->orWhere('m.creator_ull_entity_id = ?', $userId);
    $q->leftJoin('m.CreatorUllEntity.UllEntityGroupsAsGroup meg');
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
      OR gru.id = ? AND p.slug LIKE ?
      OR x.UllFlowApp.is_public = ?',
      array($userId, $userId, $userId, $userId, $userId, '%_global_read', true)
    );

//    printQuery($q->getQuery());
//    var_dump($q->getParams());
//    die('access');          
    
    return $q;
	}
	
	/**
	 * Check if a given UllFlowDoc id exists
	 * 
	 * @param integer $id    UllFlowDoc->id
	 * @return boolean
	 */
	public static function hasId($id)
	{
	  $q = new Doctrine_Query;
	  $q->from('UllFlowDoc d');
	  $q->where('d.id = ?', $id);
	  
	  return (bool) $q->count();
	}
	
	/**
	 * Check if a given virtual column exists
	 * 
	 * @param integer $id    UllFlowDoc->id
	 * @param string $slug   UlLFlowColumnConfig->slug
	 * @return boolean
	 */
	public static function hasVirtualColumn($id, $slug)
	{
    $q = new Doctrine_Query;
    $q
      ->from('UllFlowDoc d, d.UllFlowApp a, a.UllFlowColumnConfigs c')
      ->where('d.id = ?', $id)
      ->addWhere('c.slug = ?', $slug)
    ;
    
    return (bool) $q->count();
	}
}