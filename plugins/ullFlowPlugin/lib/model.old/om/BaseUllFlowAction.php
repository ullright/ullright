<?php

/**
 * Base class that represents a row from the 'ull_flow_action' table.
 *
 * 
 *
 * @package    plugins.ullFlowPlugin.lib.model.om
 */
abstract class BaseUllFlowAction extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        UllFlowActionPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;


	/**
	 * The value for the slug field.
	 * @var        string
	 */
	protected $slug;


	/**
	 * The value for the caption_i18n_default field.
	 * @var        string
	 */
	protected $caption_i18n_default;


	/**
	 * The value for the status_only field.
	 * @var        boolean
	 */
	protected $status_only;


	/**
	 * The value for the disable_validation field.
	 * @var        boolean
	 */
	protected $disable_validation;


	/**
	 * The value for the notify_creator field.
	 * @var        boolean
	 */
	protected $notify_creator;


	/**
	 * The value for the notify_next field.
	 * @var        boolean
	 */
	protected $notify_next;


	/**
	 * The value for the in_resultlist_by_default field.
	 * @var        boolean
	 */
	protected $in_resultlist_by_default;


	/**
	 * The value for the show_assigned_to field.
	 * @var        boolean
	 */
	protected $show_assigned_to;


	/**
	 * The value for the comment_is_mandatory field.
	 * @var        boolean
	 */
	protected $comment_is_mandatory;


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
	 * Collection to store aggregation of collUllFlowActionI18ns.
	 * @var        array
	 */
	protected $collUllFlowActionI18ns;

	/**
	 * The criteria used to select the current contents of collUllFlowActionI18ns.
	 * @var        Criteria
	 */
	protected $lastUllFlowActionI18nCriteria = null;

	/**
	 * Collection to store aggregation of collUllFlowDocs.
	 * @var        array
	 */
	protected $collUllFlowDocs;

	/**
	 * The criteria used to select the current contents of collUllFlowDocs.
	 * @var        Criteria
	 */
	protected $lastUllFlowDocCriteria = null;

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
	 * Collection to store aggregation of collUllFlowStepActions.
	 * @var        array
	 */
	protected $collUllFlowStepActions;

	/**
	 * The criteria used to select the current contents of collUllFlowStepActions.
	 * @var        Criteria
	 */
	protected $lastUllFlowStepActionCriteria = null;

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
	 * Get the [slug] column value.
	 * 
	 * @return     string
	 */
	public function getSlug()
	{

		return $this->slug;
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
	 * Get the [status_only] column value.
	 * 
	 * @return     boolean
	 */
	public function getStatusOnly()
	{

		return $this->status_only;
	}

	/**
	 * Get the [disable_validation] column value.
	 * 
	 * @return     boolean
	 */
	public function getDisableValidation()
	{

		return $this->disable_validation;
	}

	/**
	 * Get the [notify_creator] column value.
	 * 
	 * @return     boolean
	 */
	public function getNotifyCreator()
	{

		return $this->notify_creator;
	}

	/**
	 * Get the [notify_next] column value.
	 * 
	 * @return     boolean
	 */
	public function getNotifyNext()
	{

		return $this->notify_next;
	}

	/**
	 * Get the [in_resultlist_by_default] column value.
	 * 
	 * @return     boolean
	 */
	public function getInResultlistByDefault()
	{

		return $this->in_resultlist_by_default;
	}

	/**
	 * Get the [show_assigned_to] column value.
	 * 
	 * @return     boolean
	 */
	public function getShowAssignedTo()
	{

		return $this->show_assigned_to;
	}

	/**
	 * Get the [comment_is_mandatory] column value.
	 * 
	 * @return     boolean
	 */
	public function getCommentIsMandatory()
	{

		return $this->comment_is_mandatory;
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
			$this->modifiedColumns[] = UllFlowActionPeer::ID;
		}

	} // setId()

	/**
	 * Set the value of [slug] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setSlug($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->slug !== $v) {
			$this->slug = $v;
			$this->modifiedColumns[] = UllFlowActionPeer::SLUG;
		}

	} // setSlug()

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
			$this->modifiedColumns[] = UllFlowActionPeer::CAPTION_I18N_DEFAULT;
		}

	} // setCaptionI18nDefault()

	/**
	 * Set the value of [status_only] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setStatusOnly($v)
	{

		if ($this->status_only !== $v) {
			$this->status_only = $v;
			$this->modifiedColumns[] = UllFlowActionPeer::STATUS_ONLY;
		}

	} // setStatusOnly()

	/**
	 * Set the value of [disable_validation] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setDisableValidation($v)
	{

		if ($this->disable_validation !== $v) {
			$this->disable_validation = $v;
			$this->modifiedColumns[] = UllFlowActionPeer::DISABLE_VALIDATION;
		}

	} // setDisableValidation()

	/**
	 * Set the value of [notify_creator] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setNotifyCreator($v)
	{

		if ($this->notify_creator !== $v) {
			$this->notify_creator = $v;
			$this->modifiedColumns[] = UllFlowActionPeer::NOTIFY_CREATOR;
		}

	} // setNotifyCreator()

	/**
	 * Set the value of [notify_next] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setNotifyNext($v)
	{

		if ($this->notify_next !== $v) {
			$this->notify_next = $v;
			$this->modifiedColumns[] = UllFlowActionPeer::NOTIFY_NEXT;
		}

	} // setNotifyNext()

	/**
	 * Set the value of [in_resultlist_by_default] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setInResultlistByDefault($v)
	{

		if ($this->in_resultlist_by_default !== $v) {
			$this->in_resultlist_by_default = $v;
			$this->modifiedColumns[] = UllFlowActionPeer::IN_RESULTLIST_BY_DEFAULT;
		}

	} // setInResultlistByDefault()

	/**
	 * Set the value of [show_assigned_to] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setShowAssignedTo($v)
	{

		if ($this->show_assigned_to !== $v) {
			$this->show_assigned_to = $v;
			$this->modifiedColumns[] = UllFlowActionPeer::SHOW_ASSIGNED_TO;
		}

	} // setShowAssignedTo()

	/**
	 * Set the value of [comment_is_mandatory] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setCommentIsMandatory($v)
	{

		if ($this->comment_is_mandatory !== $v) {
			$this->comment_is_mandatory = $v;
			$this->modifiedColumns[] = UllFlowActionPeer::COMMENT_IS_MANDATORY;
		}

	} // setCommentIsMandatory()

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
			$this->modifiedColumns[] = UllFlowActionPeer::CREATOR_USER_ID;
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
			$this->modifiedColumns[] = UllFlowActionPeer::CREATED_AT;
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
			$this->modifiedColumns[] = UllFlowActionPeer::UPDATOR_USER_ID;
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
			$this->modifiedColumns[] = UllFlowActionPeer::UPDATED_AT;
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

			$this->slug = $rs->getString($startcol + 1);

			$this->caption_i18n_default = $rs->getString($startcol + 2);

			$this->status_only = $rs->getBoolean($startcol + 3);

			$this->disable_validation = $rs->getBoolean($startcol + 4);

			$this->notify_creator = $rs->getBoolean($startcol + 5);

			$this->notify_next = $rs->getBoolean($startcol + 6);

			$this->in_resultlist_by_default = $rs->getBoolean($startcol + 7);

			$this->show_assigned_to = $rs->getBoolean($startcol + 8);

			$this->comment_is_mandatory = $rs->getBoolean($startcol + 9);

			$this->creator_user_id = $rs->getInt($startcol + 10);

			$this->created_at = $rs->getTimestamp($startcol + 11, null);

			$this->updator_user_id = $rs->getInt($startcol + 12);

			$this->updated_at = $rs->getTimestamp($startcol + 13, null);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 14; // 14 = UllFlowActionPeer::NUM_COLUMNS - UllFlowActionPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating UllFlowAction object", $e);
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

    foreach (sfMixer::getCallables('BaseUllFlowAction:delete:pre') as $callable)
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
			$con = Propel::getConnection(UllFlowActionPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			UllFlowActionPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseUllFlowAction:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseUllFlowAction:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(UllFlowActionPeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

    if ($this->isModified() && !$this->isColumnModified(UllFlowActionPeer::UPDATED_AT))
    {
      $this->setUpdatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(UllFlowActionPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseUllFlowAction:save:post') as $callable)
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


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = UllFlowActionPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += UllFlowActionPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collUllFlowActionI18ns !== null) {
				foreach($this->collUllFlowActionI18ns as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collUllFlowDocs !== null) {
				foreach($this->collUllFlowDocs as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collUllFlowMemorys !== null) {
				foreach($this->collUllFlowMemorys as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collUllFlowStepActions !== null) {
				foreach($this->collUllFlowStepActions as $referrerFK) {
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


			if (($retval = UllFlowActionPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collUllFlowActionI18ns !== null) {
					foreach($this->collUllFlowActionI18ns as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collUllFlowDocs !== null) {
					foreach($this->collUllFlowDocs as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collUllFlowMemorys !== null) {
					foreach($this->collUllFlowMemorys as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collUllFlowStepActions !== null) {
					foreach($this->collUllFlowStepActions as $referrerFK) {
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
		$pos = UllFlowActionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getSlug();
				break;
			case 2:
				return $this->getCaptionI18nDefault();
				break;
			case 3:
				return $this->getStatusOnly();
				break;
			case 4:
				return $this->getDisableValidation();
				break;
			case 5:
				return $this->getNotifyCreator();
				break;
			case 6:
				return $this->getNotifyNext();
				break;
			case 7:
				return $this->getInResultlistByDefault();
				break;
			case 8:
				return $this->getShowAssignedTo();
				break;
			case 9:
				return $this->getCommentIsMandatory();
				break;
			case 10:
				return $this->getCreatorUserId();
				break;
			case 11:
				return $this->getCreatedAt();
				break;
			case 12:
				return $this->getUpdatorUserId();
				break;
			case 13:
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
		$keys = UllFlowActionPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getSlug(),
			$keys[2] => $this->getCaptionI18nDefault(),
			$keys[3] => $this->getStatusOnly(),
			$keys[4] => $this->getDisableValidation(),
			$keys[5] => $this->getNotifyCreator(),
			$keys[6] => $this->getNotifyNext(),
			$keys[7] => $this->getInResultlistByDefault(),
			$keys[8] => $this->getShowAssignedTo(),
			$keys[9] => $this->getCommentIsMandatory(),
			$keys[10] => $this->getCreatorUserId(),
			$keys[11] => $this->getCreatedAt(),
			$keys[12] => $this->getUpdatorUserId(),
			$keys[13] => $this->getUpdatedAt(),
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
		$pos = UllFlowActionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setSlug($value);
				break;
			case 2:
				$this->setCaptionI18nDefault($value);
				break;
			case 3:
				$this->setStatusOnly($value);
				break;
			case 4:
				$this->setDisableValidation($value);
				break;
			case 5:
				$this->setNotifyCreator($value);
				break;
			case 6:
				$this->setNotifyNext($value);
				break;
			case 7:
				$this->setInResultlistByDefault($value);
				break;
			case 8:
				$this->setShowAssignedTo($value);
				break;
			case 9:
				$this->setCommentIsMandatory($value);
				break;
			case 10:
				$this->setCreatorUserId($value);
				break;
			case 11:
				$this->setCreatedAt($value);
				break;
			case 12:
				$this->setUpdatorUserId($value);
				break;
			case 13:
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
		$keys = UllFlowActionPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setSlug($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setCaptionI18nDefault($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setStatusOnly($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setDisableValidation($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setNotifyCreator($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setNotifyNext($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setInResultlistByDefault($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setShowAssignedTo($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setCommentIsMandatory($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setCreatorUserId($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setCreatedAt($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setUpdatorUserId($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setUpdatedAt($arr[$keys[13]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(UllFlowActionPeer::DATABASE_NAME);

		if ($this->isColumnModified(UllFlowActionPeer::ID)) $criteria->add(UllFlowActionPeer::ID, $this->id);
		if ($this->isColumnModified(UllFlowActionPeer::SLUG)) $criteria->add(UllFlowActionPeer::SLUG, $this->slug);
		if ($this->isColumnModified(UllFlowActionPeer::CAPTION_I18N_DEFAULT)) $criteria->add(UllFlowActionPeer::CAPTION_I18N_DEFAULT, $this->caption_i18n_default);
		if ($this->isColumnModified(UllFlowActionPeer::STATUS_ONLY)) $criteria->add(UllFlowActionPeer::STATUS_ONLY, $this->status_only);
		if ($this->isColumnModified(UllFlowActionPeer::DISABLE_VALIDATION)) $criteria->add(UllFlowActionPeer::DISABLE_VALIDATION, $this->disable_validation);
		if ($this->isColumnModified(UllFlowActionPeer::NOTIFY_CREATOR)) $criteria->add(UllFlowActionPeer::NOTIFY_CREATOR, $this->notify_creator);
		if ($this->isColumnModified(UllFlowActionPeer::NOTIFY_NEXT)) $criteria->add(UllFlowActionPeer::NOTIFY_NEXT, $this->notify_next);
		if ($this->isColumnModified(UllFlowActionPeer::IN_RESULTLIST_BY_DEFAULT)) $criteria->add(UllFlowActionPeer::IN_RESULTLIST_BY_DEFAULT, $this->in_resultlist_by_default);
		if ($this->isColumnModified(UllFlowActionPeer::SHOW_ASSIGNED_TO)) $criteria->add(UllFlowActionPeer::SHOW_ASSIGNED_TO, $this->show_assigned_to);
		if ($this->isColumnModified(UllFlowActionPeer::COMMENT_IS_MANDATORY)) $criteria->add(UllFlowActionPeer::COMMENT_IS_MANDATORY, $this->comment_is_mandatory);
		if ($this->isColumnModified(UllFlowActionPeer::CREATOR_USER_ID)) $criteria->add(UllFlowActionPeer::CREATOR_USER_ID, $this->creator_user_id);
		if ($this->isColumnModified(UllFlowActionPeer::CREATED_AT)) $criteria->add(UllFlowActionPeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(UllFlowActionPeer::UPDATOR_USER_ID)) $criteria->add(UllFlowActionPeer::UPDATOR_USER_ID, $this->updator_user_id);
		if ($this->isColumnModified(UllFlowActionPeer::UPDATED_AT)) $criteria->add(UllFlowActionPeer::UPDATED_AT, $this->updated_at);

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
		$criteria = new Criteria(UllFlowActionPeer::DATABASE_NAME);

		$criteria->add(UllFlowActionPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of UllFlowAction (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setSlug($this->slug);

		$copyObj->setCaptionI18nDefault($this->caption_i18n_default);

		$copyObj->setStatusOnly($this->status_only);

		$copyObj->setDisableValidation($this->disable_validation);

		$copyObj->setNotifyCreator($this->notify_creator);

		$copyObj->setNotifyNext($this->notify_next);

		$copyObj->setInResultlistByDefault($this->in_resultlist_by_default);

		$copyObj->setShowAssignedTo($this->show_assigned_to);

		$copyObj->setCommentIsMandatory($this->comment_is_mandatory);

		$copyObj->setCreatorUserId($this->creator_user_id);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setUpdatorUserId($this->updator_user_id);

		$copyObj->setUpdatedAt($this->updated_at);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach($this->getUllFlowActionI18ns() as $relObj) {
				$copyObj->addUllFlowActionI18n($relObj->copy($deepCopy));
			}

			foreach($this->getUllFlowDocs() as $relObj) {
				$copyObj->addUllFlowDoc($relObj->copy($deepCopy));
			}

			foreach($this->getUllFlowMemorys() as $relObj) {
				$copyObj->addUllFlowMemory($relObj->copy($deepCopy));
			}

			foreach($this->getUllFlowStepActions() as $relObj) {
				$copyObj->addUllFlowStepAction($relObj->copy($deepCopy));
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
	 * @return     UllFlowAction Clone of current object.
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
	 * @return     UllFlowActionPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new UllFlowActionPeer();
		}
		return self::$peer;
	}

	/**
	 * Temporary storage of collUllFlowActionI18ns to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initUllFlowActionI18ns()
	{
		if ($this->collUllFlowActionI18ns === null) {
			$this->collUllFlowActionI18ns = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowAction has previously
	 * been saved, it will retrieve related UllFlowActionI18ns from storage.
	 * If this UllFlowAction is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getUllFlowActionI18ns($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowActionI18nPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collUllFlowActionI18ns === null) {
			if ($this->isNew()) {
			   $this->collUllFlowActionI18ns = array();
			} else {

				$criteria->add(UllFlowActionI18nPeer::ID, $this->getId());

				UllFlowActionI18nPeer::addSelectColumns($criteria);
				$this->collUllFlowActionI18ns = UllFlowActionI18nPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(UllFlowActionI18nPeer::ID, $this->getId());

				UllFlowActionI18nPeer::addSelectColumns($criteria);
				if (!isset($this->lastUllFlowActionI18nCriteria) || !$this->lastUllFlowActionI18nCriteria->equals($criteria)) {
					$this->collUllFlowActionI18ns = UllFlowActionI18nPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastUllFlowActionI18nCriteria = $criteria;
		return $this->collUllFlowActionI18ns;
	}

	/**
	 * Returns the number of related UllFlowActionI18ns.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countUllFlowActionI18ns($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowActionI18nPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(UllFlowActionI18nPeer::ID, $this->getId());

		return UllFlowActionI18nPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a UllFlowActionI18n object to this object
	 * through the UllFlowActionI18n foreign key attribute
	 *
	 * @param      UllFlowActionI18n $l UllFlowActionI18n
	 * @return     void
	 * @throws     PropelException
	 */
	public function addUllFlowActionI18n(UllFlowActionI18n $l)
	{
		$this->collUllFlowActionI18ns[] = $l;
		$l->setUllFlowAction($this);
	}

	/**
	 * Temporary storage of collUllFlowDocs to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initUllFlowDocs()
	{
		if ($this->collUllFlowDocs === null) {
			$this->collUllFlowDocs = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowAction has previously
	 * been saved, it will retrieve related UllFlowDocs from storage.
	 * If this UllFlowAction is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getUllFlowDocs($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowDocPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collUllFlowDocs === null) {
			if ($this->isNew()) {
			   $this->collUllFlowDocs = array();
			} else {

				$criteria->add(UllFlowDocPeer::ULL_FLOW_ACTION_ID, $this->getId());

				UllFlowDocPeer::addSelectColumns($criteria);
				$this->collUllFlowDocs = UllFlowDocPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(UllFlowDocPeer::ULL_FLOW_ACTION_ID, $this->getId());

				UllFlowDocPeer::addSelectColumns($criteria);
				if (!isset($this->lastUllFlowDocCriteria) || !$this->lastUllFlowDocCriteria->equals($criteria)) {
					$this->collUllFlowDocs = UllFlowDocPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastUllFlowDocCriteria = $criteria;
		return $this->collUllFlowDocs;
	}

	/**
	 * Returns the number of related UllFlowDocs.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countUllFlowDocs($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowDocPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(UllFlowDocPeer::ULL_FLOW_ACTION_ID, $this->getId());

		return UllFlowDocPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a UllFlowDoc object to this object
	 * through the UllFlowDoc foreign key attribute
	 *
	 * @param      UllFlowDoc $l UllFlowDoc
	 * @return     void
	 * @throws     PropelException
	 */
	public function addUllFlowDoc(UllFlowDoc $l)
	{
		$this->collUllFlowDocs[] = $l;
		$l->setUllFlowAction($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowAction is new, it will return
	 * an empty collection; or if this UllFlowAction has previously
	 * been saved, it will retrieve related UllFlowDocs from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in UllFlowAction.
	 */
	public function getUllFlowDocsJoinUllFlowApp($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowDocPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collUllFlowDocs === null) {
			if ($this->isNew()) {
				$this->collUllFlowDocs = array();
			} else {

				$criteria->add(UllFlowDocPeer::ULL_FLOW_ACTION_ID, $this->getId());

				$this->collUllFlowDocs = UllFlowDocPeer::doSelectJoinUllFlowApp($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(UllFlowDocPeer::ULL_FLOW_ACTION_ID, $this->getId());

			if (!isset($this->lastUllFlowDocCriteria) || !$this->lastUllFlowDocCriteria->equals($criteria)) {
				$this->collUllFlowDocs = UllFlowDocPeer::doSelectJoinUllFlowApp($criteria, $con);
			}
		}
		$this->lastUllFlowDocCriteria = $criteria;

		return $this->collUllFlowDocs;
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
	 * Otherwise if this UllFlowAction has previously
	 * been saved, it will retrieve related UllFlowMemorys from storage.
	 * If this UllFlowAction is new, it will return
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

				$criteria->add(UllFlowMemoryPeer::ULL_FLOW_ACTION_ID, $this->getId());

				UllFlowMemoryPeer::addSelectColumns($criteria);
				$this->collUllFlowMemorys = UllFlowMemoryPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(UllFlowMemoryPeer::ULL_FLOW_ACTION_ID, $this->getId());

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

		$criteria->add(UllFlowMemoryPeer::ULL_FLOW_ACTION_ID, $this->getId());

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
		$l->setUllFlowAction($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowAction is new, it will return
	 * an empty collection; or if this UllFlowAction has previously
	 * been saved, it will retrieve related UllFlowMemorys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in UllFlowAction.
	 */
	public function getUllFlowMemorysJoinUllFlowDoc($criteria = null, $con = null)
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

				$criteria->add(UllFlowMemoryPeer::ULL_FLOW_ACTION_ID, $this->getId());

				$this->collUllFlowMemorys = UllFlowMemoryPeer::doSelectJoinUllFlowDoc($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(UllFlowMemoryPeer::ULL_FLOW_ACTION_ID, $this->getId());

			if (!isset($this->lastUllFlowMemoryCriteria) || !$this->lastUllFlowMemoryCriteria->equals($criteria)) {
				$this->collUllFlowMemorys = UllFlowMemoryPeer::doSelectJoinUllFlowDoc($criteria, $con);
			}
		}
		$this->lastUllFlowMemoryCriteria = $criteria;

		return $this->collUllFlowMemorys;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowAction is new, it will return
	 * an empty collection; or if this UllFlowAction has previously
	 * been saved, it will retrieve related UllFlowMemorys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in UllFlowAction.
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

				$criteria->add(UllFlowMemoryPeer::ULL_FLOW_ACTION_ID, $this->getId());

				$this->collUllFlowMemorys = UllFlowMemoryPeer::doSelectJoinUllFlowStep($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(UllFlowMemoryPeer::ULL_FLOW_ACTION_ID, $this->getId());

			if (!isset($this->lastUllFlowMemoryCriteria) || !$this->lastUllFlowMemoryCriteria->equals($criteria)) {
				$this->collUllFlowMemorys = UllFlowMemoryPeer::doSelectJoinUllFlowStep($criteria, $con);
			}
		}
		$this->lastUllFlowMemoryCriteria = $criteria;

		return $this->collUllFlowMemorys;
	}

	/**
	 * Temporary storage of collUllFlowStepActions to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initUllFlowStepActions()
	{
		if ($this->collUllFlowStepActions === null) {
			$this->collUllFlowStepActions = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowAction has previously
	 * been saved, it will retrieve related UllFlowStepActions from storage.
	 * If this UllFlowAction is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getUllFlowStepActions($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowStepActionPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collUllFlowStepActions === null) {
			if ($this->isNew()) {
			   $this->collUllFlowStepActions = array();
			} else {

				$criteria->add(UllFlowStepActionPeer::ULL_FLOW_ACTION_ID, $this->getId());

				UllFlowStepActionPeer::addSelectColumns($criteria);
				$this->collUllFlowStepActions = UllFlowStepActionPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(UllFlowStepActionPeer::ULL_FLOW_ACTION_ID, $this->getId());

				UllFlowStepActionPeer::addSelectColumns($criteria);
				if (!isset($this->lastUllFlowStepActionCriteria) || !$this->lastUllFlowStepActionCriteria->equals($criteria)) {
					$this->collUllFlowStepActions = UllFlowStepActionPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastUllFlowStepActionCriteria = $criteria;
		return $this->collUllFlowStepActions;
	}

	/**
	 * Returns the number of related UllFlowStepActions.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countUllFlowStepActions($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowStepActionPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(UllFlowStepActionPeer::ULL_FLOW_ACTION_ID, $this->getId());

		return UllFlowStepActionPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a UllFlowStepAction object to this object
	 * through the UllFlowStepAction foreign key attribute
	 *
	 * @param      UllFlowStepAction $l UllFlowStepAction
	 * @return     void
	 * @throws     PropelException
	 */
	public function addUllFlowStepAction(UllFlowStepAction $l)
	{
		$this->collUllFlowStepActions[] = $l;
		$l->setUllFlowAction($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowAction is new, it will return
	 * an empty collection; or if this UllFlowAction has previously
	 * been saved, it will retrieve related UllFlowStepActions from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in UllFlowAction.
	 */
	public function getUllFlowStepActionsJoinUllFlowStep($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowStepActionPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collUllFlowStepActions === null) {
			if ($this->isNew()) {
				$this->collUllFlowStepActions = array();
			} else {

				$criteria->add(UllFlowStepActionPeer::ULL_FLOW_ACTION_ID, $this->getId());

				$this->collUllFlowStepActions = UllFlowStepActionPeer::doSelectJoinUllFlowStep($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(UllFlowStepActionPeer::ULL_FLOW_ACTION_ID, $this->getId());

			if (!isset($this->lastUllFlowStepActionCriteria) || !$this->lastUllFlowStepActionCriteria->equals($criteria)) {
				$this->collUllFlowStepActions = UllFlowStepActionPeer::doSelectJoinUllFlowStep($criteria, $con);
			}
		}
		$this->lastUllFlowStepActionCriteria = $criteria;

		return $this->collUllFlowStepActions;
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
    $obj = $this->getCurrentUllFlowActionI18n();

    return ($obj ? $obj->getCaptionI18n() : null);
  }

  public function setCaptionI18n($value)
  {
    $this->getCurrentUllFlowActionI18n()->setCaptionI18n($value);
  }

  protected $current_i18n = array();

  public function getCurrentUllFlowActionI18n()
  {
    if (!isset($this->current_i18n[$this->culture]))
    {
      $obj = UllFlowActionI18nPeer::retrieveByPK($this->getId(), $this->culture);
      if ($obj)
      {
        $this->setUllFlowActionI18nForCulture($obj, $this->culture);
      }
      else
      {
        $this->setUllFlowActionI18nForCulture(new UllFlowActionI18n(), $this->culture);
        $this->current_i18n[$this->culture]->setCulture($this->culture);
      }
    }

    return $this->current_i18n[$this->culture];
  }

  public function setUllFlowActionI18nForCulture($object, $culture)
  {
    $this->current_i18n[$culture] = $object;
    $this->addUllFlowActionI18n($object);
  }


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseUllFlowAction:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseUllFlowAction::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseUllFlowAction
