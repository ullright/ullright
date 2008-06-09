<?php

abstract class ullAuth 
{
  
  static function authenticate(UllUser $user, $password) {
    
  }
  
  protected static function authInternal(UllUser $user, $password) {

    if (md5($password) == $user->getPassword()) {
      return 'authInternal';
    }
    
  }
  
  protected static function authFileShare(UllUser $user, $password) {

    $share      = sfConfig::get('app_auth_fileshare_share');
    $domain     = sfConfig::get('app_auth_fileshare_domain');
    $checkfile  = sfConfig::get('app_auth_fileshare_checkfile');
    
    $cmd = 'smbclient ' . $share . ' "' . $password . '" -U "' . $domain . '/' 
      . $user->getUsername() . '" -c ls';
      
//    ullCoreTools::printR($cmd);
//    exit();
    
    $output = shell_exec($cmd);
    
    if (strstr($output, $checkfile)) {
        return "authFileShare";
    }
    
  }
  
}

?>