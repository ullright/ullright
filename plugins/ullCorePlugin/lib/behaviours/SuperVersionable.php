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
 * Doctrine_Template_Versionable
 *
 * Add revisioning/audit log to your models
 *
 * @package     Doctrine
 * @subpackage  Template
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision$
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * 
 * Adapted to SuperAuditLog (SuperVersionable) for ullright.
 */
class Doctrine_Template_SuperVersionable extends Doctrine_Template
{
  /**
   * __construct
   *
   * @param array $options
   * @return void
   */
  public function __construct(array $options = array())
  {
    $this->_plugin = new Doctrine_SuperAuditLog($options);
  }

  /**
   * Setup the Versionable behavior for the template
   *
   * @return void
   */

  public function setUp()
  {
    $this->_plugin->initialize($this->_table);
      
    $this->hasColumn('version', 'integer', 8);

    $this->addListener(new Doctrine_SuperAuditLog_Listener($this->_plugin));
  }

  /**
   * Get plugin for Versionable template
   *
   * @return void
   */
  public function getAuditLog()
  {
    return $this->_plugin;
  }

  /**
   * revert
   * reverts this record to given version, this method only works if versioning plugin
   * is enabled
   *
   * @throws Doctrine_Record_Exception    if given version does not exist
   * @param integer $version      an integer > 1
   * @return Doctrine_Record      this object
   */
  public function revert($version)
  {
    $auditLog = $this->_plugin;

    $data = $auditLog->getVersion($this->getInvoker(), $version);

    if ( ! isset($data[0])) {
      throw new Doctrine_Record_Exception('Version ' . $version . ' does not exist!');
    }

    $this->getInvoker()->merge($data[0]);

    return $this->getInvoker();
  }

  /**
   * Retrieves all future versions for the invoker
   * 
   * @return the future *Version Doctrine_Records
   */
  public function getFutureVersions()
  {
    $auditLog = $this->_plugin;
    return $auditLog->getFutureVersions($this->getInvoker());
  }
  
  /**
   * Gets the className of the log.
   * 
   * @return the className
   */
  public function getClassName()
  {
    return $this->_plugin->getOption('className');
  }
}