<?php

/**
 * ullFlaggable is a behavior for Doctrine which allows
 * ullUsers to activate/deactivate/remove multiple flags
 * on a specific record.
 */
class ullFlaggable extends Doctrine_Template
{
  /**
   * Returns a new instance of this behavior, and
   * checks the 'flags' option
   *
   * @param $options array of options
   * @return ullFlaggable instance of this behavior
   */
  public function __construct(array $options = array())
  {
    //flags option is required
    if (empty($options['flags']))
    {
      throw new InvalidArgumentException("ullFlaggable: 'flags' option must be set (single value or array)");
    }
    
    //make it an array for easier handling later on
    if (!is_array($options['flags']))
    {
      $options['flags'] = array($options['flags']);
    }
    
    //make sure we can use the flag names as column names
    foreach($options['flags'] as $flag)
    {
      if (empty($flag) || strlen($flag) > 27) //27 = 32 - 'flag_'
      {
        throw new LengthException("ullFlaggable: flag '$flag' is empty or too long");
      }
    }
    
    parent::__construct($options);

    $this->_plugin = new ullFlaggableRecordGenerator($options);
  }

  /**
   * Initializes this behavior
   */
  public function setUp()
  {
    $this->_plugin->initialize($this->_table);
  }

  /**
   * Returns the state of a specific flag for
   * the currently logged-in user.
   * @param string $flag the name of the flag which should be retrieved
   * @return boolean true, false or null
   */
  public function getFlag($flag)
  {
    $this->checkIfFlagNameIsValid($flag);
    $userId = ullFlaggable::retrieveLoggedInUserId();
    
    return $this->_plugin->getFlag($this->getInvoker(), $userId, $flag);
  }
  
  /**
   * Sets the state of a specific flag for
   * the currently logged-in user to
   * either true, false or null.
   * 
   * @param $flag the name of the flag which should be set
   * @param $value true, false or null
   * @return boolean true
   */
  public function setFlag($flag, $value = true)
  {
    if (!is_bool($value) && $value !== null)
    {
      throw new InvalidArgumentException("'$value' is not a valid flag value (true/false/null)");
    }
    
    $this->checkIfFlagNameIsValid($flag);
    $userId = ullFlaggable::retrieveLoggedInUserId();
    
    return $this->_plugin->setFlag($this->getInvoker(), $userId, $flag, $value);
  }
  
  /**
   * Modifies a Doctrine_Query so that it selects an additional
   * column containing the state of a specific flag for
   * the currently logged-in user.
   * 
   * @param Doctrine_Query $q the query to modify
   * @param string $flag the flag name to select
   * @return Doctrine_Query the query object
   */
  public static function addFlagQueryPart(Doctrine_Query $q, $flag)
  {
    //not possible, since this method needs to be static
    //should we do additional security checks for $flag?
    //
    //$this->checkIfFlagNameIsValid($flag);
    
    $userId = ullFlaggable::retrieveLoggedInUserId();
    
    $q
      ->leftJoin('x.Flags as flags WITH flags.flagger_ull_user_id = ?', $userId)
      ->addSelect('flags.flag_' . $flag . ' as ' . 'user_flag_' . $flag)
      //injection checking? why does addSelect not accept parameters?
    ;
    
    return $q;
  }

  /**
   * Checks if a given flag name is valid for
   * this record.
   * 
   * @param string $flag the name of the flag
   * @return boolean true or false
   */
  public function hasFlag($flag)
  {
    try
    {
      $this->checkIfFlagNameIsValid($flag);
      return true;
    }
    catch (InvalidArgumentException $e)
    {
      return false;
    }
  }
  
  /**
   * Internal function for flag name checking.
   * 
   * @param string $flag the flag name
   */
  protected function checkIfFlagNameIsValid($flag)
  {
    $flags = $this->getOption('flags');

    if (!in_array($flag, $flags, true)) //strict checking, or else 'true' would be ok too, etc.
    {
      throw new InvalidArgumentException('Flag ' . $flag .
        ' does not exist on ' . $this->getInvoker()->getTable()->getComponentName());
    }
  }
  
  /**
   * Internal function which retrieves the id of the currently
   * logged-in user, or throws an exception if that is not possible.
   * 
   * @return string id of the currently logged-in user
   */
  protected static function retrieveLoggedInUserId()
  {
    if ($userId = sfContext::getInstance()->getUser()->getAttribute('user_id'))
    {
      return $userId;
    }
    else
    {
      throw new UnexpectedValueException('This ullFlaggable action can only be executed by a logged in user');
    }
  }
}