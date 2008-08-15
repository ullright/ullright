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
  public static function authenticate(User $user, $password) 
  {
    return self::authInternal($user, $password);
  }
  
}