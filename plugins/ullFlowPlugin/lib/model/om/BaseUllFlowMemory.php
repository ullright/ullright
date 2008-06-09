<?php

/**
 * Base class that represents a row from the 'ull_flow_memory' table.
 *
 * 
 *
 * @package    plugins.ullFlowPlugin.lib.model.om
 */
abstract class BaseUllFlowMemory extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        UllFlowMemoryPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;


	/**
	 * The value for the ull_flow_doc_id field.
	 * @var        int
	 */
	protected $ull_flow_doc_id;


	/**
	 * The value for the ull_flow_step_id field.
	 * @var        int
	 */
	protected $ull_flow_step_id;


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
	 * The value for the comment field.
	 * @var        string
	 */
	protected $comment;


	/**
	 * The value for the creator_group_id field.
	 * @var        int
	 */
	protected $creator_group_id;


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
	 * @var        UllFlowDoc
	 */
	protected $aUllFlowDoc;

	/**
	 * @var        UllFlowStep
	 */
	protected $aUllFlowStep;

	/**
	 * @var        UllFlowAction
	 */
	protected $aUllFlowAction;

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
	 * Get the [ull_flow_doc_id] column value.
	 * 
	 * @return     int
	 */
	public function getUllFlowDocId()
	{

		return $this->ull_flow_doc_id;
	}

	/**
	 * Get the [ull_flow_step_id] column value.
	 * 
	 * @return     int
	 */
	public function getUllFlowStepId()
	{

		return $this->ull_flow_step_id;
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
	 * Get the [comment] column value.
	 * 
	 * @return     string
	 */
	public function getComment()
	{

		return $this->comment;
	}

	/**
	 * Get the [creator_group_id] column value.
	 * 
	 * @return     int
	 */
	public function getCreatorGroupId()
	{

		return $this->creator_group_id;
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
			$this->modifiedColumns[] = UllFlowMemoryPeer::ID;
		}

	} // setId()

	/**
	 * Set the value of [ull_flow_doc_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setUllFlowDocId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->ull_flow_doc_id !== $v) {
			$this->ull_flow_doc_id = $v;
			$this->modifiedColumns[] = UllFlowMemoryPeer::ULL_FLOW_DOC_ID;
		}

		if ($this->aUllFlowDoc !== null && $this->aUllFlowDoc->getId() !== $v) {
			$this->aUllFlowDoc = null;
		}

	} // setUllFlowDocId()

	/**
	 * Set the value of [ull_flow_step_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setUllFlowStepId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->ull_flow_step_id !== $v) {
			$this->ull_flow_step_id = $v;
			$this->modifiedColumns[] = UllFlowMemoryPeer::ULL_FLOW_STEP_ID;
		}

		if ($this->aUllFlowStep !== null && $this->aUllFlowStep->getId() !== $v) {
			$this->aUllFlowStep = null;
		}

	} // setUllFlowStepId()

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
			$this->modifiedColumns[] = UllFlowMemoryPeer::ULL_FLOW_ACTION_ID;
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
			$this->modifiedColumns[] = UllFlowMemoryPeer::ASSIGNED_TO_ULL_USER_ID;
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
			$this->modifiedColumns[] = UllFlowMemoryPeer::ASSIGNED_TO_ULL_GROUP_ID;
		}

	} // setAssignedToUllGroupId()

	/**
	 * Set the value of [comment] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setComment($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->comment !== $v) {
			$this->comment = $v;
			$this->modifiedColumns[] = UllFlowMemoryPeer::COMMENT;
		}

	} // setComment()

	/**
	 * Set the value of [creator_group_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setCreatorGroupId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->creator_group_id !== $v) {
			$this->creator_group_id = $v;
			$this->modifiedColumns[] = UllFlowMemoryPeer::CREATOR_GROUP_ID;
		}

	} // setCreatorGroupId()

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
			$this->modifiedColumns[] = UllFlowMemoryPeer::CREATOR_USER_ID;
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
			$this->modifiedColumns[] = UllFlowMemoryPeer::CREATED_AT;
		}

	} // setCreatedAt()

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

			$this->ull_flow_doc_id = $rs->getInt($startcol + 1);

			$this->ull_flow_step_id = $rs->getInt($startcol + 2);

			$this->ull_flow_action_id = $rs->getInt($startcol + 3);

			$this->assigned_to_ull_user_id = $rs->getInt($startcol + 4);

			$this->assigned_to_ull_group_id = $rs->getInt($startcol + 5);

			$this->comment = $rs->getString($startcol + 6);

			$this->creator_group_id = $rs->getInt($startcol + 7);

			$this->creator_user_id = $rs->getInt($startcol + 8);

			$this->created_at = $rs->getTimestamp($startcol + 9, null);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 10; // 10 = UllFlowMemoryPeer::NUM_COLUMNS - UllFlowMemoryPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating UllFlowMemory object", $e);
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

    foreach (sfMixer::getCallables('BaseUllFlowMemory:delete:pre') as $callable)
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
			$con = Propel::getConnection(UllFlowMemoryPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			UllFlowMemoryPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseUllFlowMemory:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseUllFlowMemory:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(UllFlowMemoryPeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(UllFlowMemoryPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseUllFlowMemory:save:post') as $callable)
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

			if ($this->aUllFlowDoc !== null) {
				if ($this->aUllFlowDoc->isModified()) {
					$affectedRows += $this->aUllFlowDoc->save($con);
				}
				$this->setUllFlowDoc($this->aUllFlowDoc);
			}

			if ($this->aUllFlowStep !== null) {
				if ($this->aUllFlowStep->isModified() || $this->aUllFlowStep->getCurrentUllFlowStepI18n()->isModified()) {
					$affectedRows += $this->aUllFlowStep->save($con);
				}
				$this->setUllFlowStep($this->aUllFlowStep);
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
					$pk = UllFlowMemoryPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += UllFlowMemoryPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
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

			if ($this->aUllFlowDoc !== null) {
				if (!$this->aUllFlowDoc->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aUllFlowDoc->getValidationFailures());
				}
			}

			if ($this->aUllFlowStep !== null) {
				if (!$this->aUllFlowStep->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aUllFlowStep->getValidationFailures());
				}
			}

			if ($this->aUllFlowAction !== null) {
				if (!$this->aUllFlowAction->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aUllFlowAction->getValidationFailures());
				}
			}


			if (($retval = UllFlowMemoryPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
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
		$pos = UllFlowMemoryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getUllFlowDocId();
				break;
			case 2:
				return $this->getUllFlowStepId();
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
				return $this->getComment();
				break;
			case 7:
				return $this->getCreatorGroupId();
				break;
			case 8:
				return $this->getCreatorUserId();
				break;
			case 9:
				return $this->getCreatedAt();
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
		$keys = UllFlowMemoryPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getUllFlowDocId(),
			$keys[2] => $this->getUllFlowStepId(),
			$keys[3] => $this->getUllFlowActionId(),
			$keys[4] => $this->getAssignedToUllUserId(),
			$keys[5] => $this->getAssignedToUllGroupId(),
			$keys[6] => $this->getComment(),
			$keys[7] => $this->getCreatorGroupId(),
			$keys[8] => $this->getCreatorUserId(),
			$keys[9] => $this->getCreatedAt(),
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
		$pos = UllFlowMemoryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setUllFlowDocId($value);
				break;
			case 2:
				$this->setUllFlowStepId($value);
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
				$this->setComment($value);
				break;
			case 7:
				$this->setCreatorGroupId($value);
				break;
			case 8:
				$this->setCreatorUserId($value);
				break;
			case 9:
				$this->setCreatedAt($value);
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
		$keys = UllFlowMemoryPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setUllFlowDocId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setUllFlowStepId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setUllFlowActionId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setAssignedToUllUserId($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setAssignedToUllGroupId($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setComment($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setCreatorGroupId($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setCreatorUserId($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setCreatedAt($arr[$keys[9]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(UllFlowMemoryPeer::DATABASE_NAME);

		if ($this->isColumnModified(UllFlowMemoryPeer::ID)) $criteria->add(UllFlowMemoryPeer::ID, $this->id);
		if ($this->isColumnModified(UllFlowMemoryPeer::ULL_FLOW_DOC_ID)) $criteria->add(UllFlowMemoryPeer::ULL_FLOW_DOC_ID, $this->ull_flow_doc_id);
		if ($this->isColumnModified(UllFlowMemoryPeer::ULL_FLOW_STEP_ID)) $criteria->add(UllFlowMemoryPeer::ULL_FLOW_STEP_ID, $this->ull_flow_step_id);
		if ($this->isColumnModified(UllFlowMemoryPeer::ULL_FLOW_ACTION_ID)) $criteria->add(UllFlowMemoryPeer::ULL_FLOW_ACTION_ID, $this->ull_flow_action_id);
		if ($this->isColumnModified(UllFlowMemoryPeer::ASSIGNED_TO_ULL_USER_ID)) $criteria->add(UllFlowMemoryPeer::ASSIGNED_TO_ULL_USER_ID, $this->assigned_to_ull_user_id);
		if ($this->isColumnModified(UllFlowMemoryPeer::ASSIGNED_TO_ULL_GROUP_ID)) $criteria->add(UllFlowMemoryPeer::ASSIGNED_TO_ULL_GROUP_ID, $this->assigned_to_ull_group_id);
		if ($this->isColumnModified(UllFlowMemoryPeer::COMMENT)) $criteria->add(UllFlowMemoryPeer::COMMENT, $this->comment);
		if ($this->isColumnModified(UllFlowMemoryPeer::CREATOR_GROUP_ID)) $criteria->add(UllFlowMemoryPeer::CREATOR_GROUP_ID, $this->creator_group_id);
		if ($this->isColumnModified(UllFlowMemoryPeer::CREATOR_USER_ID)) $criteria->add(UllFlowMemoryPeer::CREATOR_USER_ID, $this->creator_user_id);
		if ($this->isColumnModified(UllFlowMemoryPeer::CREATED_AT)) $criteria->add(UllFlowMemoryPeer::CREATED_AT, $this->created_at);

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
		$criteria = new Criteria(UllFlowMemoryPeer::DATABASE_NAME);

		$criteria->add(UllFlowMemoryPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of UllFlowMemory (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setUllFlowDocId($this->ull_flow_doc_id);

		$copyObj->setUllFlowStepId($this->ull_flow_step_id);

		$copyObj->setUllFlowActionId($this->ull_flow_action_id);

		$copyObj->setAssignedToUllUserId($this->assigned_to_ull_user_id);

		$copyObj->setAssignedToUllGroupId($this->assigned_to_ull_group_id);

		$copyObj->setComment($this->comment);

		$copyObj->setCreatorGroupId($this->creator_group_id);

		$copyObj->setCreatorUserId($this->creator_user_id);

		$copyObj->setCreatedAt($this->created_at);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

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
	 * @return     UllFlowMemory Clone of current object.
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
	 * @return     UllFlowMemoryPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new UllFlowMemoryPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a UllFlowDoc object.
	 *
	 * @param      UllFlowDoc $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setUllFlowDoc($v)
	{


		if ($v === null) {
			$this->setUllFlowDocId(NULL);
		} else {
			$this->setUllFlowDocId($v->getId());
		}


		$this->aUllFlowDoc = $v;
	}


	/**
	 * Get the associated UllFlowDoc object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     UllFlowDoc The associated UllFlowDoc object.
	 * @throws     PropelException
	 */
	public function getUllFlowDoc($con = null)
	{
		if ($this->aUllFlowDoc === null && ($this->ull_flow_doc_id !== null)) {
			// include the related Peer class
			include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowDocPeer.php';

			$this->aUllFlowDoc = UllFlowDocPeer::retrieveByPK($this->ull_flow_doc_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = UllFlowDocPeer::retrieveByPK($this->ull_flow_doc_id, $con);
			   $obj->addUllFlowDocs($this);
			 */
		}
		return $this->aUllFlowDoc;
	}

	/**
	 * Declares an association between this object and a UllFlowStep object.
	 *
	 * @param      UllFlowStep $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setUllFlowStep($v)
	{


		if ($v === null) {
			$this->setUllFlowStepId(NULL);
		} else {
			$this->setUllFlowStepId($v->getId());
		}


		$this->aUllFlowStep = $v;
	}


	/**
	 * Get the associated UllFlowStep object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     UllFlowStep The associated UllFlowStep object.
	 * @throws     PropelException
	 */
	public function getUllFlowStep($con = null)
	{
		if ($this->aUllFlowStep === null && ($this->ull_flow_step_id !== null)) {
			// include the related Peer class
			include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowStepPeer.php';

			$this->aUllFlowStep = UllFlowStepPeer::retrieveByPK($this->ull_flow_step_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = UllFlowStepPeer::retrieveByPK($this->ull_flow_step_id, $con);
			   $obj->addUllFlowSteps($this);
			 */
		}
		return $this->aUllFlowStep;
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
	 * Otherwise if this UllFlowMemory has previously
	 * been saved, it will retrieve related UllFlowValues from storage.
	 * If this UllFlowMemory is new, it will return
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

				$criteria->add(UllFlowValuePeer::ULL_FLOW_MEMORY_ID, $this->getId());

				UllFlowValuePeer::addSelectColumns($criteria);
				$this->collUllFlowValues = UllFlowValuePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(UllFlowValuePeer::ULL_FLOW_MEMORY_ID, $this->getId());

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

		$criteria->add(UllFlowValuePeer::ULL_FLOW_MEMORY_ID, $this->getId());

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
		$l->setUllFlowMemory($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllFlowMemory is new, it will return
	 * an empty collection; or if this UllFlowMemory has previously
	 * been saved, it will retrieve related UllFlowValues from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in UllFlowMemory.
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

				$criteria->add(UllFlowValuePeer::ULL_FLOW_MEMORY_ID, $this->getId());

				$this->collUllFlowValues = UllFlowValuePeer::doSelectJoinUllFlowDoc($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(UllFlowValuePeer::ULL_FLOW_MEMORY_ID, $this->getId());

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
	 * Otherwise if this UllFlowMemory is new, it will return
	 * an empty collection; or if this UllFlowMemory has previously
	 * been saved, it will retrieve related UllFlowValues from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in UllFlowMemory.
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

				$criteria->add(UllFlowValuePeer::ULL_FLOW_MEMORY_ID, $this->getId());

				$this->collUllFlowValues = UllFlowValuePeer::doSelectJoinUllFlowField($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(UllFlowValuePeer::ULL_FLOW_MEMORY_ID, $this->getId());

			if (!isset($this->lastUllFlowValueCriteria) || !$this->lastUllFlowValueCriteria->equals($criteria)) {
				$this->collUllFlowValues = UllFlowValuePeer::doSelectJoinUllFlowField($criteria, $con);
			}
		}
		$this->lastUllFlowValueCriteria = $criteria;

		return $this->collUllFlowValues;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseUllFlowMemory:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseUllFlowMemory::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseUllFlowMemory
