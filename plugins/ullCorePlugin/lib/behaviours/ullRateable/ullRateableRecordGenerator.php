<?php

/**
 * ullRateableRecordGenerator is a record generator used by
 * the ullRateable behavior. It creates a separate table
 * for a model, allowing for the storage of ratings; and
 * also provides methods to get and set ratings and calculate
 * the average.
 */
class ullRateableRecordGenerator extends Doctrine_Record_Generator
{
  protected $_options = array(
    'className' => '%CLASS%Rateable',
    'max_rating'  => 5,

    //we need the following two set
    'generateFiles' => false,
    'children'      => array(), 
  );

  /**
   * Returns a new instance of this record generator.
   * 
   * @param array $options
   * @return ullRateableRecordGenerator the new instance
   */
  public function  __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
  }

  /**
   * Internal function called by Doctrine to build relations
   */
  public function buildRelation()
  {
    //this allows us to access ratings from the owning model,
    //e.g. ullLocation.Ratings
    $this->buildForeignRelation('Ratings');
    $this->buildLocalRelation();
  }

  /**
   * Internal function called by Doctrine; responsible
   * for defining the table used to store ratings.
   * The primary key is specified as the combination of the
   * id of the owning model and the voting user's id.
   * In addition, there is a column for the actual rating
   */
  public function setTableDefinition()
  {
    $tableName = $this->_options['table']->getTableName();
    $this->setTableName($tableName . '_rating');

    $this->hasColumn('voter_ull_user_id', 'integer', 8, array('primary' => true));
    $this->hasColumn('rating', 'int', 1, array('notnull' => true)); //int because we do not allow 'half-star' ratings
  }

  /**
   * Retrieves the average rating for a given Doctrine_Record
   * from the database
   * 
   * @param Doctrine_Record $invoker record the average rating should be retrieved for
   * @return float the average rating
   */
  public function getAvgRating(Doctrine_Record $invoker)
  {
    $q = Doctrine_Query::create()
      ->select(ullRateable::getInternalAvgQueryPart('ratings') . ' as average_rating')
      ->from($this->getOption('className') . ' as ratings')
      ->where('ratings.id = ?', $invoker->id)
    ;

    $result = $q->fetchOne();
    return $result['average_rating'];
  }

  /**
   * Retrives the rating for a given ullUser's id from
   * the database, returns null if no rating was yet given.
   * 
   * @param Doctrine_Record $invoker record the rating should be retrieved for
   * @param unknown_type $ullUserId ullUser's id the rating should be retrieved for
   * @return float the user's rating, null if none stored
   */
  public function getUserRating(Doctrine_Record $invoker, $ullUserId)
  {
    $q = Doctrine_Query::create()
      ->select('ratings.rating')
      ->from($this->getOption('className') . ' as ratings')
      ->where('ratings.id = ?', $invoker->id)
      ->andWhere('ratings.voter_ull_user_id = ?', $ullUserId)
    ;
    
    $result = $q->fetchOne();
    return $result['rating'];
  }
  
  /**
   * Stores the rating for a given ullUser's id in the
   * database, replacing an existing one.
   * If the rating itself is null, an already stored
   * rating gets removed.
   * 
   * @param Doctrine_Record $invoker record the rating should be set for
   * @param unknown_type $ullUserId ullUser's id the rating should be set for
   * @param unknown_type $rating the rating (null if it should be removed)
   * @return boolean true
   */
  public function setUserRating(Doctrine_Record $invoker, $ullUserId, $rating)
  {
    $className = $this->getOption('className');
    $ratingObject = new $className();

    $ratingObject['voter_ull_user_id'] = $ullUserId;
    $ratingObject['id'] = $invoker->id;

    if ($rating)
    {
      $ratingObject['rating'] = $rating;
      $ratingObject->replace();
    }
    else
    {
      $q = Doctrine_Query::create()
        ->delete($this->getOption('className') . ' as ratings')
        ->where('ratings.id = ?', $invoker->id)
        ->andWhere('ratings.voter_ull_user_id = ?', $ullUserId)
      ;
      
      $q->execute();
    }

    return true;
  }
}
