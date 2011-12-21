<?php 

/**
 * Test implementation of ullDoctrineMapper
 *
 */
class ullDoctrineMapperNewsletter extends ullDoctrineMapper
{
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
      $user = Doctrine::getTable('UllUser')->findOneByEmail($email);
      
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
    $mailingListName = $row['UllNewsletterMailingLists'];
    
    if ($mailingListName) 
    {
      $mailingList = Doctrine::getTable('UllNewsletterMailingList')
        ->findOneByName($mailingListName);
        
      if ($mailingList)
      {
        $row['UllNewsletterMailingLists']= array($mailingList->id);
      }
      else
      {
        $row['UllNewsletterMailingLists'] = $mailingListName;
      }
    }  

    return $row;
  } 
  
}  