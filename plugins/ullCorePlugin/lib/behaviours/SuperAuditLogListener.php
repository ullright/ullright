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
 * Doctrine_AuditLog_Listener
 *
 * @package     Doctrine
 * @subpackage  AuditLog
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision$
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 */
class Doctrine_SuperAuditLog_Listener extends Doctrine_Record_Listener
{
	/**
	 * Instance of Doctrine_Auditlog
	 *
	 * @var Doctrine_AuditLog
	 */
	protected $_auditLog;

	/**
	 * Instantiate AuditLog listener and set the Doctrine_AuditLog instance to the class
	 *
	 * @param   Doctrine_AuditLog $auditLog
	 * @return  void
	 */
	public function __construct(Doctrine_SuperAuditLog $auditLog)
	{
		$this->_auditLog = $auditLog;
	}

	/**
	 * Pre insert event hook for incrementing version number
	 *
	 * @param   Doctrine_Event $event
	 * @return  void
	 */
	public function preInsert(Doctrine_Event $event)
	{
		$versionColumn = $this->_auditLog->getOption('versionColumn');

		$event->getInvoker()->set($versionColumn, 1);
	}

	/**
	 * Post insert event hook which creates the new version record
	 * This will only insert a version record if the auditLog is enabled
	 *
	 * @param   Doctrine_Event $event
	 * @return  void
	 */
	public function postInsert(Doctrine_Event $event)
	{
		$class = $this->_auditLog->getOption('className');

		$record  = $event->getInvoker();
		$version = new $class();
		$version->merge($record->toArray());
		$version->save();
	}

	/**
	 * Pre delete event hook deletes all related versions
	 * This will only delete version records if the auditLog is enabled
	 *
	 * @param   Doctrine_Event $event
	 * @return  void
	 */
	public function preDelete(Doctrine_Event $event)
	{
		//throw new Exception("Deleting is not supported for SuperVersionable objects.");
		
		$className = $this->_auditLog->getOption('className');
		$versionColumn = $this->_auditLog->getOption('versionColumn');
		$event->getInvoker()->set($versionColumn, null);

		$q = Doctrine_Query::create();
		foreach ((array) $this->_auditLog->getOption('table')->getIdentifier() as $id) {
			$conditions[] = 'obj.' . $id . ' = ?';
			$values[] = $event->getInvoker()->get($id);
		}

		$rows = $q->delete($className)
		->from($className.' obj')
		->where(implode(' AND ', $conditions))
		->execute($values);
	}

	/**
	 * Pre update event hook for inserting new version record
	 * This will only insert a version record if the auditLog is enabled
	 *
	 * @param  Doctrine_Event $event
	 * @return void
	 */
	public function preUpdate(Doctrine_Event $event)
	{
		$record = $event->getInvoker();
		$class  = $this->_auditLog->getOption('className');
		$versionColumn = $this->_auditLog->getOption('versionColumn');
		$version = new $class();

		//check if the update time exists and is in the future
		if ($record->contains('scheduled_update_date'))
		{
			if (strtotime($record->scheduled_update_date) > time())
			{
				//$record->updated_at = $record->scheduled_update_date;
				
				//don't update the record
				$event->skipOperation();

				//but insert a new (future) version
				$version->merge($record->toArray());
				$version->set('scheduled_update_date', $record->scheduled_update_date);
        $version->set('reference_version', $this->_getNextVersion($record) - 1);
        //var_dump($version->toArray()); 
				//var_dump($record->toArray()); die();
				//$version->set('reference_version', $record->version);
				$version->set($versionColumn, $this->_getNextFutureVersion($record));
				$version->save();
				
				return;
			}
			else
			{
				//we got a scheduled date in the past -
				//the form validation process should
				//catch this, but we're doing a safety 'unset'
				$record->mapValue('scheduled_update_date', NULL);
			}
		}

		$record->set($versionColumn, $this->_getNextVersion($record));

		$version->merge($record->toArray());
		$version->save();
	}

	/**
	 * Get the next version number for the audit log
	 *
	 * @param Doctrine_Record $record
	 * @return integer $nextVersion
	 */
	protected function _getNextVersion(Doctrine_Record $record)
	{
		return ($this->_auditLog->getMaxVersionNumber($record) + 1);
	}
	
  protected function _getNextFutureVersion(Doctrine_Record $record)
  {
    $minVersion = $this->_auditLog->getMaxVersionNumber($record, true);
  	//if a record has no future versions,
  	//we need to fix the version number
    $minVersion = ($minVersion == 1) ? 0 : $minVersion;
    return $minVersion - 1;
  }
}