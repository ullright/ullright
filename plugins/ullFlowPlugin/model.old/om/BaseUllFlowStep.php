<?php

/**
 * Base class that represents a row from the 'ull_flow_step' table.
 *
 * 
 *
 * @package    plugins.ullFlowPlugin.lib.model.om
 */
abstract class BaseUllFlowStep extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        UllFlowStepPeer
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
	 * The value for the is_start field.
	 * @var        boolean
	 */
	protected $is_start;


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
	 * Collection to store aggregation of collUllFlowStepI18ns.
	 * @var        array
	 */
	protected $collUllFlowStepI18ns;

	/**
	 * The criteria used to select the current contents of collUllFlowStepI18ns.
	 * @var        Criteria
	 */
	protected $lastUllFlowStepI18nCriteria = null;

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
	 * Get the [ull_flow_app_id] column value.
	 * 
	 * @return     int
	 */
	public function getUllFlowAppId()
	{

		return $this->ull_flow_app_id;
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
	 * Get the [is_start] column value.
	 * 
	 * @return     boolean
	 */
	public function getIsStart()
	{

		return $this->is_start;
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
			$this->modifiedColumns[] = UllFlowStepPeer::ID;
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
			$this->modifiedColumns[] = UllFlowStepPeer::ULL_FLOW_APP_ID;
		}

		if ($this->aUllFlowApp !== null && $this->aUllFlowApp->getId() !== $v) {
			$this->aUllFlowApp = null;
		}

	} // setUllFlowAppId()

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
			$this->modifiedColumns[] = UllFlowStepPeer::SLUG;
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
			$this->modifiedColumns[] = UllFlowStepPeer::CAPTION_I18N_DEFAULT;
		}

	} // setCaptionI18nDefault()

	/**
	 * Set the value of [is_start] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setIsStart($v)
	{

		if ($this->is_start !== $v) {
			$this->is_start = $v;
			$this->modifiedColumns[] = UllFlowStepPeer::IS_START;
		}

	} // setIsStart()

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
			$this->modifiedColumns[] = UllFlowStepPeer::CREATOR_USER_ID;
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
			$this->modifiedColumns[] = UllFlowStepPeer::CREATED_AT;
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
			$this->modifiedColumns[] = UllFlowStepPeer::UPDATOR_USER_ID;
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
			$this->modifiedColumns[] = UllFlowStepPeer::UPDATED_AT;
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

			$this->slug = $rs->getString($startcol + 2);

			$this->caption_i18n_default = $rs->getString($startcol + 3);

			$this->is_start = $rs->getBoolean($startcol + 4);

			$this->creator_user_id = $rs->getInt($startcol + 5);

			$this->created_at = $rs->getTimestamp($startcol + 6, null);

			$this->updator_user_id = $rs->getInt($startcol + 7);

			$this->updated_at = $rs->getTimestamp($startcol + 8, null);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 9; // 9 = UllFlowStepPeer::NUM_COLUMNS - UllFlowStepPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating UllFlowStep object", $e);
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

    foreach (sfMixer::getCallables('BaseUllFlowStep:delete:pre') as $callable)
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
			$con = Propel::getConnection(UllFlowStepPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			UllFlowStepPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseUllFlowStep:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseUllFlowStep:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(UllFlowStepPeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

    if ($this->isModified() && !$this->isColumnModified(UllFlowStepPeer::UPDATED_AT))
    {
      $this->setUpdatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(UllFlowStepPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseUllFlowStep:save:post') as $callable)
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
					$pk = UllFlowStepPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += UllFlowStepPeer::doUpdate($this, $con);
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

			if ($this->collUllFlowStepI18ns !== null) {
				foreach($this->collUllFlowStepI18ns as $referrerFK) {
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


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aUllFlowApp !== null) {
				if (!$this->aUllFlowApp->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aUllFlowApp->getValidationFailures());
				}
			}


			if (($retval = UllFlowStepPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collUllFlowMemorys !== null) {
					foreach($this->collUllFlowMemorys as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collUllFlowStepI18ns !== null) {
					foreach($this->collUllFlowStepI18ns as $referrerFK) {
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
		$pos = UllFlowStepPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getSlug();
				break;
			case 3:
				return $this->getCaptionI18nDefault();
				break;
			case 4:
				return $this->getIsStart();
				break;
			case 5:
				return $this->getCreatorUserId();
				break;
			case 6:
				return $this->getCreatedAt();
				break;
			case 7:
				return $this->getUpdatorUserId();
				break;
			case 8:
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
		$keys = UllFlowStepPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getUllFlowAppId(),
			$keys[2] => $this->getSlug(),
			$keys[3] => $this->getCaptionI18nDefault(),
			$keys[4] => $this->getIsStart(),
			$keys[5] => $this->getCreatorUserId(),
			$keys[6] => $this->getCreatedAt(),
			$keys[7] => $this->getUpdatorUserId(),
			$keys[8] => $this->getUpdatedAt(),
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
		$pos = UllFlowStepPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setSlug($value);
				break;
			case 3:
				$this->setCaptionI18nDefault($value);
				break;
			case 4:
				$this->setIsStart($value);
				break;
			case 5:
				$this->setCreatorUserId($value);
				break;
			case 6:
				$this->setCreatedAt($value);
				break;
			case 7:
				$this->setUpdatorUserId($value);
				break;
			case 8:
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
		$keys = UllFlowStepPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setUllFlowAppId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setSlug($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setCaptionI18nDefault($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setIsStart($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setCreatorUserId($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setCreatedAt($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setUpdatorUserId($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setUpdatedAt($arr[$keys[8]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(UllFlowStepPeer::DATABASE_NAME);

		if ($this->isColumnModified(UllFlowStepPeer::ID)) $criteria->add(UllFlowStepPeer::ID, $this->id);
		if ($this->isColumnModified(UllFlowStepPeer::ULL_FLOW_APP_ID)) $criteria->add(UllFlowStepPeer::ULL_FLOW_APP_ID, $this->ull_flow_app_id);
		if ($this->isColumnModified(UllFlowStepPeer::SLUG)) $criteria->add(UllFlowStepPeer::SLUG, $this->slug);
		if ($this->isColumnModified(UllFlowStepPeer::CAPTION_I18N_DEFAULT)) $criteria->add(UllFlowStepPeer::CAPTION_I18N_DEFAULT, $this->caption_i18n_default);
		if ($this->isColumnModified(UllFlowStepPeer::IS_START)) $criteria->add(UllFlowStepPeer::IS_START, $this->is_start);
		if ($this->isColumnModified(UllFlowStepPeer::CREATOR_USER_ID)) $criteria->add(UllFlowStepPeer::CREATOR_USER_ID, $this->creator_user_id);
		if ($this->isColumnModified(UllFlowStepPeer::CREATED_AT)) $criteria->add(UllFlowStepPeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(UllFlowStepPeer::UPDATOR_USER_ID)) $criteria->add(UllFlowStepPeer::UPDATOR_USER_ID, $this->updator_user_id);
		if ($this->isColumnModified(UllFlowStepPeer::UPDATED_AT)) $criteria->add(UllFlowStepPeer::UPDATED_AT, $this->updated_at);

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
		$criteria = new Criteria(UllFlowStepPeer::DATABASE_NAME);

		$criteria->add(UllFlowStepPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of UllFlowStep (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setUllFlowAppId($this->ull_flow_app_id);

		$copyObj->setSlug($this->slug);

		$copyObj->setCaptionI18nDefault($this->caption_i18n_default);

		$copyObj->setIsStart($this->is_start);

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

			foreach($this->getUllFlowStepI18ns() as $relObj) {
				$copyObj->addUllFlowStepI18n($relObj->copy($deepCopy));
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
	 * @return     UllFlowStep Clone of current object.
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
	 * @return     UllFlowStepPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new UllFlowStepPeer();
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
	 * Otherwise if this UllFlowStep has previously
	 * been saved, it will retrieve related UllFlowMemorys from storage.
	 * If this UllFlowStep is new, it will return
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

				$criteria->add(UllFlowMemoryPeer::ULL_FLOW_STEP_ID, $this->getId());

				UllFlowMemoryPeer::addSelectColumns($criteria);
				$this->collUllFlowMemorys = UllFlowMemoryPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(UllFlowMemoryPeer::ULL_FLOW_STEP_ID, $this->getId());

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

		$criteria->add(UllFlowMemoryPeer::ULL_FLOW_STEP_ID, $this->getId());

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
		$l->setUllFlowStep($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowStep is new, it will return
	 * an empty collection; or if this UllFlowStep has previously
	 * been saved, it will retrieve related UllFlowMemorys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in UllFlowStep.
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

				$criteria->add(UllFlowMemoryPeer::ULL_FLOW_STEP_ID, $this->getId());

				$this->collUllFlowMemorys = UllFlowMemoryPeer::doSelectJoinUllFlowDoc($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(UllFlowMemoryPeer::ULL_FLOW_STEP_ID, $this->getId());

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
	 * Otherwise if this UllFlowStep is new, it will return
	 * an empty collection; or if this UllFlowStep has previously
	 * been saved, it will retrieve related UllFlowMemorys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in UllFlowStep.
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

				$criteria->add(UllFlowMemoryPeer::ULL_FLOW_STEP_ID, $this->getId());

				$this->collUllFlowMemorys = UllFlowMemoryPeer::doSelectJoinUllFlowAction($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(UllFlowMemoryPeer::ULL_FLOW_STEP_ID, $this->getId());

			if (!isset($this->lastUllFlowMemoryCriteria) || !$this->lastUllFlowMemoryCriteria->equals($criteria)) {
				$this->collUllFlowMemorys = UllFlowMemoryPeer::doSelectJoinUllFlowAction($criteria, $con);
			}
		}
		$this->lastUllFlowMemoryCriteria = $criteria;

		return $this->collUllFlowMemorys;
	}

	/**
	 * Temporary storage of collUllFlowStepI18ns to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initUllFlowStepI18ns()
	{
		if ($this->collUllFlowStepI18ns === null) {
			$this->collUllFlowStepI18ns = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowStep has previously
	 * been saved, it will retrieve related UllFlowStepI18ns from storage.
	 * If this UllFlowStep is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getUllFlowStepI18ns($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowStepI18nPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collUllFlowStepI18ns === null) {
			if ($this->isNew()) {
			   $this->collUllFlowStepI18ns = array();
			} else {

				$criteria->add(UllFlowStepI18nPeer::ID, $this->getId());

				UllFlowStepI18nPeer::addSelectColumns($criteria);
				$this->collUllFlowStepI18ns = UllFlowStepI18nPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(UllFlowStepI18nPeer::ID, $this->getId());

				UllFlowStepI18nPeer::addSelectColumns($criteria);
				if (!isset($this->lastUllFlowStepI18nCriteria) || !$this->lastUllFlowStepI18nCriteria->equals($criteria)) {
					$this->collUllFlowStepI18ns = UllFlowStepI18nPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastUllFlowStepI18nCriteria = $criteria;
		return $this->collUllFlowStepI18ns;
	}

	/**
	 * Returns the number of related UllFlowStepI18ns.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countUllFlowStepI18ns($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowStepI18nPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(UllFlowStepI18nPeer::ID, $this->getId());

		return UllFlowStepI18nPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a UllFlowStepI18n object to this object
	 * through the UllFlowStepI18n foreign key attribute
	 *
	 * @param      UllFlowStepI18n $l UllFlowStepI18n
	 * @return     void
	 * @throws     PropelException
	 */
	public function addUllFlowStepI18n(UllFlowStepI18n $l)
	{
		$this->collUllFlowStepI18ns[] = $l;
		$l->setUllFlowStep($this);
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
	 * Otherwise if this UllFlowStep has previously
	 * been saved, it will retrieve related UllFlowStepActions from storage.
	 * If this UllFlowStep is new, it will return
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

				$criteria->add(UllFlowStepActionPeer::ULL_FLOW_STEP_ID, $this->getId());

				UllFlowStepActionPeer::addSelectColumns($criteria);
				$this->collUllFlowStepActions = UllFlowStepActionPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(UllFlowStepActionPeer::ULL_FLOW_STEP_ID, $this->getId());

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

		$criteria->add(UllFlowStepActionPeer::ULL_FLOW_STEP_ID, $this->getId());

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
		$l->setUllFlowStep($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowStep is new, it will return
	 * an empty collection; or if this UllFlowStep has previously
	 * been saved, it will retrieve related UllFlowStepActions from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in UllFlowStep.
	 */
	public function getUllFlowStepActionsJoinUllFlowAction($criteria = null, $con = null)
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

				$criteria->add(UllFlowStepActionPeer::ULL_FLOW_STEP_ID, $this->getId());

				$this->collUllFlowStepActions = UllFlowStepActionPeer::doSelectJoinUllFlowAction($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(UllFlowStepActionPeer::ULL_FLOW_STEP_ID, $this->getId());

			if (!isset($this->lastUllFlowStepActionCriteria) || !$this->lastUllFlowStepActionCriteria->equals($criteria)) {
				$this->collUllFlowStepActions = UllFlowStepActionPeer::doSelectJoinUllFlowAction($criteria, $con);
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
    $obj = $this->getCurrentUllFlowStepI18n();

    return ($obj ? $obj->getCaptionI18n() : null);
  }

  public function setCaptionI18n($value)
  {
    $this->getCurrentUllFlowStepI18n()->setCaptionI18n($value);
  }

  protected $current_i18n = array();

  public function getCurrentUllFlowStepI18n()
  {
    if (!isset($this->current_i18n[$this->culture]))
    {
      $obj = UllFlowStepI18nPeer::retrieveByPK($this->getId(), $this->culture);
      if ($obj)
      {
        $this->setUllFlowStepI18nForCulture($obj, $this->culture);
      }
      else
      {
        $this->setUllFlowStepI18nForCulture(new UllFlowStepI18n(), $this->culture);
        $this->current_i18n[$this->culture]->setCulture($this->culture);
      }
    }

    return $this->current_i18n[$this->culture];
  }

  public function setUllFlowStepI18nForCulture($object, $culture)
  {
    $this->current_i18n[$culture] = $object;
    $this->addUllFlowStepI18n($object);
  }


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseUllFlowStep:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseUllFlowStep::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseUllFlowStep
