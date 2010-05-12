<?php

/**
 * ullCommentable is a behavior for Doctrine which allows
 * ullUsers to comment (multiple times) on a specific record.
 * Deletion of a comment is not supported, instead its text
 * is set to a configurable message (e.g. 'Deleted on ...')
 */
class ullCommentable extends Doctrine_Template
{
  /**
   * Returns a new instance of this behavior
   *
   * @param $options array of options
   * @return ullCommentable instance of this behavior
   */
  public function __construct(array $options = array())
  {
    parent::__construct($options);

    $this->_plugin = new ullCommentableRecordGenerator($options);
  }

  /**
   * Initializes this behavior
   */
  public function setUp()
  {
    $this->_plugin->initialize($this->_table);
  }

  /**
   * Retrieves all comments, sorted by date, descending
   *
   * @return Doctrine_Collection the comments
   */
  public function getCommentFeed()
  {
    return $this->_plugin->getComments($this->getInvoker());
  }

  /**
   * Adds a new comment, requires a currently logged-in user
   * 
   * @param string $commentText the text of the new comment
   * @return void
   */
  public function addComment($commentText)
  {
    $userId = $this->retrieveLoggedInUserId();
    
    $this->_plugin->addComment($this->getInvoker(), $userId, $commentText);
  }

  /**
   * Revokes a comment by its id, i.e. its text is replaced
   * by an appropriate message
   * 
   * This function acts as a delegate in the owning model's
   * table object
   * 
   * @param string $commentId the id of the comment which should be deleted
   * @return string the the object which the comment was refering to, false in case of error
   */
  public function revokeCommentByIdTableProxy($commentId)
  {
    $userId = $this->retrieveLoggedInUserId();
    $comment = Doctrine::getTable($this->_plugin->getOption('className'))->findOneById($commentId);
    
    if ($comment)
    {
      if ($userId == $comment['commenter_ull_user_id'])
      {
        $revokeMessage = __('This comment was deleted by its author.', null, 'ullCoreMessages');
        
      }
      else if (UllUserTable::hasPermission('ull_commentable_revoke_comments'))
      {
        $revokeMessage = __('This comment was deleted by an administrator.', null, 'ullCoreMessages');
      }
      else
      {
        return false;
      }
      
      $comment['comment'] = $revokeMessage;
      $comment->save();
        
      return $comment['CommentOn'];
    }
    
    return false;
  }
  
  /**
   * Internal function which retrieves the id of the currently
   * logged-in user, or throws an exception if that is not possible.
   * 
   * @return string id of the currently logged-in user
   */
  protected function retrieveLoggedInUserId()
  {
    if ($userId = sfContext::getInstance()->getUser()->getAttribute('user_id'))
    {
      return $userId;
    }
    else
    {
      throw new UnexpectedValueException('This comment action can only be executed by a logged in user');
    }
  }
}