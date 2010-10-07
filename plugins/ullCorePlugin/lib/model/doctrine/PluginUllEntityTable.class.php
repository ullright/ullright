<?php
/**
 */
class PluginUllEntityTable extends UllRecordTable
{
  /**
   * checks if a given UllUser is the given UllEntity or member of the given UllEntity
   *
   * $user is optional, by default the logged in user is used.
   *
   * @param UllEntity $entity
   * @param mixed $user         optional, = logged in user per default
   * @return string 'user' or 'group' if successful, null if user is not logged in
   */
  public static function has(UllEntity $entity, $user = null)
  {
    if ($user === null)
    {
      $userId = sfContext::getInstance()->getUser()->getAttribute('user_id');

      if ($userId !== null)
      {
        $user = Doctrine::getTable('UllUser')->findOneById($userId);
      }
      else
      {
        return null;
      }
    }

    if (!$user instanceof UllUser)
    {
      throw new InvalidArgumentException('user must be a UllUser object');
    }

    if ($entity->type == 'user')
    {
      if ($entity->id == $user->id)
      {
        return 'user';
      }
    }
    elseif ($entity->type == 'group')
    {
      if (UllUserTable::hasGroup($entity->id, $user->id))
      {
        return 'group';
      }
    }
  }


  /**
   * Find by Id
   *
   * @param integer $id
   * @return UllEntity
   */
  public static function findById($id)
  {
    $q = new Doctrine_Query;
    $q
    ->from('UllEntity')
    ->where('id = ?', $id)
    ->useResultCache(true)
    ;

    return $q->fetchOne();
  }


  /**
   * Find UllEntity by display_name
   *
   * @param string $displayName
   * @return UllEntity
   */
  public static function findByDisplayName($displayName)
  {
    $q = new Doctrine_Query;
    $q
    ->from('UllEntity')
    ->where('display_name = ?', $displayName)
    ->useResultCache(true)
    ;

    return $q->fetchOne();
  }


  /**
   * Find UllEntity->id by display_name
   *
   * @param string $displayName
   * @return integer
   */
  public static function findIdByDisplayName($displayName)
  {
    $entity = self::findByDisplayName($displayName);
    if ($entity)
    {
      return $entity->id;
    }
  }


  /**
   * Build org-chart graph
   *
   * @param UllEntity $entity
   * @param integer $depth
   * @param boolean $hydrate
   * @param integer $level
   * @return ullTreeNode
   */
  public static function getSubordinateTree(UllEntity $entity, $depth = 999999999, $hydrate = true, $level = 1)
  {
    $node = new ullTreeNodeOrgchart(($hydrate) ? $entity : $entity->id);

    if (($subordinates = $entity->getSubordinates(true, true)) && ($level < $depth))
    {
      $level++;

      foreach ($subordinates as $subordinate)
      {
        // Distinguish between different types of subs
        if ($subordinate->isSuperior())
        {
          // Sub-superiors are added as normal leafes/subtrees
          $node->addSubnode(self::getSubordinateTree($subordinate, $depth, $hydrate, $level));
        }
        elseif ($subordinate->isAssistant())
        {
           $node->addAssistant(self::getSubordinateTree($subordinate, $depth, $hydrate, $level));
        }
        else
        {
          // Special handling for subordinates - they are attached in a separate "container" 
          $node->addSubordinate(self::getSubordinateTree($subordinate, $depth, $hydrate, $level));
        }
      }
    }

    return $node;
  }
}