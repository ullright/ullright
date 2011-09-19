<?php

/**
 * PluginUllUserOneTimeTokenTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllUserOneTimeTokenTable extends UllRecordTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object PluginUllUserOneTimeTokenTable
     */
    public static function getInstance()
    {
      return Doctrine_Core::getTable('PluginUllUserOneTimeToken');
    }
    

    /**
     * Create a one time token for a given user_id
     * (This method can not be namend "create" because it already exists) 
     * 
     * @param integer $userId
     * 
     * @return UllUserOneTimeToken
     */
    public static function createToken($userId)
    {
      $token = new UllUserOneTimeToken();
      $token->ull_user_id = $userId;
      $token->token = ullCoreTools::randomString();
      $token->save();
      
      return $token;
    }
    
    /**
     * Use up a token
     * 
     * @param string $token
     * @param userId UllUser id
     *  
     * @return boolean
     */
    public static function isValidAndUseUp($token, $userId)
    {
      $q = new Doctrine_Query;
      $q
        ->from('UllUserOneTimeToken t')
        ->where('t.token = ?', $token)
        ->andWhere('t.ull_user_id = ?', $userId)
        ->andWhere('t.is_used_up = ?', false)
      ;
      
      $result = $q->execute();
      
      if (1 == count($result))
      {
        $token = $result->getFirst();
        $token->is_used_up = true;
        $token->save();
        
        return true;
      }

      return false;
    }
}