<?php 

class BaseUllUserColumnConfigCollection extends UllEntityColumnConfigCollection
{
  
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    $this->disable('type');
    
    $this['username']->setValidatorOption('required', true);
    
    $this->useManyToManyRelation('UllGroup');
  }  
  
  
  /**
   * Adjusts the columns configuration for the sign up and edit account action
   * 
   * Can be customized in a custom columnsConfig class in apps/frontend/...
   * 
   * @param UllUser $user
   */
  public function adjustColumnConfigForEditAccount(UllUser $user)
  {
    $showColumns = array(
      'first_name',
      'last_name',
      'email',
      'username',
      'password',    
    );
    $this->disableAllExcept($showColumns);
    $this->order($showColumns);
    foreach ($showColumns as $column)
    {
      $this[$column]->setAccess('r');
    }
    
    if (!$user->exists())
    {
      $editColumns = array(
        'first_name',
        'last_name',
        'email',
        'username',
        'password',    
      );
    }
    else
    {
      $editColumns = array(
        'first_name',
        'last_name',
        'email',
        'password',    
      );
    }
    
    foreach ($editColumns as $column)
    {
      $this[$column]->setAccess('w');
    }    
    
    $this->setIsRequired(array(
      'first_name',
      'last_name',
      'email',
      'username',
      'password',    
    ));
    
    $this->adjustColumnConfigForEditAccountCommon($user);
  }  
  
  
  /**
   * Common settings for the edit user account columnsConfig
   * 
   * @param UllUser $user
   */
  protected function adjustColumnConfigForEditAccountCommon(UllUser $user)
  {
     $this['id']
      ->setAccess('r')
      ->setAutoRender(false)
    ;
    
    $this['email']
      ->setHelp(__('A valid email address is important for functions like sending you a new password in case you forgot yours', null, 'ullCoreMessages') . '.')
    ;

    // Passwort needs to be retained during creation
    if (!$user->exists())
    {
      $this['password']
        ->setWidgetOption('render_pseudo_password', false)
        ->setWidgetOption('always_render_empty', false)
      ;
    }
  }  
 
}