<?php

/**
 * Base class that represents a row from the 'ull_wiki' table.
 *
 * 
 *
 * @package    plugins.ullWikiPlugin.lib.model.om
 */
abstract class BaseUllWiki extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        UllWikiPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;


	/**
	 * The value for the docid field.
	 * @var        int
	 */
	protected $docid;


	/**
	 * The value for the current field.
	 * @var        int
	 */
	protected $current;


	/**
	 * The value for the culture field.
	 * @var        string
	 */
	protected $culture;


	/**
	 * The value for the body field.
	 * @var        string
	 */
	protected $body;


	/**
	 * The value for the subject field.
	 * @var        string
	 */
	protected $subject;


	/**
	 * The value for the changelog_comment field.
	 * @var        string
	 */
	protected $changelog_comment;


	/**
	 * The value for the read_counter field.
	 * @var        int
	 */
	protected $read_counter;


	/**
	 * The value for the edit_counter field.
	 * @var        int
	 */
	protected $edit_counter;


	/**
	 * The value for the duplicate_tags_for_propel_search field.
	 * @var        string
	 */
	protected $duplicate_tags_for_propel_search;


	/**
	 * The value for the locked_by_user_id field.
	 * @var        int
	 */
	protected $locked_by_user_id;


	/**
	 * The value for the locked_at field.
	 * @var        int
	 */
	protected $locked_at;


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
	 * Get the [docid] column value.
	 * 
	 * @return     int
	 */
	public function getDocid()
	{

		return $this->docid;
	}

	/**
	 * Get the [current] column value.
	 * 
	 * @return     int
	 */
	public function getCurrent()
	{

		return $this->current;
	}

	/**
	 * Get the [culture] column value.
	 * 
	 * @return     string
	 */
	public function getCulture()
	{

		return $this->culture;
	}

	/**
	 * Get the [body] column value.
	 * 
	 * @return     string
	 */
	public function getBody()
	{

		return $this->body;
	}

	/**
	 * Get the [subject] column value.
	 * 
	 * @return     string
	 */
	public function getSubject()
	{

		return $this->subject;
	}

	/**
	 * Get the [changelog_comment] column value.
	 * 
	 * @return     string
	 */
	public function getChangelogComment()
	{

		return $this->changelog_comment;
	}

	/**
	 * Get the [read_counter] column value.
	 * 
	 * @return     int
	 */
	public function getReadCounter()
	{

		return $this->read_counter;
	}

	/**
	 * Get the [edit_counter] column value.
	 * 
	 * @return     int
	 */
	public function getEditCounter()
	{

		return $this->edit_counter;
	}

	/**
	 * Get the [duplicate_tags_for_propel_search] column value.
	 * 
	 * @return     string
	 */
	public function getDuplicateTagsForPropelSearch()
	{

		return $this->duplicate_tags_for_propel_search;
	}

	/**
	 * Get the [locked_by_user_id] column value.
	 * 
	 * @return     int
	 */
	public function getLockedByUserId()
	{

		return $this->locked_by_user_id;
	}

	/**
	 * Get the [optionally formatted] [locked_at] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getLockedAt($format = 'Y-m-d H:i:s')
	{

		if ($this->locked_at === null || $this->locked_at === '') {
			return null;
		} elseif (!is_int($this->locked_at)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->locked_at);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [locked_at] as date/time value: " . var_export($this->locked_at, true));
			}
		} else {
			$ts = $this->locked_at;
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
			$this->modifiedColumns[] = UllWikiPeer::ID;
		}

	} // setId()

	/**
	 * Set the value of [docid] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setDocid($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->docid !== $v) {
			$this->docid = $v;
			$this->modifiedColumns[] = UllWikiPeer::DOCID;
		}

	} // setDocid()

	/**
	 * Set the value of [current] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setCurrent($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->current !== $v) {
			$this->current = $v;
			$this->modifiedColumns[] = UllWikiPeer::CURRENT;
		}

	} // setCurrent()

	/**
	 * Set the value of [culture] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setCulture($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->culture !== $v) {
			$this->culture = $v;
			$this->modifiedColumns[] = UllWikiPeer::CULTURE;
		}

	} // setCulture()

	/**
	 * Set the value of [body] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setBody($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->body !== $v) {
			$this->body = $v;
			$this->modifiedColumns[] = UllWikiPeer::BODY;
		}

	} // setBody()

	/**
	 * Set the value of [subject] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setSubject($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->subject !== $v) {
			$this->subject = $v;
			$this->modifiedColumns[] = UllWikiPeer::SUBJECT;
		}

	} // setSubject()

	/**
	 * Set the value of [changelog_comment] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setChangelogComment($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->changelog_comment !== $v) {
			$this->changelog_comment = $v;
			$this->modifiedColumns[] = UllWikiPeer::CHANGELOG_COMMENT;
		}

	} // setChangelogComment()

	/**
	 * Set the value of [read_counter] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setReadCounter($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->read_counter !== $v) {
			$this->read_counter = $v;
			$this->modifiedColumns[] = UllWikiPeer::READ_COUNTER;
		}

	} // setReadCounter()

	/**
	 * Set the value of [edit_counter] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setEditCounter($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->edit_counter !== $v) {
			$this->edit_counter = $v;
			$this->modifiedColumns[] = UllWikiPeer::EDIT_COUNTER;
		}

	} // setEditCounter()

	/**
	 * Set the value of [duplicate_tags_for_propel_search] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setDuplicateTagsForPropelSearch($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->duplicate_tags_for_propel_search !== $v) {
			$this->duplicate_tags_for_propel_search = $v;
			$this->modifiedColumns[] = UllWikiPeer::DUPLICATE_TAGS_FOR_PROPEL_SEARCH;
		}

	} // setDuplicateTagsForPropelSearch()

	/**
	 * Set the value of [locked_by_user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setLockedByUserId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->locked_by_user_id !== $v) {
			$this->locked_by_user_id = $v;
			$this->modifiedColumns[] = UllWikiPeer::LOCKED_BY_USER_ID;
		}

	} // setLockedByUserId()

	/**
	 * Set the value of [locked_at] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setLockedAt($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [locked_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->locked_at !== $ts) {
			$this->locked_at = $ts;
			$this->modifiedColumns[] = UllWikiPeer::LOCKED_AT;
		}

	} // setLockedAt()

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
			$this->modifiedColumns[] = UllWikiPeer::CREATOR_USER_ID;
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
			$this->modifiedColumns[] = UllWikiPeer::CREATED_AT;
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
			$this->modifiedColumns[] = UllWikiPeer::UPDATOR_USER_ID;
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
			$this->modifiedColumns[] = UllWikiPeer::UPDATED_AT;
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

			$this->docid = $rs->getInt($startcol + 1);

			$this->current = $rs->getInt($startcol + 2);

			$this->culture = $rs->getString($startcol + 3);

			$this->body = $rs->getString($startcol + 4);

			$this->subject = $rs->getString($startcol + 5);

			$this->changelog_comment = $rs->getString($startcol + 6);

			$this->read_counter = $rs->getInt($startcol + 7);

			$this->edit_counter = $rs->getInt($startcol + 8);

			$this->duplicate_tags_for_propel_search = $rs->getString($startcol + 9);

			$this->locked_by_user_id = $rs->getInt($startcol + 10);

			$this->locked_at = $rs->getTimestamp($startcol + 11, null);

			$this->creator_user_id = $rs->getInt($startcol + 12);

			$this->created_at = $rs->getTimestamp($startcol + 13, null);

			$this->updator_user_id = $rs->getInt($startcol + 14);

			$this->updated_at = $rs->getTimestamp($startcol + 15, null);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 16; // 16 = UllWikiPeer::NUM_COLUMNS - UllWikiPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating UllWiki object", $e);
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

    foreach (sfMixer::getCallables('BaseUllWiki:delete:pre') as $callable)
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
			$con = Propel::getConnection(UllWikiPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			UllWikiPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseUllWiki:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseUllWiki:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(UllWikiPeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

    if ($this->isModified() && !$this->isColumnModified(UllWikiPeer::UPDATED_AT))
    {
      $this->setUpdatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(UllWikiPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseUllWiki:save:post') as $callable)
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
					$pk = UllWikiPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += UllWikiPeer::doUpdate($this, $con);
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


			if (($retval = UllWikiPeer::doValidate($this, $columns)) !== true) {
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
		$pos = UllWikiPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getDocid();
				break;
			case 2:
				return $this->getCurrent();
				break;
			case 3:
				return $this->getCulture();
				break;
			case 4:
				return $this->getBody();
				break;
			case 5:
				return $this->getSubject();
				break;
			case 6:
				return $this->getChangelogComment();
				break;
			case 7:
				return $this->getReadCounter();
				break;
			case 8:
				return $this->getEditCounter();
				break;
			case 9:
				return $this->getDuplicateTagsForPropelSearch();
				break;
			case 10:
				return $this->getLockedByUserId();
				break;
			case 11:
				return $this->getLockedAt();
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
		$keys = UllWikiPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getDocid(),
			$keys[2] => $this->getCurrent(),
			$keys[3] => $this->getCulture(),
			$keys[4] => $this->getBody(),
			$keys[5] => $this->getSubject(),
			$keys[6] => $this->getChangelogComment(),
			$keys[7] => $this->getReadCounter(),
			$keys[8] => $this->getEditCounter(),
			$keys[9] => $this->getDuplicateTagsForPropelSearch(),
			$keys[10] => $this->getLockedByUserId(),
			$keys[11] => $this->getLockedAt(),
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
		$pos = UllWikiPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setDocid($value);
				break;
			case 2:
				$this->setCurrent($value);
				break;
			case 3:
				$this->setCulture($value);
				break;
			case 4:
				$this->setBody($value);
				break;
			case 5:
				$this->setSubject($value);
				break;
			case 6:
				$this->setChangelogComment($value);
				break;
			case 7:
				$this->setReadCounter($value);
				break;
			case 8:
				$this->setEditCounter($value);
				break;
			case 9:
				$this->setDuplicateTagsForPropelSearch($value);
				break;
			case 10:
				$this->setLockedByUserId($value);
				break;
			case 11:
				$this->setLockedAt($value);
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
		$keys = UllWikiPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setDocid($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setCurrent($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setCulture($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setBody($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setSubject($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setChangelogComment($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setReadCounter($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setEditCounter($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setDuplicateTagsForPropelSearch($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setLockedByUserId($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setLockedAt($arr[$keys[11]]);
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
		$criteria = new Criteria(UllWikiPeer::DATABASE_NAME);

		if ($this->isColumnModified(UllWikiPeer::ID)) $criteria->add(UllWikiPeer::ID, $this->id);
		if ($this->isColumnModified(UllWikiPeer::DOCID)) $criteria->add(UllWikiPeer::DOCID, $this->docid);
		if ($this->isColumnModified(UllWikiPeer::CURRENT)) $criteria->add(UllWikiPeer::CURRENT, $this->current);
		if ($this->isColumnModified(UllWikiPeer::CULTURE)) $criteria->add(UllWikiPeer::CULTURE, $this->culture);
		if ($this->isColumnModified(UllWikiPeer::BODY)) $criteria->add(UllWikiPeer::BODY, $this->body);
		if ($this->isColumnModified(UllWikiPeer::SUBJECT)) $criteria->add(UllWikiPeer::SUBJECT, $this->subject);
		if ($this->isColumnModified(UllWikiPeer::CHANGELOG_COMMENT)) $criteria->add(UllWikiPeer::CHANGELOG_COMMENT, $this->changelog_comment);
		if ($this->isColumnModified(UllWikiPeer::READ_COUNTER)) $criteria->add(UllWikiPeer::READ_COUNTER, $this->read_counter);
		if ($this->isColumnModified(UllWikiPeer::EDIT_COUNTER)) $criteria->add(UllWikiPeer::EDIT_COUNTER, $this->edit_counter);
		if ($this->isColumnModified(UllWikiPeer::DUPLICATE_TAGS_FOR_PROPEL_SEARCH)) $criteria->add(UllWikiPeer::DUPLICATE_TAGS_FOR_PROPEL_SEARCH, $this->duplicate_tags_for_propel_search);
		if ($this->isColumnModified(UllWikiPeer::LOCKED_BY_USER_ID)) $criteria->add(UllWikiPeer::LOCKED_BY_USER_ID, $this->locked_by_user_id);
		if ($this->isColumnModified(UllWikiPeer::LOCKED_AT)) $criteria->add(UllWikiPeer::LOCKED_AT, $this->locked_at);
		if ($this->isColumnModified(UllWikiPeer::CREATOR_USER_ID)) $criteria->add(UllWikiPeer::CREATOR_USER_ID, $this->creator_user_id);
		if ($this->isColumnModified(UllWikiPeer::CREATED_AT)) $criteria->add(UllWikiPeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(UllWikiPeer::UPDATOR_USER_ID)) $criteria->add(UllWikiPeer::UPDATOR_USER_ID, $this->updator_user_id);
		if ($this->isColumnModified(UllWikiPeer::UPDATED_AT)) $criteria->add(UllWikiPeer::UPDATED_AT, $this->updated_at);

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
		$criteria = new Criteria(UllWikiPeer::DATABASE_NAME);

		$criteria->add(UllWikiPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of UllWiki (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setDocid($this->docid);

		$copyObj->setCurrent($this->current);

		$copyObj->setCulture($this->culture);

		$copyObj->setBody($this->body);

		$copyObj->setSubject($this->subject);

		$copyObj->setChangelogComment($this->changelog_comment);

		$copyObj->setReadCounter($this->read_counter);

		$copyObj->setEditCounter($this->edit_counter);

		$copyObj->setDuplicateTagsForPropelSearch($this->duplicate_tags_for_propel_search);

		$copyObj->setLockedByUserId($this->locked_by_user_id);

		$copyObj->setLockedAt($this->locked_at);

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
	 * @return     UllWiki Clone of current object.
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
	 * @return     UllWikiPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new UllWikiPeer();
		}
		return self::$peer;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseUllWiki:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseUllWiki::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseUllWiki
