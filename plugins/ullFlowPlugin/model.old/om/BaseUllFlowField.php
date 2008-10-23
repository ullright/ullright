<?php

/**
 * Base class that represents a row from the 'ull_flow_field' table.
 *
 * 
 *
 * @package    plugins.ullFlowPlugin.lib.model.om
 */
abstract class BaseUllFlowField extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        UllFlowFieldPeer
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
	 * The value for the ull_field_id field.
	 * @var        int
	 */
	protected $ull_field_id;


	/**
	 * The value for the options field.
	 * @var        string
	 */
	protected $options;


	/**
	 * The value for the caption_i18n_default field.
	 * @var        string
	 */
	protected $caption_i18n_default;


	/**
	 * The value for the sequence field.
	 * @var        int
	 */
	protected $sequence;


	/**
	 * The value for the default_value field.
	 * @var        int
	 */
	protected $default_value;


	/**
	 * The value for the enabled field.
	 * @var        boolean
	 */
	protected $enabled;


	/**
	 * The value for the mandatory field.
	 * @var        boolean
	 */
	protected $mandatory;


	/**
	 * The value for the is_title field.
	 * @var        boolean
	 */
	protected $is_title;


	/**
	 * The value for the is_priority field.
	 * @var        boolean
	 */
	protected $is_priority;


	/**
	 * The value for the is_deadline field.
	 * @var        boolean
	 */
	protected $is_deadline;


	/**
	 * The value for the is_custom_field1 field.
	 * @var        boolean
	 */
	protected $is_custom_field1;


	/**
	 * The value for the ull_access_group_id field.
	 * @var        int
	 */
	protected $ull_access_group_id;


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
	 * Collection to store aggregation of collUllFlowFieldI18ns.
	 * @var        array
	 */
	protected $collUllFlowFieldI18ns;

	/**
	 * The criteria used to select the current contents of collUllFlowFieldI18ns.
	 * @var        Criteria
	 */
	protected $lastUllFlowFieldI18nCriteria = null;

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
   * The value for the culture field.
   * @var string
   */
  protected $culture;

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
	 * Get the [ull_field_id] column value.
	 * 
	 * @return     int
	 */
	public function getUllFieldId()
	{

		return $this->ull_field_id;
	}

	/**
	 * Get the [options] column value.
	 * 
	 * @return     string
	 */
	public function getOptions()
	{

		return $this->options;
	}

	/**
	 * Get the [caption_i18n_default] column value.
	 * 
	 * @return     string
	 */
	public function getCaptionI18nDefault()
	{

		return $this->caption_i18n_default;
	}

	/**
	 * Get the [sequence] column value.
	 * 
	 * @return     int
	 */
	public function getSequence()
	{

		return $this->sequence;
	}

	/**
	 * Get the [default_value] column value.
	 * 
	 * @return     int
	 */
	public function getDefaultValue()
	{

		return $this->default_value;
	}

	/**
	 * Get the [enabled] column value.
	 * 
	 * @return     boolean
	 */
	public function getEnabled()
	{

		return $this->enabled;
	}

	/**
	 * Get the [mandatory] column value.
	 * 
	 * @return     boolean
	 */
	public function getMandatory()
	{

		return $this->mandatory;
	}

	/**
	 * Get the [is_title] column value.
	 * 
	 * @return     boolean
	 */
	public function getIsTitle()
	{

		return $this->is_title;
	}

	/**
	 * Get the [is_priority] column value.
	 * 
	 * @return     boolean
	 */
	public function getIsPriority()
	{

		return $this->is_priority;
	}

	/**
	 * Get the [is_deadline] column value.
	 * 
	 * @return     boolean
	 */
	public function getIsDeadline()
	{

		return $this->is_deadline;
	}

	/**
	 * Get the [is_custom_field1] column value.
	 * 
	 * @return     boolean
	 */
	public function getIsCustomField1()
	{

		return $this->is_custom_field1;
	}

	/**
	 * Get the [ull_access_group_id] column value.
	 * 
	 * @return     int
	 */
	public function getUllAccessGroupId()
	{

		return $this->ull_access_group_id;
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
			$this->modifiedColumns[] = UllFlowFieldPeer::ID;
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
			$this->modifiedColumns[] = UllFlowFieldPeer::ULL_FLOW_APP_ID;
		}

		if ($this->aUllFlowApp !== null && $this->aUllFlowApp->getId() !== $v) {
			$this->aUllFlowApp = null;
		}

	} // setUllFlowAppId()

	/**
	 * Set the value of [ull_field_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setUllFieldId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->ull_field_id !== $v) {
			$this->ull_field_id = $v;
			$this->modifiedColumns[] = UllFlowFieldPeer::ULL_FIELD_ID;
		}

	} // setUllFieldId()

	/**
	 * Set the value of [options] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setOptions($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->options !== $v) {
			$this->options = $v;
			$this->modifiedColumns[] = UllFlowFieldPeer::OPTIONS;
		}

	} // setOptions()

	/**
	 * Set the value of [caption_i18n_default] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setCaptionI18nDefault($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->caption_i18n_default !== $v) {
			$this->caption_i18n_default = $v;
			$this->modifiedColumns[] = UllFlowFieldPeer::CAPTION_I18N_DEFAULT;
		}

	} // setCaptionI18nDefault()

	/**
	 * Set the value of [sequence] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setSequence($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->sequence !== $v) {
			$this->sequence = $v;
			$this->modifiedColumns[] = UllFlowFieldPeer::SEQUENCE;
		}

	} // setSequence()

	/**
	 * Set the value of [default_value] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setDefaultValue($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->default_value !== $v) {
			$this->default_value = $v;
			$this->modifiedColumns[] = UllFlowFieldPeer::DEFAULT_VALUE;
		}

	} // setDefaultValue()

	/**
	 * Set the value of [enabled] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setEnabled($v)
	{

		if ($this->enabled !== $v) {
			$this->enabled = $v;
			$this->modifiedColumns[] = UllFlowFieldPeer::ENABLED;
		}

	} // setEnabled()

	/**
	 * Set the value of [mandatory] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setMandatory($v)
	{

		if ($this->mandatory !== $v) {
			$this->mandatory = $v;
			$this->modifiedColumns[] = UllFlowFieldPeer::MANDATORY;
		}

	} // setMandatory()

	/**
	 * Set the value of [is_title] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setIsTitle($v)
	{

		if ($this->is_title !== $v) {
			$this->is_title = $v;
			$this->modifiedColumns[] = UllFlowFieldPeer::IS_TITLE;
		}

	} // setIsTitle()

	/**
	 * Set the value of [is_priority] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setIsPriority($v)
	{

		if ($this->is_priority !== $v) {
			$this->is_priority = $v;
			$this->modifiedColumns[] = UllFlowFieldPeer::IS_PRIORITY;
		}

	} // setIsPriority()

	/**
	 * Set the value of [is_deadline] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setIsDeadline($v)
	{

		if ($this->is_deadline !== $v) {
			$this->is_deadline = $v;
			$this->modifiedColumns[] = UllFlowFieldPeer::IS_DEADLINE;
		}

	} // setIsDeadline()

	/**
	 * Set the value of [is_custom_field1] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setIsCustomField1($v)
	{

		if ($this->is_custom_field1 !== $v) {
			$this->is_custom_field1 = $v;
			$this->modifiedColumns[] = UllFlowFieldPeer::IS_CUSTOM_FIELD1;
		}

	} // setIsCustomField1()

	/**
	 * Set the value of [ull_access_group_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setUllAccessGroupId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->ull_access_group_id !== $v) {
			$this->ull_access_group_id = $v;
			$this->modifiedColumns[] = UllFlowFieldPeer::ULL_ACCESS_GROUP_ID;
		}

	} // setUllAccessGroupId()

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
			$this->modifiedColumns[] = UllFlowFieldPeer::CREATOR_USER_ID;
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
			$this->modifiedColumns[] = UllFlowFieldPeer::CREATED_AT;
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
			$this->modifiedColumns[] = UllFlowFieldPeer::UPDATOR_USER_ID;
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
			$this->modifiedColumns[] = UllFlowFieldPeer::UPDATED_AT;
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

			$this->ull_field_id = $rs->getInt($startcol + 2);

			$this->options = $rs->getString($startcol + 3);

			$this->caption_i18n_default = $rs->getString($startcol + 4);

			$this->sequence = $rs->getInt($startcol + 5);

			$this->default_value = $rs->getInt($startcol + 6);

			$this->enabled = $rs->getBoolean($startcol + 7);

			$this->mandatory = $rs->getBoolean($startcol + 8);

			$this->is_title = $rs->getBoolean($startcol + 9);

			$this->is_priority = $rs->getBoolean($startcol + 10);

			$this->is_deadline = $rs->getBoolean($startcol + 11);

			$this->is_custom_field1 = $rs->getBoolean($startcol + 12);

			$this->ull_access_group_id = $rs->getInt($startcol + 13);

			$this->creator_user_id = $rs->getInt($startcol + 14);

			$this->created_at = $rs->getTimestamp($startcol + 15, null);

			$this->updator_user_id = $rs->getInt($startcol + 16);

			$this->updated_at = $rs->getTimestamp($startcol + 17, null);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 18; // 18 = UllFlowFieldPeer::NUM_COLUMNS - UllFlowFieldPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating UllFlowField object", $e);
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

    foreach (sfMixer::getCallables('BaseUllFlowField:delete:pre') as $callable)
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
			$con = Propel::getConnection(UllFlowFieldPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			UllFlowFieldPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseUllFlowField:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseUllFlowField:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(UllFlowFieldPeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

    if ($this->isModified() && !$this->isColumnModified(UllFlowFieldPeer::UPDATED_AT))
    {
      $this->setUpdatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(UllFlowFieldPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseUllFlowField:save:post') as $callable)
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


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = UllFlowFieldPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += UllFlowFieldPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collUllFlowFieldI18ns !== null) {
				foreach($this->collUllFlowFieldI18ns as $referrerFK) {
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


			if (($retval = UllFlowFieldPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collUllFlowFieldI18ns !== null) {
					foreach($this->collUllFlowFieldI18ns as $referrerFK) {
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
		$pos = UllFlowFieldPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getUllFieldId();
				break;
			case 3:
				return $this->getOptions();
				break;
			case 4:
				return $this->getCaptionI18nDefault();
				break;
			case 5:
				return $this->getSequence();
				break;
			case 6:
				return $this->getDefaultValue();
				break;
			case 7:
				return $this->getEnabled();
				break;
			case 8:
				return $this->getMandatory();
				break;
			case 9:
				return $this->getIsTitle();
				break;
			case 10:
				return $this->getIsPriority();
				break;
			case 11:
				return $this->getIsDeadline();
				break;
			case 12:
				return $this->getIsCustomField1();
				break;
			case 13:
				return $this->getUllAccessGroupId();
				break;
			case 14:
				return $this->getCreatorUserId();
				break;
			case 15:
				return $this->getCreatedAt();
				break;
			case 16:
				return $this->getUpdatorUserId();
				break;
			case 17:
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
		$keys = UllFlowFieldPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getUllFlowAppId(),
			$keys[2] => $this->getUllFieldId(),
			$keys[3] => $this->getOptions(),
			$keys[4] => $this->getCaptionI18nDefault(),
			$keys[5] => $this->getSequence(),
			$keys[6] => $this->getDefaultValue(),
			$keys[7] => $this->getEnabled(),
			$keys[8] => $this->getMandatory(),
			$keys[9] => $this->getIsTitle(),
			$keys[10] => $this->getIsPriority(),
			$keys[11] => $this->getIsDeadline(),
			$keys[12] => $this->getIsCustomField1(),
			$keys[13] => $this->getUllAccessGroupId(),
			$keys[14] => $this->getCreatorUserId(),
			$keys[15] => $this->getCreatedAt(),
			$keys[16] => $this->getUpdatorUserId(),
			$keys[17] => $this->getUpdatedAt(),
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
		$pos = UllFlowFieldPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setUllFieldId($value);
				break;
			case 3:
				$this->setOptions($value);
				break;
			case 4:
				$this->setCaptionI18nDefault($value);
				break;
			case 5:
				$this->setSequence($value);
				break;
			case 6:
				$this->setDefaultValue($value);
				break;
			case 7:
				$this->setEnabled($value);
				break;
			case 8:
				$this->setMandatory($value);
				break;
			case 9:
				$this->setIsTitle($value);
				break;
			case 10:
				$this->setIsPriority($value);
				break;
			case 11:
				$this->setIsDeadline($value);
				break;
			case 12:
				$this->setIsCustomField1($value);
				break;
			case 13:
				$this->setUllAccessGroupId($value);
				break;
			case 14:
				$this->setCreatorUserId($value);
				break;
			case 15:
				$this->setCreatedAt($value);
				break;
			case 16:
				$this->setUpdatorUserId($value);
				break;
			case 17:
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
		$keys = UllFlowFieldPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setUllFlowAppId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setUllFieldId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setOptions($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setCaptionI18nDefault($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setSequence($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setDefaultValue($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setEnabled($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setMandatory($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setIsTitle($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setIsPriority($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setIsDeadline($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setIsCustomField1($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setUllAccessGroupId($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setCreatorUserId($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setCreatedAt($arr[$keys[15]]);
		if (array_key_exists($keys[16], $arr)) $this->setUpdatorUserId($arr[$keys[16]]);
		if (array_key_exists($keys[17], $arr)) $this->setUpdatedAt($arr[$keys[17]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(UllFlowFieldPeer::DATABASE_NAME);

		if ($this->isColumnModified(UllFlowFieldPeer::ID)) $criteria->add(UllFlowFieldPeer::ID, $this->id);
		if ($this->isColumnModified(UllFlowFieldPeer::ULL_FLOW_APP_ID)) $criteria->add(UllFlowFieldPeer::ULL_FLOW_APP_ID, $this->ull_flow_app_id);
		if ($this->isColumnModified(UllFlowFieldPeer::ULL_FIELD_ID)) $criteria->add(UllFlowFieldPeer::ULL_FIELD_ID, $this->ull_field_id);
		if ($this->isColumnModified(UllFlowFieldPeer::OPTIONS)) $criteria->add(UllFlowFieldPeer::OPTIONS, $this->options);
		if ($this->isColumnModified(UllFlowFieldPeer::CAPTION_I18N_DEFAULT)) $criteria->add(UllFlowFieldPeer::CAPTION_I18N_DEFAULT, $this->caption_i18n_default);
		if ($this->isColumnModified(UllFlowFieldPeer::SEQUENCE)) $criteria->add(UllFlowFieldPeer::SEQUENCE, $this->sequence);
		if ($this->isColumnModified(UllFlowFieldPeer::DEFAULT_VALUE)) $criteria->add(UllFlowFieldPeer::DEFAULT_VALUE, $this->default_value);
		if ($this->isColumnModified(UllFlowFieldPeer::ENABLED)) $criteria->add(UllFlowFieldPeer::ENABLED, $this->enabled);
		if ($this->isColumnModified(UllFlowFieldPeer::MANDATORY)) $criteria->add(UllFlowFieldPeer::MANDATORY, $this->mandatory);
		if ($this->isColumnModified(UllFlowFieldPeer::IS_TITLE)) $criteria->add(UllFlowFieldPeer::IS_TITLE, $this->is_title);
		if ($this->isColumnModified(UllFlowFieldPeer::IS_PRIORITY)) $criteria->add(UllFlowFieldPeer::IS_PRIORITY, $this->is_priority);
		if ($this->isColumnModified(UllFlowFieldPeer::IS_DEADLINE)) $criteria->add(UllFlowFieldPeer::IS_DEADLINE, $this->is_deadline);
		if ($this->isColumnModified(UllFlowFieldPeer::IS_CUSTOM_FIELD1)) $criteria->add(UllFlowFieldPeer::IS_CUSTOM_FIELD1, $this->is_custom_field1);
		if ($this->isColumnModified(UllFlowFieldPeer::ULL_ACCESS_GROUP_ID)) $criteria->add(UllFlowFieldPeer::ULL_ACCESS_GROUP_ID, $this->ull_access_group_id);
		if ($this->isColumnModified(UllFlowFieldPeer::CREATOR_USER_ID)) $criteria->add(UllFlowFieldPeer::CREATOR_USER_ID, $this->creator_user_id);
		if ($this->isColumnModified(UllFlowFieldPeer::CREATED_AT)) $criteria->add(UllFlowFieldPeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(UllFlowFieldPeer::UPDATOR_USER_ID)) $criteria->add(UllFlowFieldPeer::UPDATOR_USER_ID, $this->updator_user_id);
		if ($this->isColumnModified(UllFlowFieldPeer::UPDATED_AT)) $criteria->add(UllFlowFieldPeer::UPDATED_AT, $this->updated_at);

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
		$criteria = new Criteria(UllFlowFieldPeer::DATABASE_NAME);

		$criteria->add(UllFlowFieldPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of UllFlowField (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setUllFlowAppId($this->ull_flow_app_id);

		$copyObj->setUllFieldId($this->ull_field_id);

		$copyObj->setOptions($this->options);

		$copyObj->setCaptionI18nDefault($this->caption_i18n_default);

		$copyObj->setSequence($this->sequence);

		$copyObj->setDefaultValue($this->default_value);

		$copyObj->setEnabled($this->enabled);

		$copyObj->setMandatory($this->mandatory);

		$copyObj->setIsTitle($this->is_title);

		$copyObj->setIsPriority($this->is_priority);

		$copyObj->setIsDeadline($this->is_deadline);

		$copyObj->setIsCustomField1($this->is_custom_field1);

		$copyObj->setUllAccessGroupId($this->ull_access_group_id);

		$copyObj->setCreatorUserId($this->creator_user_id);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setUpdatorUserId($this->updator_user_id);

		$copyObj->setUpdatedAt($this->updated_at);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach($this->getUllFlowFieldI18ns() as $relObj) {
				$copyObj->addUllFlowFieldI18n($relObj->copy($deepCopy));
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
	 * @return     UllFlowField Clone of current object.
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
	 * @return     UllFlowFieldPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new UllFlowFieldPeer();
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
	 * Temporary storage of collUllFlowFieldI18ns to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initUllFlowFieldI18ns()
	{
		if ($this->collUllFlowFieldI18ns === null) {
			$this->collUllFlowFieldI18ns = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowField has previously
	 * been saved, it will retrieve related UllFlowFieldI18ns from storage.
	 * If this UllFlowField is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getUllFlowFieldI18ns($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowFieldI18nPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collUllFlowFieldI18ns === null) {
			if ($this->isNew()) {
			   $this->collUllFlowFieldI18ns = array();
			} else {

				$criteria->add(UllFlowFieldI18nPeer::ID, $this->getId());

				UllFlowFieldI18nPeer::addSelectColumns($criteria);
				$this->collUllFlowFieldI18ns = UllFlowFieldI18nPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(UllFlowFieldI18nPeer::ID, $this->getId());

				UllFlowFieldI18nPeer::addSelectColumns($criteria);
				if (!isset($this->lastUllFlowFieldI18nCriteria) || !$this->lastUllFlowFieldI18nCriteria->equals($criteria)) {
					$this->collUllFlowFieldI18ns = UllFlowFieldI18nPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastUllFlowFieldI18nCriteria = $criteria;
		return $this->collUllFlowFieldI18ns;
	}

	/**
	 * Returns the number of related UllFlowFieldI18ns.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countUllFlowFieldI18ns($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowFieldI18nPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(UllFlowFieldI18nPeer::ID, $this->getId());

		return UllFlowFieldI18nPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a UllFlowFieldI18n object to this object
	 * through the UllFlowFieldI18n foreign key attribute
	 *
	 * @param      UllFlowFieldI18n $l UllFlowFieldI18n
	 * @return     void
	 * @throws     PropelException
	 */
	public function addUllFlowFieldI18n(UllFlowFieldI18n $l)
	{
		$this->collUllFlowFieldI18ns[] = $l;
		$l->setUllFlowField($this);
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
	 * Otherwise if this UllFlowField has previously
	 * been saved, it will retrieve related UllFlowValues from storage.
	 * If this UllFlowField is new, it will return
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

				$criteria->add(UllFlowValuePeer::ULL_FLOW_FIELD_ID, $this->getId());

				UllFlowValuePeer::addSelectColumns($criteria);
				$this->collUllFlowValues = UllFlowValuePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(UllFlowValuePeer::ULL_FLOW_FIELD_ID, $this->getId());

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

		$criteria->add(UllFlowValuePeer::ULL_FLOW_FIELD_ID, $this->getId());

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
		$l->setUllFlowField($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowField is new, it will return
	 * an empty collection; or if this UllFlowField has previously
	 * been saved, it will retrieve related UllFlowValues from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in UllFlowField.
	 */
	public function getUllFlowValuesJoinUllFlowDoc($criteria = null, $con = null)
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

				$criteria->add(UllFlowValuePeer::ULL_FLOW_FIELD_ID, $this->getId());

				$this->collUllFlowValues = UllFlowValuePeer::doSelectJoinUllFlowDoc($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(UllFlowValuePeer::ULL_FLOW_FIELD_ID, $this->getId());

			if (!isset($this->lastUllFlowValueCriteria) || !$this->lastUllFlowValueCriteria->equals($criteria)) {
				$this->collUllFlowValues = UllFlowValuePeer::doSelectJoinUllFlowDoc($criteria, $con);
			}
		}
		$this->lastUllFlowValueCriteria = $criteria;

		return $this->collUllFlowValues;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowField is new, it will return
	 * an empty collection; or if this UllFlowField has previously
	 * been saved, it will retrieve related UllFlowValues from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in UllFlowField.
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

				$criteria->add(UllFlowValuePeer::ULL_FLOW_FIELD_ID, $this->getId());

				$this->collUllFlowValues = UllFlowValuePeer::doSelectJoinUllFlowMemory($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(UllFlowValuePeer::ULL_FLOW_FIELD_ID, $this->getId());

			if (!isset($this->lastUllFlowValueCriteria) || !$this->lastUllFlowValueCriteria->equals($criteria)) {
				$this->collUllFlowValues = UllFlowValuePeer::doSelectJoinUllFlowMemory($criteria, $con);
			}
		}
		$this->lastUllFlowValueCriteria = $criteria;

		return $this->collUllFlowValues;
	}

  public function getCulture()
  {
    return $this->culture;
  }

  public function setCulture($culture)
  {
    $this->culture = $culture;
  }

  public function getCaptionI18n()
  {
    $obj = $this->getCurrentUllFlowFieldI18n();

    return ($obj ? $obj->getCaptionI18n() : null);
  }

  public function setCaptionI18n($value)
  {
    $this->getCurrentUllFlowFieldI18n()->setCaptionI18n($value);
  }

  protected $current_i18n = array();

  public function getCurrentUllFlowFieldI18n()
  {
    if (!isset($this->current_i18n[$this->culture]))
    {
      $obj = UllFlowFieldI18nPeer::retrieveByPK($this->getId(), $this->culture);
      if ($obj)
      {
        $this->setUllFlowFieldI18nForCulture($obj, $this->culture);
      }
      else
      {
        $this->setUllFlowFieldI18nForCulture(new UllFlowFieldI18n(), $this->culture);
        $this->current_i18n[$this->culture]->setCulture($this->culture);
      }
    }

    return $this->current_i18n[$this->culture];
  }

  public function setUllFlowFieldI18nForCulture($object, $culture)
  {
    $this->current_i18n[$culture] = $object;
    $this->addUllFlowFieldI18n($object);
  }


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseUllFlowField:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseUllFlowField::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseUllFlowField
