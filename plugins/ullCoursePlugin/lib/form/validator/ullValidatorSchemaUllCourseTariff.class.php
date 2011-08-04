<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Used for UllCourseBooking to check if a selected tarif is valid for the selected course
 *
 * @package    symfony
 * @subpackage validator
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfValidatorSchemaCompare.class.php 21908 2009-09-11 12:06:21Z fabien $
 */
class ullValidatorSchemaUllCourseTariff extends sfValidatorSchema
{

  /**
   * Constructor.
   *
   * Available options:
   *
   *  * left_field:         The left field name
   *  * right_field:        The right field name
   *  * throw_global_error: Whether to throw a global error (false by default) or an error tied to the left field
   *
   * @param string $leftField   The left field name
   * @param string $operator    The operator to apply
   * @param string $rightField  The right field name
   * @param array  $options     An array of options
   * @param array  $messages    An array of error messages
   *
   * @see sfValidatorBase
   */
  public function __construct($leftField, $rightField, $options = array(), $messages = array())
  {
    $this->addOption('left_field', $leftField); // the tariff id
    $this->addOption('right_field', $rightField); // the course id

    $this->addOption('throw_global_error', false);

    parent::__construct(null, $options, $messages);
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($values)
  {
    if (null === $values)
    {
      $values = array();
    }

    if (!is_array($values))
    {
      throw new InvalidArgumentException('You must pass an array parameter to the clean() method');
    }

    $leftValue  = isset($values[$this->getOption('left_field')]) ? $values[$this->getOption('left_field')] : null;
    $rightValue = isset($values[$this->getOption('right_field')]) ? $values[$this->getOption('right_field')] : null;

    $validTariffs = UllCourseTariffTable::findIdsByCourseId($rightValue);
    
    $valid = in_array($leftValue, $validTariffs);

    if (!$valid)
    {
      $error = new sfValidatorError($this, 'invalid', array(
        'left_field'  => $leftValue,
        'right_field' => $rightValue,
      ));
      
      if ($this->getOption('throw_global_error'))
      {
        throw $error;
      }

      throw new sfValidatorErrorSchema($this, array($this->getOption('left_field') => $error));
    }

    return $values;
  }

}
