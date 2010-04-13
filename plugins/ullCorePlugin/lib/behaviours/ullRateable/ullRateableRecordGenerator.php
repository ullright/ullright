<?php

class ullRateableRecordGenerator extends Doctrine_Record_Generator
{
  protected $_options = array(
    'className' => '%CLASS%Rateable',
    'max_rating'  => 5,

    //we need the following two set
    'generateFiles' => false,
    'children'      => array(), 
  );

  public function  __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
  }

  public function buildRelation()
  {
    $this->buildForeignRelation('Ratings');
    $this->buildLocalRelation();
  }

  public function setTableDefinition()
  {
    $tableName = $this->_options['table']->getTableName();
    $this->setTableName($tableName . '_rating');

    $this->hasColumn('voter_ull_user_id', 'integer', 8, array('primary' => true));
    $this->hasColumn('rating', 'int', 1, array('notnull' => true)); //int because we do not allow 'half-star' ratings
  }

  public function getAvgRating(Doctrine_Record $invoker)
  {
    $q = Doctrine_Query::create()
      ->select('avg(ratings.rating) as average_rating')
      ->from($this->getOption('className') . ' as ratings')
      ->where('ratings.id = ?', $invoker->id)
    ;

    $result = $q->fetchOne();
    return $result['average_rating'];
  }

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
      //why doesn't this work?
      //$ratingObject->delete();
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
