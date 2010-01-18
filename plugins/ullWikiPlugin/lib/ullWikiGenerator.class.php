<?php

class ullWikiGenerator extends ullTableToolGenerator
{
  /**
   * Constructor
   *
   * @param string $defaultAccess can be "r" or "w" for read or write
   */
  public function __construct($defaultAccess = 'r')
  {
    $this->modelName = 'UllWiki';
    
    parent::__construct($this->modelName, $defaultAccess);
    
    $this->formClass = 'ullWikiDocForm';
  }  
  
  /**
   * returns the identifier url params
   *
   * @param Doctrine_record $row
   * @return string
   */
  public function getIdentifierUrlParams($row)
  {
    if (!is_integer($row)) 
    {
      throw new UnexpectedArgumentException('$row must be an integer: ' . $row);
    }
    
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildForm() first');
    }
    
    return 'docid=' . $this->rows[$row]->id;
  }  
  
  /**
   * returns the identifier url params
   *
   * @param Doctrine_record $row
   * @return array
   */
  public function getIdentifierUrlParamsAsArray($row)
  {
    if (!is_integer($row)) 
    {
      throw new UnexpectedArgumentException('$row must be an integer: ' . $row);
    }
    
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildForm() first');
    }
    
    return array('docid' => $this->rows[$row]->id);
  }
}