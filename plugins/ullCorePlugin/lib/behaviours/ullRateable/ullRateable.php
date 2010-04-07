<?php

class ullRateable extends Doctrine_Template
{
  public function __construct(array $options = array())
  {
    parent::__construct($options);

    $this->_plugin = new ullRateableRecordGenerator($options);
  }

  public function setUp()
  {
    $this->_plugin->initialize($this->_table);

    $this->_table->unshiftFilter(new ullRateableRecordFilter());
  }

  public function getAvgRating()
  {
    return $this->_plugin->getAvgRating($this->getInvoker());
  }

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
  
  public function setUserRating($rating = 3)
  {
    $rating = intval($rating);
    if ($rating < 1 || $rating > $this->_plugin->getOption('max_rating'))
    {
      throw new InvalidArgumentException(
        'Rating must be an integer between 1 and ' . $this->_plugin->getOption('max_rating'));
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
}