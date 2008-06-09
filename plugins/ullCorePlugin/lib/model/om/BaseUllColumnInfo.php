<?php

/**
 * Base class that represents a row from the 'ull_column_info' table.
 *
 * 
 *
 * @package    plugins.ullCorePlugin.lib.model.om
 */
abstract class BaseUllColumnInfo extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        UllColumnInfoPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;


	/**
	 * The value for the db_table_name field.
	 * @var        string
	 */
	protected $db_table_name;


	/**
	 * The value for the db_column_name field.
	 * @var        string
	 */
	protected $db_column_name;


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
	 * The value for the enabled field.
	 * @var        boolean
	 */
	protected $enabled;


	/**
	 * The value for the show_in_list field.
	 * @var        boolean
	 */
	protected $show_in_list;


	/**
	 * The value for the mandatory field.
	 * @var        boolean
	 */
	protected $mandatory;


	/**
	 * The value for the caption_i18n_default field.
	 * @var        string
	 */
	protected $caption_i18n_default;


	/**
	 * The value for the description_i18n_default field.
	 * @var        string
	 */
	protected $description_i18n_default;

	/**
	 * @var        UllField
	 */
	protected $aUllField;

	/**
	 * Collection to store aggregation of collUllColumnInfoI18ns.
	 * @var        array
	 */
	protected $collUllColumnInfoI18ns;

	/**
	 * The criteria used to select the current contents of collUllColumnInfoI18ns.
	 * @var        Criteria
	 */
	protected $lastUllColumnInfoI18nCriteria = null;

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
	 * Get the [db_table_name] column value.
	 * 
	 * @return     string
	 */
	public function getDbTableName()
	{

		return $this->db_table_name;
	}

	/**
	 * Get the [db_column_name] column value.
	 * 
	 * @return     string
	 */
	public function getDbColumnName()
	{

		return $this->db_column_name;
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
	 * Get the [enabled] column value.
	 * 
	 * @return     boolean
	 */
	public function getEnabled()
	{

		return $this->enabled;
	}

	/**
	 * Get the [show_in_list] column value.
	 * 
	 * @return     boolean
	 */
	public function getShowInList()
	{

		return $this->show_in_list;
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
	 * Get the [caption_i18n_default] column value.
	 * 
	 * @return     string
	 */
	public function getCaptionI18nDefault()
	{

		return $this->caption_i18n_default;
	}

	/**
	 * Get the [description_i18n_default] column value.
	 * 
	 * @return     string
	 */
	public function getDescriptionI18nDefault()
	{

		return $this->description_i18n_default;
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
			$this->modifiedColumns[] = UllColumnInfoPeer::ID;
		}

	} // setId()

	/**
	 * Set the value of [db_table_name] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setDbTableName($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->db_table_name !== $v) {
			$this->db_table_name = $v;
			$this->modifiedColumns[] = UllColumnInfoPeer::DB_TABLE_NAME;
		}

	} // setDbTableName()

	/**
	 * Set the value of [db_column_name] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setDbColumnName($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->db_column_name !== $v) {
			$this->db_column_name = $v;
			$this->modifiedColumns[] = UllColumnInfoPeer::DB_COLUMN_NAME;
		}

	} // setDbColumnName()

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
			$this->modifiedColumns[] = UllColumnInfoPeer::ULL_FIELD_ID;
		}

		if ($this->aUllField !== null && $this->aUllField->getId() !== $v) {
			$this->aUllField = null;
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
			$this->modifiedColumns[] = UllColumnInfoPeer::OPTIONS;
		}

	} // setOptions()

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
			$this->modifiedColumns[] = UllColumnInfoPeer::ENABLED;
		}

	} // setEnabled()

	/**
	 * Set the value of [show_in_list] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setShowInList($v)
	{

		if ($this->show_in_list !== $v) {
			$this->show_in_list = $v;
			$this->modifiedColumns[] = UllColumnInfoPeer::SHOW_IN_LIST;
		}

	} // setShowInList()

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
			$this->modifiedColumns[] = UllColumnInfoPeer::MANDATORY;
		}

	} // setMandatory()

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
			$this->modifiedColumns[] = UllColumnInfoPeer::CAPTION_I18N_DEFAULT;
		}

	} // setCaptionI18nDefault()

	/**
	 * Set the value of [description_i18n_default] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setDescriptionI18nDefault($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->description_i18n_default !== $v) {
			$this->description_i18n_default = $v;
			$this->modifiedColumns[] = UllColumnInfoPeer::DESCRIPTION_I18N_DEFAULT;
		}

	} // setDescriptionI18nDefault()

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

			$this->db_table_name = $rs->getString($startcol + 1);

			$this->db_column_name = $rs->getString($startcol + 2);

			$this->ull_field_id = $rs->getInt($startcol + 3);

			$this->options = $rs->getString($startcol + 4);

			$this->enabled = $rs->getBoolean($startcol + 5);

			$this->show_in_list = $rs->getBoolean($startcol + 6);

			$this->mandatory = $rs->getBoolean($startcol + 7);

			$this->caption_i18n_default = $rs->getString($startcol + 8);

			$this->description_i18n_default = $rs->getString($startcol + 9);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 10; // 10 = UllColumnInfoPeer::NUM_COLUMNS - UllColumnInfoPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating UllColumnInfo object", $e);
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

    foreach (sfMixer::getCallables('BaseUllColumnInfo:delete:pre') as $callable)
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
			$con = Propel::getConnection(UllColumnInfoPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			UllColumnInfoPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseUllColumnInfo:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseUllColumnInfo:save:pre') as $callable)
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
			$con = Propel::getConnection(UllColumnInfoPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseUllColumnInfo:save:post') as $callable)
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

			if ($this->aUllField !== null) {
				if ($this->aUllField->isModified() || $this->aUllField->getCurrentUllFieldI18n()->isModified()) {
					$affectedRows += $this->aUllField->save($con);
				}
				$this->setUllField($this->aUllField);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = UllColumnInfoPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += UllColumnInfoPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collUllColumnInfoI18ns !== null) {
				foreach($this->collUllColumnInfoI18ns as $referrerFK) {
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

			if ($this->aUllField !== null) {
				if (!$this->aUllField->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aUllField->getValidationFailures());
				}
			}


			if (($retval = UllColumnInfoPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collUllColumnInfoI18ns !== null) {
					foreach($this->collUllColumnInfoI18ns as $referrerFK) {
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
		$pos = UllColumnInfoPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getDbTableName();
				break;
			case 2:
				return $this->getDbColumnName();
				break;
			case 3:
				return $this->getUllFieldId();
				break;
			case 4:
				return $this->getOptions();
				break;
			case 5:
				return $this->getEnabled();
				break;
			case 6:
				return $this->getShowInList();
				break;
			case 7:
				return $this->getMandatory();
				break;
			case 8:
				return $this->getCaptionI18nDefault();
				break;
			case 9:
				return $this->getDescriptionI18nDefault();
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
		$keys = UllColumnInfoPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getDbTableName(),
			$keys[2] => $this->getDbColumnName(),
			$keys[3] => $this->getUllFieldId(),
			$keys[4] => $this->getOptions(),
			$keys[5] => $this->getEnabled(),
			$keys[6] => $this->getShowInList(),
			$keys[7] => $this->getMandatory(),
			$keys[8] => $this->getCaptionI18nDefault(),
			$keys[9] => $this->getDescriptionI18nDefault(),
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
		$pos = UllColumnInfoPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setDbTableName($value);
				break;
			case 2:
				$this->setDbColumnName($value);
				break;
			case 3:
				$this->setUllFieldId($value);
				break;
			case 4:
				$this->setOptions($value);
				break;
			case 5:
				$this->setEnabled($value);
				break;
			case 6:
				$this->setShowInList($value);
				break;
			case 7:
				$this->setMandatory($value);
				break;
			case 8:
				$this->setCaptionI18nDefault($value);
				break;
			case 9:
				$this->setDescriptionI18nDefault($value);
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
		$keys = UllColumnInfoPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setDbTableName($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setDbColumnName($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setUllFieldId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setOptions($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setEnabled($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setShowInList($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setMandatory($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setCaptionI18nDefault($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setDescriptionI18nDefault($arr[$keys[9]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(UllColumnInfoPeer::DATABASE_NAME);

		if ($this->isColumnModified(UllColumnInfoPeer::ID)) $criteria->add(UllColumnInfoPeer::ID, $this->id);
		if ($this->isColumnModified(UllColumnInfoPeer::DB_TABLE_NAME)) $criteria->add(UllColumnInfoPeer::DB_TABLE_NAME, $this->db_table_name);
		if ($this->isColumnModified(UllColumnInfoPeer::DB_COLUMN_NAME)) $criteria->add(UllColumnInfoPeer::DB_COLUMN_NAME, $this->db_column_name);
		if ($this->isColumnModified(UllColumnInfoPeer::ULL_FIELD_ID)) $criteria->add(UllColumnInfoPeer::ULL_FIELD_ID, $this->ull_field_id);
		if ($this->isColumnModified(UllColumnInfoPeer::OPTIONS)) $criteria->add(UllColumnInfoPeer::OPTIONS, $this->options);
		if ($this->isColumnModified(UllColumnInfoPeer::ENABLED)) $criteria->add(UllColumnInfoPeer::ENABLED, $this->enabled);
		if ($this->isColumnModified(UllColumnInfoPeer::SHOW_IN_LIST)) $criteria->add(UllColumnInfoPeer::SHOW_IN_LIST, $this->show_in_list);
		if ($this->isColumnModified(UllColumnInfoPeer::MANDATORY)) $criteria->add(UllColumnInfoPeer::MANDATORY, $this->mandatory);
		if ($this->isColumnModified(UllColumnInfoPeer::CAPTION_I18N_DEFAULT)) $criteria->add(UllColumnInfoPeer::CAPTION_I18N_DEFAULT, $this->caption_i18n_default);
		if ($this->isColumnModified(UllColumnInfoPeer::DESCRIPTION_I18N_DEFAULT)) $criteria->add(UllColumnInfoPeer::DESCRIPTION_I18N_DEFAULT, $this->description_i18n_default);

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
		$criteria = new Criteria(UllColumnInfoPeer::DATABASE_NAME);

		$criteria->add(UllColumnInfoPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of UllColumnInfo (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setDbTableName($this->db_table_name);

		$copyObj->setDbColumnName($this->db_column_name);

		$copyObj->setUllFieldId($this->ull_field_id);

		$copyObj->setOptions($this->options);

		$copyObj->setEnabled($this->enabled);

		$copyObj->setShowInList($this->show_in_list);

		$copyObj->setMandatory($this->mandatory);

		$copyObj->setCaptionI18nDefault($this->caption_i18n_default);

		$copyObj->setDescriptionI18nDefault($this->description_i18n_default);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach($this->getUllColumnInfoI18ns() as $relObj) {
				$copyObj->addUllColumnInfoI18n($relObj->copy($deepCopy));
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
	 * @return     UllColumnInfo Clone of current object.
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
	 * @return     UllColumnInfoPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new UllColumnInfoPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a UllField object.
	 *
	 * @param      UllField $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setUllField($v)
	{


		if ($v === null) {
			$this->setUllFieldId(NULL);
		} else {
			$this->setUllFieldId($v->getId());
		}


		$this->aUllField = $v;
	}


	/**
	 * Get the associated UllField object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     UllField The associated UllField object.
	 * @throws     PropelException
	 */
	public function getUllField($con = null)
	{
		if ($this->aUllField === null && ($this->ull_field_id !== null)) {
			// include the related Peer class
			include_once 'plugins/ullCorePlugin/lib/model/om/BaseUllFieldPeer.php';

			$this->aUllField = UllFieldPeer::retrieveByPK($this->ull_field_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = UllFieldPeer::retrieveByPK($this->ull_field_id, $con);
			   $obj->addUllFields($this);
			 */
		}
		return $this->aUllField;
	}

	/**
	 * Temporary storage of collUllColumnInfoI18ns to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initUllColumnInfoI18ns()
	{
		if ($this->collUllColumnInfoI18ns === null) {
			$this->collUllColumnInfoI18ns = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this UllColumnInfo has previously
	 * been saved, it will retrieve related UllColumnInfoI18ns from storage.
	 * If this UllColumnInfo is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getUllColumnInfoI18ns($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullCorePlugin/lib/model/om/BaseUllColumnInfoI18nPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collUllColumnInfoI18ns === null) {
			if ($this->isNew()) {
			   $this->collUllColumnInfoI18ns = array();
			} else {

				$criteria->add(UllColumnInfoI18nPeer::ID, $this->getId());

				UllColumnInfoI18nPeer::addSelectColumns($criteria);
				$this->collUllColumnInfoI18ns = UllColumnInfoI18nPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(UllColumnInfoI18nPeer::ID, $this->getId());

				UllColumnInfoI18nPeer::addSelectColumns($criteria);
				if (!isset($this->lastUllColumnInfoI18nCriteria) || !$this->lastUllColumnInfoI18nCriteria->equals($criteria)) {
					$this->collUllColumnInfoI18ns = UllColumnInfoI18nPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastUllColumnInfoI18nCriteria = $criteria;
		return $this->collUllColumnInfoI18ns;
	}

	/**
	 * Returns the number of related UllColumnInfoI18ns.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countUllColumnInfoI18ns($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ullCorePlugin/lib/model/om/BaseUllColumnInfoI18nPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(UllColumnInfoI18nPeer::ID, $this->getId());

		return UllColumnInfoI18nPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a UllColumnInfoI18n object to this object
	 * through the UllColumnInfoI18n foreign key attribute
	 *
	 * @param      UllColumnInfoI18n $l UllColumnInfoI18n
	 * @return     void
	 * @throws     PropelException
	 */
	public function addUllColumnInfoI18n(UllColumnInfoI18n $l)
	{
		$this->collUllColumnInfoI18ns[] = $l;
		$l->setUllColumnInfo($this);
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
    $obj = $this->getCurrentUllColumnInfoI18n();

    return ($obj ? $obj->getCaptionI18n() : null);
  }

  public function setCaptionI18n($value)
  {
    $this->getCurrentUllColumnInfoI18n()->setCaptionI18n($value);
  }

  public function getDescriptionI18n()
  {
    $obj = $this->getCurrentUllColumnInfoI18n();

    return ($obj ? $obj->getDescriptionI18n() : null);
  }

  public function setDescriptionI18n($value)
  {
    $this->getCurrentUllColumnInfoI18n()->setDescriptionI18n($value);
  }

  protected $current_i18n = array();

  public function getCurrentUllColumnInfoI18n()
  {
    if (!isset($this->current_i18n[$this->culture]))
    {
      $obj = UllColumnInfoI18nPeer::retrieveByPK($this->getId(), $this->culture);
      if ($obj)
      {
        $this->setUllColumnInfoI18nForCulture($obj, $this->culture);
      }
      else
      {
        $this->setUllColumnInfoI18nForCulture(new UllColumnInfoI18n(), $this->culture);
        $this->current_i18n[$this->culture]->setCulture($this->culture);
      }
    }

    return $this->current_i18n[$this->culture];
  }

  public function setUllColumnInfoI18nForCulture($object, $culture)
  {
    $this->current_i18n[$culture] = $object;
    $this->addUllColumnInfoI18n($object);
  }


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseUllColumnInfo:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseUllColumnInfo::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseUllColumnInfo
