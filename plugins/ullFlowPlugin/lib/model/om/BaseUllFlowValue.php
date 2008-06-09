<?php

/**
 * Base class that represents a row from the 'ull_flow_value' table.
 *
 * 
 *
 * @package    plugins.ullFlowPlugin.lib.model.om
 */
abstract class BaseUllFlowValue extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        UllFlowValuePeer
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
	 * The value for the ull_flow_field_id field.
	 * @var        int
	 */
	protected $ull_flow_field_id;


	/**
	 * The value for the ull_flow_memory_id field.
	 * @var        int
	 */
	protected $ull_flow_memory_id;


	/**
	 * The value for the current field.
	 * @var        boolean
	 */
	protected $current;


	/**
	 * The value for the value field.
	 * @var        string
	 */
	protected $value;


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
	 * @var        UllFlowDoc
	 */
	protected $aUllFlowDoc;

	/**
	 * @var        UllFlowField
	 */
	protected $aUllFlowField;

	/**
	 * @var        UllFlowMemory
	 */
	protected $aUllFlowMemory;

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
	 * Get the [ull_flow_field_id] column value.
	 * 
	 * @return     int
	 */
	public function getUllFlowFieldId()
	{

		return $this->ull_flow_field_id;
	}

	/**
	 * Get the [ull_flow_memory_id] column value.
	 * 
	 * @return     int
	 */
	public function getUllFlowMemoryId()
	{

		return $this->ull_flow_memory_id;
	}

	/**
	 * Get the [current] column value.
	 * 
	 * @return     boolean
	 */
	public function getCurrent()
	{

		return $this->current;
	}

	/**
	 * Get the [value] column value.
	 * 
	 * @return     string
	 */
	public function getValue()
	{

		return $this->value;
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
			$this->modifiedColumns[] = UllFlowValuePeer::ID;
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
			$this->modifiedColumns[] = UllFlowValuePeer::ULL_FLOW_DOC_ID;
		}

		if ($this->aUllFlowDoc !== null && $this->aUllFlowDoc->getId() !== $v) {
			$this->aUllFlowDoc = null;
		}

	} // setUllFlowDocId()

	/**
	 * Set the value of [ull_flow_field_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setUllFlowFieldId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->ull_flow_field_id !== $v) {
			$this->ull_flow_field_id = $v;
			$this->modifiedColumns[] = UllFlowValuePeer::ULL_FLOW_FIELD_ID;
		}

		if ($this->aUllFlowField !== null && $this->aUllFlowField->getId() !== $v) {
			$this->aUllFlowField = null;
		}

	} // setUllFlowFieldId()

	/**
	 * Set the value of [ull_flow_memory_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setUllFlowMemoryId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->ull_flow_memory_id !== $v) {
			$this->ull_flow_memory_id = $v;
			$this->modifiedColumns[] = UllFlowValuePeer::ULL_FLOW_MEMORY_ID;
		}

		if ($this->aUllFlowMemory !== null && $this->aUllFlowMemory->getId() !== $v) {
			$this->aUllFlowMemory = null;
		}

	} // setUllFlowMemoryId()

	/**
	 * Set the value of [current] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setCurrent($v)
	{

		if ($this->current !== $v) {
			$this->current = $v;
			$this->modifiedColumns[] = UllFlowValuePeer::CURRENT;
		}

	} // setCurrent()

	/**
	 * Set the value of [value] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setValue($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->value !== $v) {
			$this->value = $v;
			$this->modifiedColumns[] = UllFlowValuePeer::VALUE;
		}

	} // setValue()

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
			$this->modifiedColumns[] = UllFlowValuePeer::CREATOR_USER_ID;
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
			$this->modifiedColumns[] = UllFlowValuePeer::CREATED_AT;
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
			$this->modifiedColumns[] = UllFlowValuePeer::UPDATOR_USER_ID;
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
			$this->modifiedColumns[] = UllFlowValuePeer::UPDATED_AT;
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

			$this->ull_flow_doc_id = $rs->getInt($startcol + 1);

			$this->ull_flow_field_id = $rs->getInt($startcol + 2);

			$this->ull_flow_memory_id = $rs->getInt($startcol + 3);

			$this->current = $rs->getBoolean($startcol + 4);

			$this->value = $rs->getString($startcol + 5);

			$this->creator_user_id = $rs->getInt($startcol + 6);

			$this->created_at = $rs->getTimestamp($startcol + 7, null);

			$this->updator_user_id = $rs->getInt($startcol + 8);

			$this->updated_at = $rs->getTimestamp($startcol + 9, null);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 10; // 10 = UllFlowValuePeer::NUM_COLUMNS - UllFlowValuePeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating UllFlowValue object", $e);
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

    foreach (sfMixer::getCallables('BaseUllFlowValue:delete:pre') as $callable)
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
			$con = Propel::getConnection(UllFlowValuePeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			UllFlowValuePeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseUllFlowValue:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseUllFlowValue:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(UllFlowValuePeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

    if ($this->isModified() && !$this->isColumnModified(UllFlowValuePeer::UPDATED_AT))
    {
      $this->setUpdatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(UllFlowValuePeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseUllFlowValue:save:post') as $callable)
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

			if ($this->aUllFlowField !== null) {
				if ($this->aUllFlowField->isModified() || $this->aUllFlowField->getCurrentUllFlowFieldI18n()->isModified()) {
					$affectedRows += $this->aUllFlowField->save($con);
				}
				$this->setUllFlowField($this->aUllFlowField);
			}

			if ($this->aUllFlowMemory !== null) {
				if ($this->aUllFlowMemory->isModified()) {
					$affectedRows += $this->aUllFlowMemory->save($con);
				}
				$this->setUllFlowMemory($this->aUllFlowMemory);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = UllFlowValuePeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += UllFlowValuePeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
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

			if ($this->aUllFlowField !== null) {
				if (!$this->aUllFlowField->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aUllFlowField->getValidationFailures());
				}
			}

			if ($this->aUllFlowMemory !== null) {
				if (!$this->aUllFlowMemory->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aUllFlowMemory->getValidationFailures());
				}
			}


			if (($retval = UllFlowValuePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
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
		$pos = UllFlowValuePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getUllFlowFieldId();
				break;
			case 3:
				return $this->getUllFlowMemoryId();
				break;
			case 4:
				return $this->getCurrent();
				break;
			case 5:
				return $this->getValue();
				break;
			case 6:
				return $this->getCreatorUserId();
				break;
			case 7:
				return $this->getCreatedAt();
				break;
			case 8:
				return $this->getUpdatorUserId();
				break;
			case 9:
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
		$keys = UllFlowValuePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getUllFlowDocId(),
			$keys[2] => $this->getUllFlowFieldId(),
			$keys[3] => $this->getUllFlowMemoryId(),
			$keys[4] => $this->getCurrent(),
			$keys[5] => $this->getValue(),
			$keys[6] => $this->getCreatorUserId(),
			$keys[7] => $this->getCreatedAt(),
			$keys[8] => $this->getUpdatorUserId(),
			$keys[9] => $this->getUpdatedAt(),
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
		$pos = UllFlowValuePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setUllFlowFieldId($value);
				break;
			case 3:
				$this->setUllFlowMemoryId($value);
				break;
			case 4:
				$this->setCurrent($value);
				break;
			case 5:
				$this->setValue($value);
				break;
			case 6:
				$this->setCreatorUserId($value);
				break;
			case 7:
				$this->setCreatedAt($value);
				break;
			case 8:
				$this->setUpdatorUserId($value);
				break;
			case 9:
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
		$keys = UllFlowValuePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setUllFlowDocId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setUllFlowFieldId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setUllFlowMemoryId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setCurrent($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setValue($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setCreatorUserId($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setCreatedAt($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setUpdatorUserId($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setUpdatedAt($arr[$keys[9]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(UllFlowValuePeer::DATABASE_NAME);

		if ($this->isColumnModified(UllFlowValuePeer::ID)) $criteria->add(UllFlowValuePeer::ID, $this->id);
		if ($this->isColumnModified(UllFlowValuePeer::ULL_FLOW_DOC_ID)) $criteria->add(UllFlowValuePeer::ULL_FLOW_DOC_ID, $this->ull_flow_doc_id);
		if ($this->isColumnModified(UllFlowValuePeer::ULL_FLOW_FIELD_ID)) $criteria->add(UllFlowValuePeer::ULL_FLOW_FIELD_ID, $this->ull_flow_field_id);
		if ($this->isColumnModified(UllFlowValuePeer::ULL_FLOW_MEMORY_ID)) $criteria->add(UllFlowValuePeer::ULL_FLOW_MEMORY_ID, $this->ull_flow_memory_id);
		if ($this->isColumnModified(UllFlowValuePeer::CURRENT)) $criteria->add(UllFlowValuePeer::CURRENT, $this->current);
		if ($this->isColumnModified(UllFlowValuePeer::VALUE)) $criteria->add(UllFlowValuePeer::VALUE, $this->value);
		if ($this->isColumnModified(UllFlowValuePeer::CREATOR_USER_ID)) $criteria->add(UllFlowValuePeer::CREATOR_USER_ID, $this->creator_user_id);
		if ($this->isColumnModified(UllFlowValuePeer::CREATED_AT)) $criteria->add(UllFlowValuePeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(UllFlowValuePeer::UPDATOR_USER_ID)) $criteria->add(UllFlowValuePeer::UPDATOR_USER_ID, $this->updator_user_id);
		if ($this->isColumnModified(UllFlowValuePeer::UPDATED_AT)) $criteria->add(UllFlowValuePeer::UPDATED_AT, $this->updated_at);

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
		$criteria = new Criteria(UllFlowValuePeer::DATABASE_NAME);

		$criteria->add(UllFlowValuePeer::ID, $this->id);

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
	 * @param      object $copyObj An object of UllFlowValue (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setUllFlowDocId($this->ull_flow_doc_id);

		$copyObj->setUllFlowFieldId($this->ull_flow_field_id);

		$copyObj->setUllFlowMemoryId($this->ull_flow_memory_id);

		$copyObj->setCurrent($this->current);

		$copyObj->setValue($this->value);

		$copyObj->setCreatorUserId($this->creator_user_id);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setUpdatorUserId($this->updator_user_id);

		$copyObj->setUpdatedAt($this->updated_at);


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
	 * @return     UllFlowValue Clone of current object.
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
	 * @return     UllFlowValuePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new UllFlowValuePeer();
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
	 * Declares an association between this object and a UllFlowField object.
	 *
	 * @param      UllFlowField $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setUllFlowField($v)
	{


		if ($v === null) {
			$this->setUllFlowFieldId(NULL);
		} else {
			$this->setUllFlowFieldId($v->getId());
		}


		$this->aUllFlowField = $v;
	}


	/**
	 * Get the associated UllFlowField object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     UllFlowField The associated UllFlowField object.
	 * @throws     PropelException
	 */
	public function getUllFlowField($con = null)
	{
		if ($this->aUllFlowField === null && ($this->ull_flow_field_id !== null)) {
			// include the related Peer class
			include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowFieldPeer.php';

			$this->aUllFlowField = UllFlowFieldPeer::retrieveByPK($this->ull_flow_field_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = UllFlowFieldPeer::retrieveByPK($this->ull_flow_field_id, $con);
			   $obj->addUllFlowFields($this);
			 */
		}
		return $this->aUllFlowField;
	}

	/**
	 * Declares an association between this object and a UllFlowMemory object.
	 *
	 * @param      UllFlowMemory $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setUllFlowMemory($v)
	{


		if ($v === null) {
			$this->setUllFlowMemoryId(NULL);
		} else {
			$this->setUllFlowMemoryId($v->getId());
		}


		$this->aUllFlowMemory = $v;
	}


	/**
	 * Get the associated UllFlowMemory object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     UllFlowMemory The associated UllFlowMemory object.
	 * @throws     PropelException
	 */
	public function getUllFlowMemory($con = null)
	{
		if ($this->aUllFlowMemory === null && ($this->ull_flow_memory_id !== null)) {
			// include the related Peer class
			include_once 'plugins/ullFlowPlugin/lib/model/om/BaseUllFlowMemoryPeer.php';

			$this->aUllFlowMemory = UllFlowMemoryPeer::retrieveByPK($this->ull_flow_memory_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = UllFlowMemoryPeer::retrieveByPK($this->ull_flow_memory_id, $con);
			   $obj->addUllFlowMemorys($this);
			 */
		}
		return $this->aUllFlowMemory;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseUllFlowValue:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseUllFlowValue::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseUllFlowValue
