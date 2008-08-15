<?php

abstract class ullAuth 
{
  
  public static function authenticate(User $user, $password) {} 
  
  protected static function authInternal(User $user, $password) 
  {
    if (md5($password) == $user->password) 
    {
      return 'authInternal';
    }
  }
  
  protected static function authFileShare(User $user, $password) 
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
  
}