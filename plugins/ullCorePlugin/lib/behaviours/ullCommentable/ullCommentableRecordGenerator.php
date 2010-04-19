<?php

/**
 * ullCommentableRecordGenerator is a record generator used by
 * the ullCommentable behavior. It creates a separate table
 * for a model, allowing for the storage of multiple comments
 * per user
 */
class ullCommentableRecordGenerator extends Doctrine_Record_Generator
{
  protected $_options = array(
    'className' => '%CLASS%Commentable',
    'max_comment_size'  => 1000,

    //we need the following two set
    'generateFiles' => false,
    'children'      => array(), 
  );

  /**
   * Returns a new instance of this record generator.
   *
   * @param array $options
   * @return ullCommentableRecordGenerator the new instance
   */
  public function  __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
  }

  /**
   * Internal function used to generate the local relation
   * column name, e.g. ull_location_id
   * 
   * @return string the generated column name
   */
  public function getRelationLocalKey()
  {
    return $this->_options['table']->getTableName() . '_id';
  }

  /**
   * Internal function called by Doctrine to build relations
   */
  public function buildRelation()
  {
    //this allows us to access comments from the owning model,
    //e.g. ullLocation.Comments
    $this->buildForeignRelation('Comments');
    
    //this allows us to access the object this comment
    //refers to
    $this->buildLocalRelation('CommentOn');
  }

  /**
   * Internal function called by Doctrine; responsible
   * for defining the table used to store comments.
   * There is a separate (autoIncrement) id column;
   * a foreign key ullUser column; one that refers the
   * object which was commented on; in addition there
   * are columns for the comment's text and the date of
   * creation
   */
  public function setTableDefinition()
  {
    $tableName = $this->_options['table']->getTableName();
    $this->setTableName($tableName . '_comment');

    $this->hasColumn('id', 'integer', null, array('primary' => true, 'autoincrement' => true));
    $this->hasColumn($this->getRelationLocalKey(), 'integer', 8, array('notnull' => true));
    $this->hasColumn('commenter_ull_user_id', 'integer', 8, array('notnull' => true));
    $this->hasColumn('posted_date', 'timestamp', null, array('notnull' => true));
    $this->hasColumn('comment', 'string', $this->getOption('max_comment_size'), array('notnull' => true));
    
    //this also results in an additional foreign key (the commenter_ull_user_id column)
    $this->hasOne('UllUser as Commenter', array('local' => 'commenter_ull_user_id', 'foreign' => 'id'));
  }

  /**
   * Retrieves all comments from the database for a
   * given record, sorted by date, descending
   *
   * @param Doctrine_Record $invoker record the comments should be retrieved for
   * @return Doctrine_Collection the comments
   */
  public function getComments(Doctrine_Record $invoker)
  {
    $q = Doctrine_Query::create()
      ->from($this->getOption('className') . ' as comments')
      ->where('comments.' . $this->getRelationLocalKey() . ' = ?', $invoker->id)
      ->orderBy('comments.posted_date desc')
    ;

    $results = $q->execute();
    return $results;
  }

  /**
   * Adds a new comment to the invoking record
   * 
   * @param Doctrine_Record $invoker record a comment should be added for
   * @param ullUser $ullUserId ullUser the comment is from
   * @param string $commentText the comment itself
   */
  public function addComment(Doctrine_Record $invoker, $ullUserId, $commentText)
  {
    $className = $this->getOption('className');
    $comment = new $className();

    $comment[$this->getRelationLocalKey()] = $invoker->id;
    $comment['commenter_ull_user_id'] = $ullUserId;
    $comment['posted_date'] = date('c');
    $comment['comment'] = $commentText;

    $comment->save();
  }
}
