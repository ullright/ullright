<?php

/**
 * Doctrine_Template_Personable
 *
 * Sets creator_user_id and updator_user_id according to the logged in user
 *
 * @package     ullright
 * @subpackage  ullCore
 * @author      Klemens Ullmann <klemens.ullmann@ull.at>
 */
class Doctrine_Template_Personable extends Doctrine_Template
{
    /**
     * Array of Timestampable options
     *
     * @var string
     */
    protected $_options = array('created' =>  array('name'          =>  'creator_user_id',
                                                    'alias'         =>  null,
                                                    'type'          =>  'integer',
//                                                    'format'        =>  'Y-m-d H:i:s',
                                                    'disabled'      =>  false,
//                                                    'expression'    =>  false,
                                                    'options'       =>  array()),
                                'updated' =>  array('name'          =>  'updator_user_id',
                                                    'alias'         =>  null,
                                                    'type'          =>  'integer',
//                                                    'format'        =>  'Y-m-d H:i:s',
                                                    'disabled'      =>  false,
//                                                    'expression'    =>  false,
                                                    'onInsert'      =>  true,
                                                    'options'       =>  array()));

    /**
     * __construct
     *
     * @param string $array 
     * @return void
     */
    public function __construct(array $options = array())
    {
        $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
    }

    /**
     * Set table definition for Timestampable behavior
     *
     * @return void
     */
    public function setTableDefinition()
    {
        if( ! $this->_options['created']['disabled']) {
            $name = $this->_options['created']['name'];
            if ($this->_options['created']['alias']) {
                $name .= ' as ' . $this->_options['created']['alias'];
            }
            $this->hasColumn($name, $this->_options['created']['type'], null, $this->_options['created']['options']);
        }

        if( ! $this->_options['updated']['disabled']) {
            $name = $this->_options['updated']['name'];
            if ($this->_options['updated']['alias']) {
                $name .= ' as ' . $this->_options['updated']['alias'];
            }
            $this->hasColumn($name, $this->_options['updated']['type'], null, $this->_options['updated']['options']);
        }
        
        $this->hasOne('UllUser as Creator', array('local' => $this->_options['created']['name'],
                                              'foreign' => 'id'));

        $this->hasOne('UllUser as Updator', array('local' => $this->_options['updated']['name'],
                                              'foreign' => 'id'));
        
        $this->addListener(new Doctrine_Template_Listener_Personable($this->_options));
    }
}