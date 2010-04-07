<?php
/**
 * Doctrine_Template_Listener_Personable
 *
 * Sets creator_user_id and updator_user_id according to the logged in user
 *
 * @package     ullright
 * @subpackage  ullCore
 * @author      Klemens Ullmann <klemens.ullmann@ull.at>
 */
class Doctrine_Template_Listener_Personable extends Doctrine_Record_Listener
{
    /**
     * Array of personable options
     *
     * @var string
     */
    protected $_options = array();

    /**
     * __construct
     *
     * @param string $options 
     * @return void
     */
    public function __construct(array $options)
    {
        $this->_options = $options;
    }

    /**
     * Set the created and updated Personable columns when a record is inserted
     *
     * @param Doctrine_Event $event
     * @return void
     */
    public function preInsert(Doctrine_Event $event)
    {
        if ( ! $this->_options['created']['disabled']) {
            $createdName = $event->getInvoker()->getTable()->getFieldName($this->_options['created']['name']);
            $modified = $event->getInvoker()->getModified();
            if ( ! isset($modified[$createdName])) {
                $event->getInvoker()->$createdName = $this->getUserId();
            }
        }

        if ( ! $this->_options['updated']['disabled'] && $this->_options['updated']['onInsert']) {
            $updatedName = $event->getInvoker()->getTable()->getFieldName($this->_options['updated']['name']);
            $modified = $event->getInvoker()->getModified();
            if ( ! isset($modified[$updatedName])) {
                $event->getInvoker()->$updatedName = $this->getUserId();
            }
        }
    }

    /**
     * Set updated Personable column when a record is updated
     *
     * @param Doctrine_Event $evet
     * @return void
     */
    public function preUpdate(Doctrine_Event $event)
    {
        if ( ! $this->_options['updated']['disabled']) {
            $updatedName = $event->getInvoker()->getTable()->getFieldName($this->_options['updated']['name']);
            $modified = $event->getInvoker()->getModified();
            if ( ! isset($modified[$updatedName])) {
                $event->getInvoker()->$updatedName = $this->getUserId();
            }
        }
    }

    /**
     * Set the updated field for dql update queries
     *
     * @param Doctrine_Event $evet
     * @return void
     */
    public function preDqlUpdate(Doctrine_Event $event)
    {
        if ( ! $this->_options['updated']['disabled']) {
            $params = $event->getParams();
            $updatedName = $event->getInvoker()->getTable()->getFieldName($this->_options['updated']['name']);
            $field = $params['alias'] . '.' . $updatedName;
            $query = $event->getQuery();

            if ( ! $query->contains($field)) {
                $query->set($field, '?', $this->getUserId());
            }
        }
    }

    /**
     * Gets the currently logged in user's id, or default to 1 (= Masteradmin)
     *
     * @return void
     */
    public function getUserId()
    {
      if (sfContext::hasInstance())
      {
        if ($userId = sfContext::getInstance()->getUser()->getAttribute('user_id'))
        {
          return $userId;
        }
      }
      return 1;
    }
}