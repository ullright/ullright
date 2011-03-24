<?php

/**
 * ullValidatorTaggable validates as a string and orders and trims a list of tags
 *
 * @package    ullright
 * @subpackage ullCore
 * @author     Klemens Ullmann <klemens.ullmann@ull.at>
 * @version    SVN: $Id: sfValidatorBoolean.class.php 10307 2008-07-15 22:19:02Z Carl.Vondrick $
 */
class ullValidatorTaggable extends sfValidatorString
{

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $value = ullHTMLPurifier::purifyForSecurity($value);
    $tagsArray = array_map('trim', explode(',', strtolower($value)));
    natsort($tagsArray);
    
    return implode(', ', $tagsArray);
  }
  
}
