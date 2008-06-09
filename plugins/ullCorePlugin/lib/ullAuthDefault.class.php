<?php

class ullAuthInternal extends ullAuth 
{
  
  static function authenticate(UllUser $user, $password) {
    
    return (self::authInternal($user, $password));
    
  }

  
}

?>