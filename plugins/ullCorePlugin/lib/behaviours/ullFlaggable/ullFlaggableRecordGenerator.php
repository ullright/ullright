<?php

/**
 * ullFlaggableRecordGenerator is a record generator used by
 * the ullFlaggable behavior. It creates a separate table
 * for a model, allowing for the storage of multiple flags
 * per user.
 */
class ullFlaggableRecordGenerator extends Doctrine_Record_Generator
{
  protected $_options = array(
    'className' => '%CLASS%Flaggable',
    'flags'  => array('unnamed_flag'),

    //we need the following two set
    'generateFiles' => false,
    'children'      => array(), 
  );

  /**
   * Returns a new instance of this record generator.
   *
   * @param array $options
   * @return ullFlaggableRecordGenerator the new instance
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
    //this allows us to access flags from the owning model,
    $this->buildForeignRelation('Flags');

    //this allows us to access the object this flag belongs to
    $this->buildLocalRelation('FlaggedObject');
  }

  /**
   * Internal function called by Doctrine; responsible
   * for defining the table used to store comments.
   * The primary key is specified as the combination of the
   * id of the owning model and the id of the user the flags
   * belong to.
   * In addition, there is one column for each flag.
   */
  public function setTableDefinition()
  {
    $tableName = $this->_options['table']->getTableName();
    $this->setTableName($tableName . '_flags');

    $this->hasColumn('flagger_ull_user_id', 'integer', 8, array('primary' => true));

    $flags = $this->getOption('flags');

    foreach ($flags as $flag)
    {
      $this->hasColumn('flag_' . $flag, 'boolean');
    }

    //this also results in an additional foreign key (the commenter_ull_user_id column)
    $this->hasOne('UllUser as Flagger', array('local' => 'flagger_ull_user_id', 'foreign' => 'id'));
  }

  /**
   * Retrives a specific flag for a given ullUser's id and
   * a given record from the database.
   * Returns true, false or null.
   *
   * @param Doctrine_Record $invoker record the rating should be retrieved for
   * @param unknown_type $ullUserId ullUser's id the flag should be retrieved for
   * @param string $flag the flag which should be retrieved
   * @return boolean the state of the flag or null if not set
   */
  public function getFlag(Doctrine_Record $invoker, $ullUserId, $flag)
  {
    $q = Doctrine_Query::create()
      ->select('flags.flag_' . $flag)
      ->from($this->getOption('className') . ' as flags')
      ->where('flags.id = ?', $invoker->id)
      ->andWhere('flags.flagger_ull_user_id = ?', $ullUserId)
    ;

    $result = $q->fetchOne();
    return $result['flag_' . $flag];
  }

  /**
   * Sets a specific flag for a given ullUser's id and a given
   * record in the database.
   * A flag can be set to null (i.e. removed)
   *
   * @param Doctrine_Record $invoker record the flag should be set for
   * @param int $ullUserId ullUser's id the flag should be set for
   * @param string $flag the flag which should be set
   * @param boolean $value true or false or null
   * @return boolean true
   */
  public function setFlag(Doctrine_Record $invoker, $ullUserId, $flag, $value = true)
  {
    //we first retrieve the current flag record for the user
    $q = Doctrine_Query::create()
      ->from($this->getOption('className') . ' as flags')
      ->where('flags.id = ?', $invoker->id)
      ->andWhere('flags.flagger_ull_user_id = ?', $ullUserId)
    ;

    $flagObject = $q->fetchOne();

    //if there is none, create one
    if (!$flagObject)
    {
      $className = $this->getOption('className');
      $flagObject = new $className;
      $flagObject['id'] = $invoker->id;
      $flagObject['flagger_ull_user_id'] = $ullUserId;
    }

    //update the flag
    $flagObject->merge(array('flag_' . $flag => $value));
    $flagObject->save();
     
    return true;
  }
}
