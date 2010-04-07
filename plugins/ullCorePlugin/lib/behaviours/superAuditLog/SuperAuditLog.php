<?php
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.phpdoctrine.org>.
 */

/**
 * Doctrine_AuditLog
 *
 * @package     Doctrine
 * @subpackage  AuditLog
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision$
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * 
 * Adapted to SuperAuditLog (SuperVersionable) for ullright.
 * 
 * The option hasAuditLog (yes/no) was removed, since the new
 * 'future version' system needs it anyway.
 */
class Doctrine_SuperAuditLog extends Doctrine_Record_Generator
{
  /**
   * Array of AuditLog Options
   *
   * @var string
   */
  protected $_options = array(
                            'className'     => '%CLASS%Version',
                            'versionColumn' => 'version',
                            'tableName'     => false,
                            'generateFiles' => false,
                            'table'         => false,
                            'pluginTable'   => false,
                            'children'      => array(),
                            'enableFutureVersions' => false,
  );

  /**
   * Accepts array of options to configure the AuditLog
   *
   * @param   array $options An array of options
   * @return  void
   */
  public function __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
  }

  /**
   * Set the table definition for the audit log table
   *
   * @return  void
   */
  public function setTableDefinition()
  {
    $name = $this->_options['table']->getComponentName();
    $columns = $this->_options['table']->getColumns();

    // remove all sequence, autoincrement and unique constraint definitions and add to the behavior model
    foreach ($columns as $column => $definition) {
      unset($definition['autoincrement']);
      unset($definition['sequence']);
      unset($definition['unique']);

      $fieldName = $this->_options['table']->getFieldName($column);
      if ($fieldName != $column) {
        $name = $column . ' as ' . $fieldName;
      } else {
        $name = $fieldName;
      }

      $this->hasColumn($name, $definition['type'], $definition['length'], $definition);
    }

    // the version column should be part of the primary key definition
    $this->hasColumn($this->_options['versionColumn'], 'integer', 8, array('primary' => true));
    
    $this->hasColumn('reference_version', 'integer', 8);
    $this->hasColumn('scheduled_update_date', 'date');
    $this->hasColumn('done_at', 'timestamp');
    
    if (class_exists('UllUser')) //safety check
    {
      $this->hasOne('UllUser as Updator', array('local' => 'updator_user_id', 'foreign' => 'id'));
    }
  }

  /**
   * Get array of information for the passed record and the specified version
   *
   * @param   Doctrine_Record $record
   * @param   integer         $version
   * @return  array           An array with version information
   */
  public function getVersion(Doctrine_Record $record, $version)
  {
    $className = $this->_options['className'];

    $q = new Doctrine_Query;

    $values = array();
    foreach ((array) $this->_options['table']->getIdentifier() as $id) {
      $conditions[] = $className . '.' . $id . ' = ?';
      $values[] = $record->get($id);
    }

    $where = implode(' AND ', $conditions) . ' AND ' . $className . '.' . $this->_options['versionColumn'] . ' = ?';

    $values[] = $version;

    $q
      ->from($className)
      ->where($where)
    ;

    return $q->execute($values, Doctrine::HYDRATE_ARRAY);
  }
  
  /**
   * Get a Doctrine_Record for the passed record and the specified version
   * If no version is given, the most current one will be retrieved.
   * 
   * @param Doctrine_Record $record
   * @param integer $version
   * @return Doctrine_Record the Doctrine_Record with the specified version
   */
  public function getVersionRecord(Doctrine_Record $record, $version = NULL)
  {
    $version = ($version == NULL) ? $this->getMaxVersionNumber($record) : $version;
    
    $className = $this->_options['className'];

    $q = new Doctrine_Query;

    $values = array();
    foreach ((array) $this->_options['table']->getIdentifier() as $id) {
      $conditions[] = $className . '.' . $id . ' = ?';
      $values[] = $record->get($id);
    }

    $where = implode(' AND ', $conditions) . ' AND ' . $className . '.' . $this->_options['versionColumn'] . ' = ?';

    $values[] = $version;

    $q
      ->from($className)
      ->where($where)
    ;
    
    return $q->fetchOne($values);
  }
  
  /**
   * Retrives all future versions for the given Doctrine_Record,
   * ordered by 'newest on top'.
   * 
   * @param Doctrine_Record $record
   * @return future versions (multiple Doctrine_Record)
   */
  public function getFutureVersions(Doctrine_Record $record)
  {
    $className = $this->_options['className'];

    $q = new Doctrine_Query;

    $values = array();
    foreach ((array) $this->_options['table']->getIdentifier() as $id) {
      $conditions[] = $className . '.' . $id . ' = ?';
      $values[] = $record->get($id);
    }

    $where = implode(' AND ', $conditions) . ' AND ' . $className . '.' . $this->_options['versionColumn'] . ' < 0';

    $q
      ->from($className)
      ->where($where)
      ->andWhere('done_at IS NULL')
      ->orderBy('scheduled_update_date DESC, version ASC'); //newest on top
    ;
    
    $results = $q->execute($values);

    return $results;
  }
  
  /**
   * Deletes a specified future version for a given record.
   * 
   * @param Doctrine_Record $record
   * @param integer $version
   * @return void
   */
  public function deleteFutureVersion(Doctrine_Record $record, $version) {
   if ($this->_options['enableFutureVersions'] == false)
    {
      throw new Exception("Future versions are not allowed!");
    }
    
    if ($version >= 0)
    {
      throw new RuntimeException('Only future versions can be deleted.');
    }
    
    $className = $this->_options['className'];
    $q = new Doctrine_Query;
    
    $q
      ->delete($className)
      ->where('id = ?', $record->id)
      ->andWhere('version = ?', $version)
    ;
    
    if ($q->execute() == 0)
    {
      throw new RuntimeException('The specified version was not found.');
    }
  }
  
  /**
   * Get the max version number for a given Doctrine_Record
   *
   * @param Doctrine_Record $record
   * @return Integer $versionnumber
   */
  public function getMaxVersionNumber(Doctrine_Record $record, $minInsteadOfMax = false)
  {
    $minOrMax = ($minInsteadOfMax) ? 'min' : 'max';
    
    $className = $this->_options['className'];
    $select = $minOrMax . '(' . $className . '.' . $this->_options['versionColumn'] . ') ' . $minOrMax . '_version';

    foreach ((array) $this->_options['table']->getIdentifier() as $id) {
      $conditions[] = $className . '.' . $id . ' = ?';
      $values[] = $record->get($id);
    }

    $q = new Doctrine_Query;
    
    $q
      ->select($select)
      ->from($className)
      ->where(implode(' AND ',$conditions))
    ;

    $result = $q->execute($values, Doctrine::HYDRATE_ARRAY);
    
    $maxVersion = isset($result[0][$minOrMax . '_version']) ? $result[0][$minOrMax . '_version']:0;
    return $maxVersion;
  }
}