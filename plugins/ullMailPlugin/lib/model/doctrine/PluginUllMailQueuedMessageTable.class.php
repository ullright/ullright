<?php

/**
 * PluginUllMailQueuedMessageTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllMailQueuedMessageTable extends UllRecordTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object PluginUllMailQueuedMessageTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PluginUllMailQueuedMessage');
    }
    
    /**
     * Query messages to send from queue
     * 
     * @return object Doctrine_Query
     * 
     */
    public static function querySpooledMessages()
    {
      $q = new Doctrine_Query;
      $q
        ->from('UllMailQueuedMessage m')
        ->where('is_sent = ?', false)
      ;
      
      return $q;
    }     

    /**
     * Return number of unsent messages
     */
    public static function countUnsentMessages()
    {
      $q = self::querySpooledMessages();
      
      return (int) $q->count();
    }
}