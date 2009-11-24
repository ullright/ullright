<?php
/**
 * This class features custom extension to the Doctrine query class.
 * 
 * Please note that any additions should be done mainly in the
 * ullQuery class and not here. Only add methods to ullDoctrineQuery
 * which need imperative access to internal Doctrine query components.
 * 
 * Thanks to Ken Marfilla and danielfamily.com/techblog/ for ideas
 * and methods.
 */
class ullDoctrineQuery extends Doctrine_Query
{
  /**
   * Adds an opening parenthesis for nested
   * where clauses.
   * 
   * @return $this
   */
  public function openParenthesisBeforeLastPart()
  {
    $where = $this->_dqlParts['where'];
    if (count($where) > 0) {
      array_splice($where, count($where) - 1, 0, '(');
      $this->_dqlParts['where'] = $where;
    }
    
    
    return $this;

  }

  /**
   * Adds an closing parenthesis for nested
   * where clauses.
   * 
   * @return $this
   */
  public function closeParenthesis()
  {
    $where = $this->_dqlParts['where'];
    if (count($where) > 0)
    {
      $where[] = ')';
      $this->_dqlParts['where'] = $where;
    }

    return $this;
  }
  
  /**
   * This function will wrap the current dql where statement
   * in parenthesis. Can be called multiple times.
   *
   * @return $this
   */
  public function wrapExistingWhereInParantheses() {
    $where = $this->_dqlParts['where'];
    if (count($where) > 0) {
      array_unshift($where, '(');
      array_push($where, ')');
      $this->_dqlParts['where'] = $where;
    }

    return $this;
  }
}
