<?php 

/**
 * Test implementation of ullDoctrineMapper
 *
 */
class ullDoctrineMapperNewsletter extends ullDoctrineMapper
{
  
  protected 
    $mailingListCache = array()
  ;
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullDoctrineMapper::configureMapping()
   */
  public function configureMapping()
  {
    $this->mapping = array(
      'First name'    => 'first_name',
      'Last name'     => 'last_name',
      'Email'         => 'email',
      'Mailing list'  => 'UllNewsletterMailingLists'
    );    
  }
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullDoctrineMapper::getGenerator()
   */
  public function getGenerator()
  {
    $generator = new ullUserGenerator('w');
    $generator->getColumnsConfig()->disableAllExcept($this->mapping);
    $generator->getColumnsConfig()->offsetGet('email')->setIsRequired(true);  
    
    return $generator;
  }
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullDoctrineMapper::getObject()
   */
  public function getObject(array $row)
  {
    $email = $row['email'];

    // Warning: this means a new user is created when no email address
    //   (=key) is found. Therefore the validation must require the email
    //   address
    if ($email)
    {
      $user = UllUserTable::findByEmail($email);
      
      if (!$user)
      {
        $user = new UllUser;
      }
    }
    else 
    {
      $user = new UllUser;
    }
    
    return $user;
  }
  
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullDoctrineMapper::modifyRowPreValidation()
   */
  public function modifyRowPreValidation(array $row)
  {
    // Resolve the newsletter mailing list relation
    $mailingListName = $row['UllNewsletterMailingLists'];
    
    if ($mailingListName) 
    {
      $mailingListId = null;
      
      // check cache
      if (array_key_exists($mailingListName, $this->mailingListCache))
      {
        $mailingListId = $this->mailingListCache[$mailingListName];
      }
      else
      {
        $mailingList = Doctrine::getTable('UllNewsletterMailingList')
          ->findOneByName($mailingListName);
        
        if ($mailingList)
        {
          $mailingListId = $mailingList->id;
        }
      }
        
      $this->mailingListCache[$mailingListName] = $mailingListId;
        
      if ($mailingListId)
      {
        $row['UllNewsletterMailingLists'] = array($mailingListId);
      }
      // By supplying the name we trigger a validation error and pass the name
      // of the errorneous mailing list
      else
      {
        $row['UllNewsletterMailingLists'] = $mailingListName;
      }
    }  

    return $row;
  } 
  
}  