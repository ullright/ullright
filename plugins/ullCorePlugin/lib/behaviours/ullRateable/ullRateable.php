<?php

/**
 * ullRateable is a behavior for Doctrine which allows
 * ullUsers to attach a rating to a specific record.
 * A user has one vote, a given vote can be removed.
 * 
 * If a model adopts this behavior it will also receive
 * an ullRateableRecordFilter, which provides rating_average
 * and rating_user properties.
 */
class ullRateable extends Doctrine_Template
{
  /**
   * Returns a new instance of this behavior
   * 
   * @param $options array of options
   * @return ullRateable instance of this behavior
   */
  public function __construct(array $options = array())
  {
    parent::__construct($options);

    $this->_plugin = new ullRateableRecordGenerator($options);
  }

  /**
   * Initializes this behavior and adds an ullRateableRecordFilter
   * to the adopting model
   */
  public function setUp()
  {
    $this->_plugin->initialize($this->_table);

    $this->_table->unshiftFilter(new ullRateableRecordFilter());
  }

  /**
   * Returns the average rating for a record, not rounded
   * 
   * @return float the rating average, not rounded
   */
  public function getAvgRating()
  {
    return $this->_plugin->getAvgRating($this->getInvoker());
  }

  /**
   * Returns the average rating for a record rounded to
   * the given precision
   * 
   * @param $precision the precision the average should get rounded to
   * @return float the rounded average
   */
  public function getRoundedAvgRating($precision = 1)
  {
    return round($this->getAvgRating(), $precision);
  }
  
  /**
   * Returns the rating for the currently logged in user
   * or null otherwise
   * 
   * @return int the user's rating
   */
  public function getUserRating()
  {
    if ($userId = sfContext::getInstance()->getUser()->getAttribute('user_id'))
    {
      return $this->_plugin->getUserRating($this->getInvoker(), $userId);
    }
    else
    {
      return null;
    }
  }
  
  /**
   * Sets the rating for the currently logged in user to
   * a given value. If this argument is null, the rating
   * is removed.
   * If no user is currently logged in, an exception is thrown.
   * 
   * @param int $rating the rating to set, null if the rating should be removed
   * @return boolean true
   */
  public function setUserRating($rating)
  {
    //if rating is null, we remove the rating
    //if not, we try to parse a valid integer
    
    if ($rating != null)
    {
      $rating = intval($rating);
      if ($rating < 1 || $rating > $this->_plugin->getOption('max_rating'))
      {
        throw new InvalidArgumentException(
          'Rating must be an integer between 1 and ' . $this->_plugin->getOption('max_rating'));
      }
    }
    
    if ($userId = sfContext::getInstance()->getUser()->getAttribute('user_id'))
    {
      return $this->_plugin->setUserRating($this->getInvoker(), $userId, $rating);
    }
    else
    {
      throw new UnexpectedValueException('A rating can only be given by a logged in user');
    }
  }
  
  /**
   * Returns a query part to calculate the average of
   * all ratings for a given alias, when used internally
   * (e.g. from ullLocationRatings itself)
   * 
   * @param string $alias
   * @return string the query part
   */
  public static function getInternalAvgQueryPart($alias)
  {
    return 'avg(' . $alias . '.rating)';  
  }
  
  /**
   * Returns a query part to calculcate the average of
   * all ratings for a given alias, when used externally
   * (e.g. from ullLocation)
   * 
   * @param string $alias
   * @return string the query part
   */
  public static function getExternalAvgQueryPart($alias)
  {
    return 'avg(' . $alias . '.Ratings.rating)';
  }
}