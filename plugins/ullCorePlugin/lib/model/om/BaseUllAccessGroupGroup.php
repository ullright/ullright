<?php

/**
 * Base class that represents a row from the 'ull_access_group_group' table.
 *
 * 
 *
 * @package    plugins.ullCorePlugin.lib.model.om
 */
abstract class BaseUllAccessGroupGroup extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        UllAccessGroupGroupPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;


	/**
	 * The value for the ull_access_group_id field.
	 * @var        int
	 */
	protected $ull_access_group_id;


	/**
	 * The value for the ull_group_id field.
	 * @var        int
	 */
	protected $ull_group_id;


	/**
	 * The value for the read_flag field.
	 * @var        boolean
	 */
	protected $read_flag;


	/**
	 * The value for the write_flag field.
	 * @var        boolean
	 */
	protected $write_flag;

	/**
	 * @var        UllAccessGroup
	 */
	protected $aUllAccessGroup;

	/**
	 * @var        UllGroup
	 */
	protected $aUllGroup;

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
	 * Get the [ull_access_group_id] column value.
	 * 
	 * @return     int
	 */
	public function getUllAccessGroupId()
	{

		return $this->ull_access_group_id;
	}

	/**
	 * Get the [ull_group_id] column value.
	 * 
	 * @return     int
	 */
	public function getUllGroupId()
	{

		return $this->ull_group_id;
	}

	/**
	 * Get the [read_flag] column value.
	 * 
	 * @return     boolean
	 */
	public function getReadFlag()
	{

		return $this->read_flag;
	}

	/**
	 * Get the [write_flag] column value.
	 * 
	 * @return     boolean
	 */
	public function getWriteFlag()
	{

		return $this->write_flag;
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
			$this->modifiedColumns[] = UllAccessGroupGroupPeer::ID;
		}

	} // setId()

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
			$this->modifiedColumns[] = UllAccessGroupGroupPeer::ULL_ACCESS_GROUP_ID;
		}

		if ($this->aUllAccessGroup !== null && $this->aUllAccessGroup->getId() !== $v) {
			$this->aUllAccessGroup = null;
		}

	} // setUllAccessGroupId()

	/**
	 * Set the value of [ull_group_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setUllGroupId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->ull_group_id !== $v) {
			$this->ull_group_id = $v;
			$this->modifiedColumns[] = UllAccessGroupGroupPeer::ULL_GROUP_ID;
		}

		if ($this->aUllGroup !== null && $this->aUllGroup->getId() !== $v) {
			$this->aUllGroup = null;
		}

	} // setUllGroupId()

	/**
	 * Set the value of [read_flag] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setReadFlag($v)
	{

		if ($this->read_flag !== $v) {
			$this->read_flag = $v;
			$this->modifiedColumns[] = UllAccessGroupGroupPeer::READ_FLAG;
		}

	} // setReadFlag()

	/**
	 * Set the value of [write_flag] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setWriteFlag($v)
	{

		if ($this->write_flag !== $v) {
			$this->write_flag = $v;
			$this->modifiedColumns[] = UllAccessGroupGroupPeer::WRITE_FLAG;
		}

	} // setWriteFlag()

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

			$this->ull_access_group_id = $rs->getInt($startcol + 1);

			$this->ull_group_id = $rs->getInt($startcol + 2);

			$this->read_flag = $rs->getBoolean($startcol + 3);

			$this->write_flag = $rs->getBoolean($startcol + 4);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 5; // 5 = UllAccessGroupGroupPeer::NUM_COLUMNS - UllAccessGroupGroupPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating UllAccessGroupGroup object", $e);
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

    foreach (sfMixer::getCallables('BaseUllAccessGroupGroup:delete:pre') as $callable)
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
			$con = Propel::getConnection(UllAccessGroupGroupPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			UllAccessGroupGroupPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseUllAccessGroupGroup:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseUllAccessGroupGroup:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(UllAccessGroupGroupPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseUllAccessGroupGroup:save:post') as $callable)
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

			if ($this->aUllAccessGroup !== null) {
				if ($this->aUllAccessGroup->isModified()) {
					$affectedRows += $this->aUllAccessGroup->save($con);
				}
				$this->setUllAccessGroup($this->aUllAccessGroup);
			}

			if ($this->aUllGroup !== null) {
				if ($this->aUllGroup->isModified()) {
					$affectedRows += $this->aUllGroup->save($con);
				}
				$this->setUllGroup($this->aUllGroup);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = UllAccessGroupGroupPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += UllAccessGroupGroupPeer::doUpdate($this, $con);
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

			if ($this->aUllAccessGroup !== null) {
				if (!$this->aUllAccessGroup->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aUllAccessGroup->getValidationFailures());
				}
			}

			if ($this->aUllGroup !== null) {
				if (!$this->aUllGroup->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aUllGroup->getValidationFailures());
				}
			}


			if (($retval = UllAccessGroupGroupPeer::doValidate($this, $columns)) !== true) {
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
		$pos = UllAccessGroupGroupPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getUllAccessGroupId();
				break;
			case 2:
				return $this->getUllGroupId();
				break;
			case 3:
				return $this->getReadFlag();
				break;
			case 4:
				return $this->getWriteFlag();
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
		$keys = UllAccessGroupGroupPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getUllAccessGroupId(),
			$keys[2] => $this->getUllGroupId(),
			$keys[3] => $this->getReadFlag(),
			$keys[4] => $this->getWriteFlag(),
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
		$pos = UllAccessGroupGroupPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setUllAccessGroupId($value);
				break;
			case 2:
				$this->setUllGroupId($value);
				break;
			case 3:
				$this->setReadFlag($value);
				break;
			case 4:
				$this->setWriteFlag($value);
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
		$keys = UllAccessGroupGroupPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setUllAccessGroupId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setUllGroupId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setReadFlag($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setWriteFlag($arr[$keys[4]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(UllAccessGroupGroupPeer::DATABASE_NAME);

		if ($this->isColumnModified(UllAccessGroupGroupPeer::ID)) $criteria->add(UllAccessGroupGroupPeer::ID, $this->id);
		if ($this->isColumnModified(UllAccessGroupGroupPeer::ULL_ACCESS_GROUP_ID)) $criteria->add(UllAccessGroupGroupPeer::ULL_ACCESS_GROUP_ID, $this->ull_access_group_id);
		if ($this->isColumnModified(UllAccessGroupGroupPeer::ULL_GROUP_ID)) $criteria->add(UllAccessGroupGroupPeer::ULL_GROUP_ID, $this->ull_group_id);
		if ($this->isColumnModified(UllAccessGroupGroupPeer::READ_FLAG)) $criteria->add(UllAccessGroupGroupPeer::READ_FLAG, $this->read_flag);
		if ($this->isColumnModified(UllAccessGroupGroupPeer::WRITE_FLAG)) $criteria->add(UllAccessGroupGroupPeer::WRITE_FLAG, $this->write_flag);

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
		$criteria = new Criteria(UllAccessGroupGroupPeer::DATABASE_NAME);

		$criteria->add(UllAccessGroupGroupPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of UllAccessGroupGroup (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setUllAccessGroupId($this->ull_access_group_id);

		$copyObj->setUllGroupId($this->ull_group_id);

		$copyObj->setReadFlag($this->read_flag);

		$copyObj->setWriteFlag($this->write_flag);


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
	 * @return     UllAccessGroupGroup Clone of current object.
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
	 * @return     UllAccessGroupGroupPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new UllAccessGroupGroupPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a UllAccessGroup object.
	 *
	 * @param      UllAccessGroup $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setUllAccessGroup($v)
	{


		if ($v === null) {
			$this->setUllAccessGroupId(NULL);
		} else {
			$this->setUllAccessGroupId($v->getId());
		}


		$this->aUllAccessGroup = $v;
	}


	/**
	 * Get the associated UllAccessGroup object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     UllAccessGroup The associated UllAccessGroup object.
	 * @throws     PropelException
	 */
	public function getUllAccessGroup($con = null)
	{
		if ($this->aUllAccessGroup === null && ($this->ull_access_group_id !== null)) {
			// include the related Peer class
			include_once 'plugins/ullCorePlugin/lib/model/om/BaseUllAccessGroupPeer.php';

			$this->aUllAccessGroup = UllAccessGroupPeer::retrieveByPK($this->ull_access_group_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = UllAccessGroupPeer::retrieveByPK($this->ull_access_group_id, $con);
			   $obj->addUllAccessGroups($this);
			 */
		}
		return $this->aUllAccessGroup;
	}

	/**
	 * Declares an association between this object and a UllGroup object.
	 *
	 * @param      UllGroup $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setUllGroup($v)
	{


		if ($v === null) {
			$this->setUllGroupId(NULL);
		} else {
			$this->setUllGroupId($v->getId());
		}


		$this->aUllGroup = $v;
	}


	/**
	 * Get the associated UllGroup object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     UllGroup The associated UllGroup object.
	 * @throws     PropelException
	 */
	public function getUllGroup($con = null)
	{
		if ($this->aUllGroup === null && ($this->ull_group_id !== null)) {
			// include the related Peer class
			include_once 'plugins/ullCorePlugin/lib/model/om/BaseUllGroupPeer.php';

			$this->aUllGroup = UllGroupPeer::retrieveByPK($this->ull_group_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = UllGroupPeer::retrieveByPK($this->ull_group_id, $con);
			   $obj->addUllGroups($this);
			 */
		}
		return $this->aUllGroup;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseUllAccessGroupGroup:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseUllAccessGroupGroup::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseUllAccessGroupGroup
