<?php

class ullAuthDefault extends ullAuth 
{
  
  public static function authenticate(UllUser $user, $password) {
    
    return (self::authInternal($user, $password));
    
  }

  
}

?>