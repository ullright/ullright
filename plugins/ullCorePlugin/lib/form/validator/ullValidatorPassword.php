<?php

/**
 * ullValidatorTaggable validates as a string and orders and trims a list of tags
 *
 * @package    ullright
 * @subpackage ullCore
 * @author     Klemens Ullmann <klemens.ullmann@ull.at>
 * @version    SVN: $Id: sfValidatorBoolean.class.php 10307 2008-07-15 22:19:02Z Carl.Vondrick $
 */
class ullValidatorPassword extends sfValidatorString
{

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $validatedValue = parent::doClean($value);

    // '********' means no changed password
    if ($validatedValue != '' && $validatedValue != '********')
    {
      $validatedValue = md5($validatedValue);
    }

    return $validatedValue;
  }

}
