<?php
/**
 * Ullright authentification
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
abstract class ullAuth 
{
  
  /**
   * Child classes implement logic here which authentification methods to use
   * 
   * See ullAuthDefault.class.php for reference
   * 
   * @param $user
   * @param $password
   * @return unknown_type
   */
  public static function authenticate(UllUser $user, $password) {}
  
  /**
   * Internal
   * 
   * @param UllUser $user
   * @param $password
   * @return string
   */
  protected static function authInternal(UllUser $user, $password) 
  {
    if (md5($password) == $user->password) 
    {
      return 'authInternal';
    }
  }
  
  
  /**
   * Samba
   * 
   * @param UllUser $user
   * @param $password
   * @return string
   */
  protected static function authFileShare(UllUser $user, $password) 
  {
    $share      = sfConfig::get('app_auth_fileshare_share');
    $domain     = sfConfig::get('app_auth_fileshare_domain');
    $checkfile  = sfConfig::get('app_auth_fileshare_checkfile');
    
    $cmd = 'smbclient ' . $share . ' "' . $password . '" -U "' . $domain . '/' 
      . $user->getUsername() . '" -c ls';
      
    $output = shell_exec($cmd);
    
    if (strstr($output, $checkfile)) 
    {
      return "authFileShare";
    }
  }
  
  
  /**
   * IMAP
   * 
   * @param UllUser $user
   * @param $password
   * @return string
   */
  protected static function authImap(UllUser $user, $password) 
  {
    $mailbox   = sfConfig::get('app_auth_imap_mailbox', '{127.0.0.1:143/notls}INBOX');
    
    if (@imap_open ($mailbox, $username, $plainpwd)) 
    {
      return "IMAP";
    }

  }  
  
}