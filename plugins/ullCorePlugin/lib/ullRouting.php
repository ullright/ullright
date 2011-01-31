<?php
/**
 * This class adds encryption of parameters to the routing system
 */
class ullRouting extends sfPatternRouting
{
  /**
   * Encrypts parameter values if their name fulfills certain requirements,
   * tested by ullSecureParameter::isSecureParameter().
   * 
   * Otherwise works the same as sfRouting::generate().
   * @see sfRouting
   */
  public function generate($name, $params = array(), $absolute = false)
  {
    foreach ($params as $paramName => $paramValue)
    {
      if (ullSecureParameter::isSecureParameter($paramName))
      {
        $params[$paramName] = ullSecureParameter::encryptParameter($paramValue);
      }
    }

    return parent::generate($name, $params, $absolute);
  }
}