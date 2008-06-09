<?php

/**
 * Base class that represents a row from the 'ull_flow_doc' table.
 *
 * 
 *
 * @package    plugins.ullFlowPlugin.lib.model.om
 */
abstract class BaseUllFlowDoc extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        UllFlowDocPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;


	/**
	 * The value for the ull_flow_app_id field.
	 * @var        int
	 */
	protected $ull_flow_app_id;


	/**
	 * The value for the title field.
	 * @var        string
	 */
	protected $title;


	/**
	 * The value for the ull_flow_action_id field.
	 * @var        int
	 */
	protected $ull_flow_action_id;


	/**
	 * The value for the assigned_to_ull_user_id field.
	 * @var        int
	 */
	protected $assigned_to_ull_user_id;


	/**
	 * The value for the assigned_to_ull_group_id field.
	 * @var        int
	 */
	protected $assigned_to_ull_group_id;


	/**
	 * The value for the assigned_to_ull_flow_step_id field.
	 * @var        int
	 */
	protected $assigned_to_ull_flow_step_id;


	/**
	 * The value for the priority field.
	 * @var        int
	 */
	protected $priority;


	/**
	 * The value for the deadline field.
	 * @var        int
	 */
	protected $deadline;


	/**
	 * The value for the custom_field1 field.
	 * @var        string
	 */
	protected $custom_field1;


	/**
	 * The value for the read_ull_group_id field.
	 * @var        int
	 */
	protected $read_ull_group_id;


	/**
	 * The value for the write_ull_group_id field.
	 * @var        int
	 */
	protected $write_ull_group_id;


	/**
	 * The value for the creator_user_id field.
	 * @var        int
	 */
	protected $creator_user_id;


	/**
	 * The value for the created_at field.
	 * @var        int
	 */
	protected $created_at;


	/**
	 * The value for the updator_user_id field.
	 * @var        int
	 */
	protected $updator_user_id;


	/**
	 * The value for the updated_at field.
	 * @var        int
	 */
	protected $updated_at;

	/**
	 * @var        UllFlowApp
	 */
	protected $aUllFlowApp;

	/**
	 * @var        UllFlowAction
	 */
	protected $aUllFlowAction;

	/**
	 * Collection to store aggregation of collUllFlowMemorys.
	 * @var        array
	 */
	protected $collUllFlowMemorys;

	/**
	 * The criteria used to select the current contents of collUllFlowMemorys.
	 * @var        Criteria
	 */
	protected $lastUllFlowMemoryCriteria = null;

	/**
	 * Collection to store aggregation of collUllFlowValues.
	 * @var        array
	 */
	protected $collUllFlowValues;

	/**
	 * The criteria used to select the current contents of collUllFlowValues.
	 * @var        Criteria
	 */
	protected $lastUllFlowValueCriteria = null;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	/**
	 * Get the [id] column value.
	 * 
	 * @return     int
	 */
	public function getId()
	{

		return $this->id;
	}

	/**
	 * Get the [ull_flow_app_id] column value.
	 * 
	 * @return     int
	 */
	public function getUllFlowAppId()
	{

		return $this->ull_flow_app_id;
	}

	/**
	 * Get the [title] column value.
	 * 
	 * @return     string
	 */
	public function getTitle()
	{

		return $this->title;
	}

	/**
	 * Get the [ull_flow_action_id] column value.
	 * 
	 * @return     int
	 */
	public function getUllFlowActionId()
	{

		return $this->ull_flow_action_id;
	}

	/**
	 * Get the [assigned_to_ull_user_id] column value.
	 * 
	 * @return     int
	 */
	public function getAssignedToUllUserId()
	{

		return $this->assigned_to_ull_user_id;
	}

	/**
	 * Get the [assigned_to_ull_group_id] column value.
	 * 
	 * @return     int
	 */
	public function getAssignedToUllGroupId()
	{

		return $this->assigned_to_ull_group_id;
	}

	/**
	 * Get the [assigned_to_ull_flow_step_id] column value.
	 * 
	 * @return     int
	 */
	public function getAssignedToUllFlowStepId()
	{

		return $this->assigned_to_ull_flow_step_id;
	}

	/**
	 * Get the [priority] column value.
	 * 
	 * @return     int
	 */
	public function getPriority()
	{

		return $this->priority;
	}

	/**
	 * Get the [optionally formatted] [deadline] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getDeadline($format = 'Y-m-d H:i:s')
	{

		if ($this->deadline === null || $this->deadline === '') {
			return null;
		} elseif (!is_int($this->deadline)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->deadline);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [deadline] as date/time value: " . var_export($this->deadline, true));
			}
		} else {
			$ts = $this->deadline;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	/**
	 * Get the [custom_field1] column value.
	 * 
	 * @return     string
	 */
	public function getCustomField1()
	{

		return $this->custom_field1;
	}

	/**
	 * Get the [read_ull_group_id] column value.
	 * 
	 * @return     int
	 */
	public function getReadUllGroupId()
	{

		return $this->read_ull_group_id;
	}

	/**
	 * Get the [write_ull_group_id] column value.
	 * 
	 * @return     int
	 */
	public function getWriteUllGroupId()
	{

		return $this->write_ull_group_id;
	}

	/**
	 * Get the [creator_user_id] column value.
	 * 
	 * @return     int
	 */
	public function getCreatorUserId()
	{

		return $this->creator_user_id;
	}

	/**
	 * Get the [optionally formatted] [created_at] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getCreatedAt($format = 'Y-m-d H:i:s')
	{

		if ($this->created_at === null || $this->created_at === '') {
			return null;
		} elseif (!is_int($this->created_at)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->created_at);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [created_at] as date/time value: " . var_export($this->created_at, true));
			}
		} else {
			$ts = $this->created_at;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	/**
	 * Get the [updator_user_id] column value.
	 * 
	 * @return     int
	 */
	public function getUpdatorUserId()
	{

		return $this->updator_user_id;
	}

	/**
	 * Get the [optionally formatted] [updated_at] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getUpdatedAt($format = 'Y-m-d H:i:s')
	{

		if ($this->updated_at === null || $this->updated_at === '') {
			return null;
		} elseif (!is_int($this->updated_at)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->updated_at);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [updated_at] as date/time value: " . var_export($this->updated_at, true));
			}
		} else {
			$ts = $this->updated_at;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = UllFlowDocPeer::ID;
		}

	} // setId()

	/**
	 * Set the value of [ull_flow_app_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setUllFlowAppId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->ull_flow_app_id !== $v) {
			$this->ull_flow_app_id = $v;
			$this->modifiedColumns[] = UllFlowDocPeer::ULL_FLOW_APP_ID;
		}

		if ($this->aUllFlowApp !== null && $this->aUllFlowApp->getId() !== $v) {
			$this->aUllFlowApp = null;
		}

	} // setUllFlowAppId()

	/**
	 * Set the value of [title] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setTitle($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->title !== $v) {
			$this->title = $v;
			$this->modifiedColumns[] = UllFlowDocPeer::TITLE;
		}

	} // setTitle()

	/**
	 * Set the value of [ull_flow_action_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setUllFlowActionId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->ull_flow_action_id !== $v) {
			$this->ull_flow_action_id = $v;
			$this->modifiedColumns[] = UllFlowDocPeer::ULL_FLOW_ACTION_ID;
		}

		if ($this->aUllFlowAction !== null && $this->aUllFlowAction->getId() !== $v) {
			$this->aUllFlowAction = null;
		}

	} // setUllFlowActionId()

	/**
	 * Set the value of [assigned_to_ull_user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setAssignedToUllUserId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->assigned_to_ull_user_id !== $v) {
			$this->assigned_to_ull_user_id = $v;
			$this->modifiedColumns[] = UllFlowDocPeer::ASSIGNED_TO_ULL_USER_ID;
		}

	} // setAssignedToUllUserId()

	/**
	 * Set the value of [assigned_to_ull_group_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setAssignedToUllGroupId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->assigned_to_ull_group_id !== $v) {
			$this->assigned_to_ull_group_id = $v;
			$this->modifiedColumns[] = UllFlowDocPeer::ASSIGNED_TO_ULL_GROUP_ID;
		}

	} // setAssignedToUllGroupId()

	/**
	 * Set the value of [assigned_to_ull_flow_step_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setAssignedToUllFlowStepId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->assigned_to_ull_flow_step_id !== $v) {
			$this->assigned_to_ull_flow_step_id = $v;
			$this->modifiedColumns[] = UllFlowDocPeer::ASSIGNED_TO_ULL_FLOW_STEP_ID;
		}

	} // setAssignedToUllFlowStepId()

	/**
	 * Set the value of [priority] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setPriority($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->priority !== $v) {
			$this->priority = $v;
			$this->modifiedColumns[] = UllFlowDocPeer::PRIORITY;
		}

	} // setPriority()

	/**
	 * Set the value of [deadline] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setDeadline($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [deadline] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->deadline !== $ts) {
			$this->deadline = $ts;
			$this->modifiedColumns[] = UllFlowDocPeer::DEADLINE;
		}

	} // setDeadline()

	/**
	 * Set the value of [custom_field1] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setCustomField1($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->custom_field1 !== $v) {
			$this->custom_field1 = $v;
			$this->modifiedColumns[] = UllFlowDocPeer::CUSTOM_FIELD1;
		}

	} // setCustomField1()

	/**
	 * Set the value of [read_ull_group_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setReadUllGroupId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->read_ull_group_id !== $v) {
			$this->read_ull_group_id = $v;
			$this->modifiedColumns[] = UllFlowDocPeer::READ_ULL_GROUP_ID;
		}

	} // setReadUllGroupId()

	/**
	 * Set the value of [write_ull_group_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setWriteUllGroupId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->write_ull_group_id !== $v) {
			$this->write_ull_group_id = $v;
			$this->modifiedColumns[] = UllFlowDocPeer::WRITE_ULL_GROUP_ID;
		}

	} // setWriteUllGroupId()

	/**
	 * Set the value of [creator_user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setCreatorUserId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->creator_user_id !== $v) {
			$this->creator_user_id = $v;
			$this->modifiedColumns[] = UllFlowDocPeer::CREATOR_USER_ID;
		}

	} // setCreatorUserId()

	/**
	 * Set the value of [created_at] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setCreatedAt($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [created_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->created_at !== $ts) {
			$this->created_at = $ts;
			$this->modifiedColumns[] = UllFlowDocPeer::CREATED_AT;
		}

	} // setCreatedAt()

	/**
	 * Set the value of [updator_user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setUpdatorUserId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->updator_user_id !== $v) {
			$this->updator_user_id = $v;
			$this->modifiedColumns[] = UllFlowDocPeer::UPDATOR_USER_ID;
		}

	} // setUpdatorUserId()

	/**
	 * Set the value of [updated_at] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setUpdatedAt($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [updated_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->updated_at !== $ts) {
			$this->updated_at = $ts;
			$this->modifiedColumns[] = UllFlowDocPeer::UPDATED_AT;
		}

	} // setUpdatedAt()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (1-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      ResultSet $rs The ResultSet class with cursor advanced to desired record pos.
	 * @param      int $startcol 1-based offset column which indicates which restultset column to start with.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->ull_flow_app_id = $rs->getInt($startcol + 1);

			$this->title = $rs->getString($startcol + 2);

			$this->ull_flow_action_id = $rs->getInt($startcol + 3);

			$this->assigned_to_ull_user_id = $rs->getInt($startcol + 4);

			$this->assigned_to_ull_group_id = $rs->getInt($startcol + 5);

			$this->assigned_to_ull_flow_step_id = $rs->getInt($startcol + 6);

			$this->priority = $rs->getInt($startcol + 7);

			$this->deadline = $rs->getTimestamp($startcol + 8, null);

			$this->custom_field1 = $rs->getString($startcol + 9);

			$this->read_ull_group_id = $rs->getInt($startcol + 10);

			$this->write_ull_group_id = $rs->getInt($startcol + 11);

			$this->creator_user_id = $rs->getInt($startcol + 12);

			$this->created_at = $rs->getTimestamp($startcol + 13, null);

			$this->updator_user_id = $rs->getInt($startcol + 14);

			$this->updated_at = $rs->getTimestamp($startcol + 15, null);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 16; // 16 = UllFlowDocPeer::NUM_COLUMNS - UllFlowDocPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating UllFlowDoc object", $e);
		}
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      Connection $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete($con = null)
	{

    foreach (sfMixer::getCallables('BaseUllFlowDoc:delete:pre') as $callable)
    {
      $ret = call_user_func($callable, $this, $con);
      if ($ret)
      {
        return;
      }
    }


		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(UllFlowDocPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			UllFlowDocPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseUllFlowDoc:delete:post') as $callable)
    {
      call_user_func($callable, $this, $con);
    }

  }
	/**
	 * Stores the object in the database.  If the object is new,
	 * it inserts it; otherwise an update is performed.  This method
	 * wraps the doSave() worker method in a transaction.
	 *
	 * @param      Connection $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save($con = null)
	{

    foreach (sfMixer::getCallables('BaseUllFlowDoc:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(UllFlowDocPeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

    if ($this->isModified() && !$this->isColumnModified(UllFlowDocPeer::UPDATED_AT))
    {
      $this->setUpdatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(UllFlowDocPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseUllFlowDoc:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Stores the object in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      Connection $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave($con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aUllFlowApp !== null) {
				if ($this->aUllFlowApp->isModified() || $this->aUllFlowApp->getCurrentUllFlowAppI18n()->isModified()) {
					$affectedRows += $this->aUllFlowApp->save($con);
				}
				$this->setUllFlowApp($this->aUllFlowApp);
			}

			if ($this->aUllFlowAction !== null) {
				if ($this->aUllFlowAction->isModified() || $this->aUllFlowAction->getCurrentUllFlowActionI18n()->isModified()) {
					$affectedRows += $this->aUllFlowAction->save($con);
				}
				$this->setUllFlowAction($this->aUllFlowAction);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = UllFlowDocPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += UllFlowDocPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collUllFlowMemorys !== null) {
				foreach($this->collUllFlowMemorys as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collUllFlowValues !== null) {
				foreach($this->collUllFlowValues as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			$this->alreadyInSave = false;
		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aUllFlowApp !== null) {
				if (!$this->aUllFlowApp->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aUllFlowApp->getValidationFailures());
				}
			}

			if ($this->aUllFlowAction !== null) {
				if (!$this->aUllFlowAction->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aUllFlowAction->getValidationFailures());
				}
			}


			if (($retval = UllFlowDocPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collUllFlowMemorys !== null) {
					foreach($this->collUllFlowMemorys as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collUllFlowValues !== null) {
					foreach($this->collUllFlowValues as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants TYPE_PHPNAME,
	 *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = UllFlowDocPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getUllFlowAppId();
				break;
			case 2:
				return $this->getTitle();
				break;
			case 3:
				return $this->getUllFlowActionId();
				break;
			case 4:
				return $this->getAssignedToUllUserId();
				break;
			case 5:
				return $this->getAssignedToUllGroupId();
				break;
			case 6:
				return $this->getAssignedToUllFlowStepId();
				break;
			case 7:
				return $this->getPriority();
				break;
			case 8:
				return $this->getDeadline();
				break;
			case 9:
				return $this->getCustomField1();
				break;
			case 10:
				return $this->getReadUllGroupId();
				break;
			case 11:
				return $this->getWriteUllGroupId();
				break;
			case 12:
				return $this->getCreatorUserId();
				break;
			case 13:
				return $this->getCreatedAt();
				break;
			case 14:
				return $this->getUpdatorUserId();
				break;
			case 15:
				return $this->getUpdatedAt();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param      string $keyType One of the class type constants TYPE_PHPNAME,
	 *                        TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = UllFlowDocPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getUllFlowAppId(),
			$keys[2] => $this->getTitle(),
			$keys[3] => $this->getUllFlowActionId(),
			$keys[4] => $this->getAssignedToUllUserId(),
			$keys[5] => $this->getAssignedToUllGroupId(),
			$keys[6] => $this->getAssignedToUllFlowStepId(),
			$keys[7] => $this->getPriority(),
			$keys[8] => $this->getDeadline(),
			$keys[9] => $this->getCustomField1(),
			$keys[10] => $this->getReadUllGroupId(),
			$keys[11] => $this->getWriteUllGroupId(),
			$keys[12] => $this->getCreatorUserId(),
			$keys[13] => $this->getCreatedAt(),
			$keys[14] => $this->getUpdatorUserId(),
			$keys[15] => $this->getUpdatedAt(),
		);
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants TYPE_PHPNAME,
	 *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = UllFlowDocPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setUllFlowAppId($value);
				break;
			case 2:
				$this->setTitle($value);
				break;
			case 3:
				$this->setUllFlowActionId($value);
				break;
			case 4:
				$this->setAssignedToUllUserId($value);
				break;
			case 5:
				$this->setAssignedToUllGroupId($value);
				break;
			case 6:
				$this->setAssignedToUllFlowStepId($value);
				break;
			case 7:
				$this->setPriority($value);
				break;
			case 8:
				$this->setDeadline($value);
				break;
			case 9:
				$this->setCustomField1($value);
				break;
			case 10:
				$this->setReadUllGroupId($value);
				break;
			case 11:
				$this->setWriteUllGroupId($value);
				break;
			case 12:
				$this->setCreatorUserId($value);
				break;
			case 13:
				$this->setCreatedAt($value);
				break;
			case 14:
				$this->setUpdatorUserId($value);
				break;
			case 15:
				$this->setUpdatedAt($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME,
	 * TYPE_NUM. The default key type is the column's phpname (e.g. 'authorId')
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = UllFlowDocPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setUllFlowAppId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setTitle($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setUllFlowActionId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setAssignedToUllUserId($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setAssignedToUllGroupId($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setAssignedToUllFlowStepId($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setPriority($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setDeadline($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setCustomField1($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setReadUllGroupId($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setWriteUllGroupId($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setCreatorUserId($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setCreatedAt($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setUpdatorUserId($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setUpdatedAt($arr[$keys[15]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(UllFlowDocPeer::DATABASE_NAME);

		if ($this->isColumnModified(UllFlowDocPeer::ID)) $criteria->add(UllFlowDocPeer::ID, $this->id);
		if ($this->isColumnModified(UllFlowDocPeer::ULL_FLOW_APP_ID)) $criteria->add(UllFlowDocPeer::ULL_FLOW_APP_ID, $this->ull_flow_app_id);
		if ($this->isColumnModified(UllFlowDocPeer::TITLE)) $criteria->add(UllFlowDocPeer::TITLE, $this->title);
		if ($this->isColumnModified(UllFlowDocPeer::ULL_FLOW_ACTION_ID)) $criteria->add(UllFlowDocPeer::ULL_FLOW_ACTION_ID, $this->ull_flow_action_id);
		if ($this->isColumnModified(UllFlowDocPeer::ASSIGNED_TO_ULL_USER_ID)) $criteria->add(UllFlowDocPeer::ASSIGNED_TO_ULL_USER_ID, $this->assigned_to_ull_user_id);
		if ($this->isColumnModified(UllFlowDocPeer::ASSIGNED_TO_ULL_GROUP_ID)) $criteria->add(UllFlowDocPeer::ASSIGNED_TO_ULL_GROUP_ID, $this->assigned_to_ull_group_id);
		if ($this->isColumnModified(UllFlowDocPeer::ASSIGNED_TO_ULL_FLOW_STEP_ID)) $criteria->add(UllFlowDocPeer::ASSIGNED_TO_ULL_FLOW_STEP_ID, $this->assigned_to_ull_flow_step_id);
		if ($this->isColumnModified(UllFlowDocPeer::PRIORITY)) $criteria->add(UllFlowDocPeer::PRIORITY, $this->priority);
		if ($this->isColumnModified(UllFlowDocPeer::DEADLINE)) $criteria->add(UllFlowDocPeer::DEADLINE, $this->deadline);
		if ($this->isColumnModified(UllFlowDocPeer::CUSTOM_FIELD1)) $criteria->add(UllFlowDocPeer::CUSTOM_FIELD1, $this->custom_field1);
		if ($this->isColumnModified(UllFlowDocPeer::READ_ULL_GROUP_ID)) $criteria->add(UllFlowDocPeer::READ_ULL_GROUP_ID, $this->read_ull_group_id);
		if ($this->isColumnModified(UllFlowDocPeer::WRITE_ULL_GROUP_ID)) $criteria->add(UllFlowDocPeer::WRITE_ULL_GROUP_ID, $this->write_ull_group_id);
		if ($this->isColumnModified(UllFlowDocPeer::CREATOR_USER_ID)) $criteria->add(UllFlowDocPeer::CREATOR_USER_ID, $this->creator_user_id);
		if ($this->isColumnModified(UllFlowDocPeer::CREATED_AT)) $criteria->add(UllFlowDocPeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(UllFlowDocPeer::UPDATOR_USER_ID)) $criteria->add(UllFlowDocPeer::UPDATOR_USER_ID, $this->updator_user_id);
		if ($this->isColumnModified(UllFlowDocPeer::UPDATED_AT)) $criteria->add(UllFlowDocPeer::UPDATED_AT, $this->updated_at);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(UllFlowDocPeer::DATABASE_NAME);

		$criteria->add(UllFlowDocPeer::ID, $this->id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of UllFlowDoc (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setUllFlowAppId($this->ull_flow_app_id);

		$copyObj->setTitle($this->title);

		$copyObj->setUllFlowActionId($this->ull_flow_action_id);

		$copyObj->setAssignedToUllUserId($this->assigned_to_ull_user_id);

		$copyObj->setAssignedToUllGroupId($this->assigned_to_ull_group_id);

		$copyObj->setAssignedToUllFlowStepId($this->assigned_to_ull_flow_step_id);

		$copyObj->setPriority($this->priority);

		$copyObj->setDeadline($this->deadline);

		$copyObj->setCustomField1($this->custom_field1);

		$copyObj->setReadUllGroupId($this->read_ull_group_id);

		$copyObj->setWriteUllGroupId($this->write_ull_group_id);

		$copyObj->setCreatorUserId($this->creator_user_id);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setUpdatorUserId($this->updator_user_id);

		$copyObj->setUpdatedAt($this->updated_at);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach($this->getUllFlowMemorys() as $relObj) {
				$copyObj->addUllFlowMemory($relObj->copy($deepCopy));
			}

			foreach($this->getUllFlowValues() as $relObj) {
				$copyObj->addUllFlowValue($relObj->copy($deepCopy));
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setId(NULL); // this is a pkey column, so set to default value

	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     UllFlowDoc Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     UllFlowDocPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new UllFlowDocPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a UllFlowApp object.
	 *
	 * @param      UllFlowApp $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setUllFlowApp($v)
	{


		if ($v === null) {
			$this->setUllFlowAppId(NULL);
		} else {
			$this->setUllFlowAppId($v->getId());
		}


		$this->aUllFlowApp = $v;
	}


	/**
	 * Get the associated UllFlowApp object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     UllFlowApp The associated UllFlowApp object.
	 * @throws     PropelException
	 */
	public function getUllFlowApp($con = null)
	{
		if ($this->aUllFlowApp === null && ($this->ull_flow_app_id !== null)) {
			// include the related Peer class
			include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowAppPeer.php';

			$this->aUllFlowApp = UllFlowAppPeer::retrieveByPK($this->ull_flow_app_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = UllFlowAppPeer::retrieveByPK($this->ull_flow_app_id, $con);
			   $obj->addUllFlowApps($this);
			 */
		}
		return $this->aUllFlowApp;
	}

	/**
	 * Declares an association between this object and a UllFlowAction object.
	 *
	 * @param      UllFlowAction $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setUllFlowAction($v)
	{


		if ($v === null) {
			$this->setUllFlowActionId(NULL);
		} else {
			$this->setUllFlowActionId($v->getId());
		}


		$this->aUllFlowAction = $v;
	}


	/**
	 * Get the associated UllFlowAction object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     UllFlowAction The associated UllFlowAction object.
	 * @throws     PropelException
	 */
	public function getUllFlowAction($con = null)
	{
		if ($this->aUllFlowAction === null && ($this->ull_flow_action_id !== null)) {
			// include the related Peer class
			include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowActionPeer.php';

			$this->aUllFlowAction = UllFlowActionPeer::retrieveByPK($this->ull_flow_action_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = UllFlowActionPeer::retrieveByPK($this->ull_flow_action_id, $con);
			   $obj->addUllFlowActions($this);
			 */
		}
		return $this->aUllFlowAction;
	}

	/**
	 * Temporary storage of collUllFlowMemorys to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initUllFlowMemorys()
	{
		if ($this->collUllFlowMemorys === null) {
			$this->collUllFlowMemorys = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowDoc has previously
	 * been saved, it will retrieve related UllFlowMemorys from storage.
	 * If this UllFlowDoc is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getUllFlowMemorys($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowMemoryPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collUllFlowMemorys === null) {
			if ($this->isNew()) {
			   $this->collUllFlowMemorys = array();
			} else {

				$criteria->add(UllFlowMemoryPeer::ULL_FLOW_DOC_ID, $this->getId());

				UllFlowMemoryPeer::addSelectColumns($criteria);
				$this->collUllFlowMemorys = UllFlowMemoryPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(UllFlowMemoryPeer::ULL_FLOW_DOC_ID, $this->getId());

				UllFlowMemoryPeer::addSelectColumns($criteria);
				if (!isset($this->lastUllFlowMemoryCriteria) || !$this->lastUllFlowMemoryCriteria->equals($criteria)) {
					$this->collUllFlowMemorys = UllFlowMemoryPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastUllFlowMemoryCriteria = $criteria;
		return $this->collUllFlowMemorys;
	}

	/**
	 * Returns the number of related UllFlowMemorys.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countUllFlowMemorys($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowMemoryPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(UllFlowMemoryPeer::ULL_FLOW_DOC_ID, $this->getId());

		return UllFlowMemoryPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a UllFlowMemory object to this object
	 * through the UllFlowMemory foreign key attribute
	 *
	 * @param      UllFlowMemory $l UllFlowMemory
	 * @return     void
	 * @throws     PropelException
	 */
	public function addUllFlowMemory(UllFlowMemory $l)
	{
		$this->collUllFlowMemorys[] = $l;
		$l->setUllFlowDoc($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowDoc is new, it will return
	 * an empty collection; or if this UllFlowDoc has previously
	 * been saved, it will retrieve related UllFlowMemorys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in UllFlowDoc.
	 */
	public function getUllFlowMemorysJoinUllFlowStep($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowMemoryPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collUllFlowMemorys === null) {
			if ($this->isNew()) {
				$this->collUllFlowMemorys = array();
			} else {

				$criteria->add(UllFlowMemoryPeer::ULL_FLOW_DOC_ID, $this->getId());

				$this->collUllFlowMemorys = UllFlowMemoryPeer::doSelectJoinUllFlowStep($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(UllFlowMemoryPeer::ULL_FLOW_DOC_ID, $this->getId());

			if (!isset($this->lastUllFlowMemoryCriteria) || !$this->lastUllFlowMemoryCriteria->equals($criteria)) {
				$this->collUllFlowMemorys = UllFlowMemoryPeer::doSelectJoinUllFlowStep($criteria, $con);
			}
		}
		$this->lastUllFlowMemoryCriteria = $criteria;

		return $this->collUllFlowMemorys;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowDoc is new, it will return
	 * an empty collection; or if this UllFlowDoc has previously
	 * been saved, it will retrieve related UllFlowMemorys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in UllFlowDoc.
	 */
	public function getUllFlowMemorysJoinUllFlowAction($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowMemoryPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collUllFlowMemorys === null) {
			if ($this->isNew()) {
				$this->collUllFlowMemorys = array();
			} else {

				$criteria->add(UllFlowMemoryPeer::ULL_FLOW_DOC_ID, $this->getId());

				$this->collUllFlowMemorys = UllFlowMemoryPeer::doSelectJoinUllFlowAction($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(UllFlowMemoryPeer::ULL_FLOW_DOC_ID, $this->getId());

			if (!isset($this->lastUllFlowMemoryCriteria) || !$this->lastUllFlowMemoryCriteria->equals($criteria)) {
				$this->collUllFlowMemorys = UllFlowMemoryPeer::doSelectJoinUllFlowAction($criteria, $con);
			}
		}
		$this->lastUllFlowMemoryCriteria = $criteria;

		return $this->collUllFlowMemorys;
	}

	/**
	 * Temporary storage of collUllFlowValues to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initUllFlowValues()
	{
		if ($this->collUllFlowValues === null) {
			$this->collUllFlowValues = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowDoc has previously
	 * been saved, it will retrieve related UllFlowValues from storage.
	 * If this UllFlowDoc is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getUllFlowValues($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowValuePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collUllFlowValues === null) {
			if ($this->isNew()) {
			   $this->collUllFlowValues = array();
			} else {

				$criteria->add(UllFlowValuePeer::ULL_FLOW_DOC_ID, $this->getId());

				UllFlowValuePeer::addSelectColumns($criteria);
				$this->collUllFlowValues = UllFlowValuePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(UllFlowValuePeer::ULL_FLOW_DOC_ID, $this->getId());

				UllFlowValuePeer::addSelectColumns($criteria);
				if (!isset($this->lastUllFlowValueCriteria) || !$this->lastUllFlowValueCriteria->equals($criteria)) {
					$this->collUllFlowValues = UllFlowValuePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastUllFlowValueCriteria = $criteria;
		return $this->collUllFlowValues;
	}

	/**
	 * Returns the number of related UllFlowValues.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countUllFlowValues($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowValuePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(UllFlowValuePeer::ULL_FLOW_DOC_ID, $this->getId());

		return UllFlowValuePeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a UllFlowValue object to this object
	 * through the UllFlowValue foreign key attribute
	 *
	 * @param      UllFlowValue $l UllFlowValue
	 * @return     void
	 * @throws     PropelException
	 */
	public function addUllFlowValue(UllFlowValue $l)
	{
		$this->collUllFlowValues[] = $l;
		$l->setUllFlowDoc($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowDoc is new, it will return
	 * an empty collection; or if this UllFlowDoc has previously
	 * been saved, it will retrieve related UllFlowValues from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in UllFlowDoc.
	 */
	public function getUllFlowValuesJoinUllFlowField($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowValuePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collUllFlowValues === null) {
			if ($this->isNew()) {
				$this->collUllFlowValues = array();
			} else {

				$criteria->add(UllFlowValuePeer::ULL_FLOW_DOC_ID, $this->getId());

				$this->collUllFlowValues = UllFlowValuePeer::doSelectJoinUllFlowField($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(UllFlowValuePeer::ULL_FLOW_DOC_ID, $this->getId());

			if (!isset($this->lastUllFlowValueCriteria) || !$this->lastUllFlowValueCriteria->equals($criteria)) {
				$this->collUllFlowValues = UllFlowValuePeer::doSelectJoinUllFlowField($criteria, $con);
			}
		}
		$this->lastUllFlowValueCriteria = $criteria;

		return $this->collUllFlowValues;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowDoc is new, it will return
	 * an empty collection; or if this UllFlowDoc has previously
	 * been saved, it will retrieve related UllFlowValues from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in UllFlowDoc.
	 */
	public function getUllFlowValuesJoinUllFlowMemory($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowValuePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collUllFlowValues === null) {
			if ($this->isNew()) {
				$this->collUllFlowValues = array();
			} else {

				$criteria->add(UllFlowValuePeer::ULL_FLOW_DOC_ID, $this->getId());

				$this->collUllFlowValues = UllFlowValuePeer::doSelectJoinUllFlowMemory($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(UllFlowValuePeer::ULL_FLOW_DOC_ID, $this->getId());

			if (!isset($this->lastUllFlowValueCriteria) || !$this->lastUllFlowValueCriteria->equals($criteria)) {
				$this->collUllFlowValues = UllFlowValuePeer::doSelectJoinUllFlowMemory($criteria, $con);
			}
		}
		$this->lastUllFlowValueCriteria = $criteria;

		return $this->collUllFlowValues;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseUllFlowDoc:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseUllFlowDoc::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseUllFlowDoc
