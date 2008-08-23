<?php

class ullAuthDefault extends ullAuth 
{
  /**
   * Default authetification
   *
   * @param User $user
   * @param string $password
   * @return string
   */
  public static function authenticate(UllUser $user, $password) 
  {
    return self::authInternal($user, $password);
  }
  
}