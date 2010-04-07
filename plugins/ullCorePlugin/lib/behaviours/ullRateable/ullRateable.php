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

  public function setUserRating($rating = 3)
  {
    if (!is_int($rating) || $rating < 1 || $rating > $this->getOption('max_rating'))
    {
      throw new InvalidArgumentException(
        'Rating must be an integer between 1 and ' . $this->getOption('max_rating'));
    }

    return $this->_plugin->setUserRating($this->getInvoker(), 2, $rating);
  }
}