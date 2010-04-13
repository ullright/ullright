<?php

/**
 * PluginUllEntity
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class PluginUllEntity extends BaseUllEntity
{
  /**
   * __toString()
   *
   * @return string
   */
  public function __toString()
  {

    return (string) $this->display_name;

    //    if ($this->type == 'group')
    //    {
    //      $group = sfContext::getInstance()->getI18N()->__('Group', null, 'common');
    //      //return $this->display_name . " ($group)";
    //      //let's see how this looks...
    //      return $this->display_name;
    //    }
    //    else
    //    {
    //      return $this->display_name;
    //    }
    }


    /**
     * For clone users: transparently load the parent's values if none are set natively
     *
     * This is set here, and not in PluginUllCloneUser class, so it works if we work with UllEntites on a higher level (e.g. Users and CloneUsers)
     *
     * Overwrites Doctrine_Record::get() method
     *
     * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine/Doctrine_Record#get($fieldName, $load)
     */
    public function get($fieldName, $load = true)
    {
      $value = parent::get($fieldName, $load);

      if (
      $value === null
      && $this->_get('type') == 'clone_user'
      && $this->getTable()->hasColumn($fieldName)
      //      && !in_array($fieldName, array('id', 'parent_ull_user_id'))
      )
      {
        $value = $this->Parent->$fieldName;
      }

      return $value;
    }


    /**
     * Overwrites Doctrine_Record::toArray() to transparently load
     * parent data for UllCloneUsers
     *
     * This is mainly needed for sfDoctrineForm::updateDefaultsFromObject
     *
     * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine/Doctrine_Record#toArray($deep, $prefixKey)
     */
    public function toArray($deep = true, $prefixKey = false)
    {
      $array = parent::toArray($deep, $prefixKey);

      $array['photo'] = $this->get('photo');
      $array['phone_extension'] = $this->get('phone_extension');
      $array['mobile_number'] = $this->get('mobile_number');

      if ($this->_get('type') == 'clone_user')
      {
        foreach ($array as $fieldName => $value)
        {
          if (!is_array($value) && empty($value))
          {
            $array[$fieldName] = $this->get($fieldName);
          }
        }
      }

      return $array;
    }


    /**
     * Get the subordinates for the current entity
     *
     * @param boolean $onlyActive             Get only active users (default = true)
     * @param boolean $onlyForOrgchart        Get only users having an enabled
     *                                          is_show_in_orgchart flag (default = false)
     * @param boolean $hydration
     * @return mixed
     */
    public function getSubordinates($onlyActive = true, $onlyForOrgchart = false, $hydrationMode = null)
    {
      $q = new ullQuery('UllUser');
      $q
      ->addWhere('superior_ull_user_id = ?', $this->id)
      ->addOrderby('last_name, first_name')
      ;

      if ($onlyActive)
      {
        $q->addWhere('UllUserStatus->is_active = ?', true);
      }
      
      if ($onlyForOrgchart)
      {
        $q->addWhere('is_show_in_orgchart = ?', true);
      }
      
      $result = $q->execute(null, $hydrationMode);

      return $result;
    }


    /**
     * Check if the current entity is a superior
     *
     * @param boolean $onlyActive
     * @param mixed $hydrationMode
     * @return boolean
     */
    public function isSuperior($onlyActive = true, $hydrationMode = null)
    {
      return (boolean) $this->getSubordinates($onlyActive, false, Doctrine::HYDRATE_NONE);
    }
  }