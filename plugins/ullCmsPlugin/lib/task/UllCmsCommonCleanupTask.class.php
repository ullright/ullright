<?php

class UllCmsCommonCleanupTask extends ullBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'ull_cms';
    $this->name             = 'common-cleanup';
    $this->briefDescription = 'Performs common cleanup tasks for a fresh ullright installation';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] performs common cleanup tasks for a fresh ullright installation
    
    - Disables all Groups except  cmsAdmins, MasterAdmins, WikiAdmins
    - Disables test users
    - Removes some CMS pages and menu entries
    
    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
    
EOF;

    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
  }


  protected function execute($arguments = array(), $options = array())
  {
    $this->initializeDatabaseConnection($arguments, $options);
    
    // GROUPS
    $q = new ullQuery('UllGroup');
    $groups = $q->execute();
    
    $whiteList = array(
      'CmsAdmins',
      'MasterAdmins',
      'UserAdmins',  
    );
    
    foreach ($groups as $group)
    {
      if (!in_array($group->display_name, $whiteList))
      {
        $group->is_active = false;
        $group->save();    
      }
    }
    
    // USERS
    $user = UllUserTable::findByDisplayName('Helpdesk Admin User');
    $user->setUllUserStatusId(2);
    $user->save();
    
    $user = UllUserTable::findByDisplayName('Helpdesk User');
    $user->setUllUserStatusId(2);
    $user->save();
    
    $user = UllUserTable::findByDisplayName('Test User');
    $user->setUllUserStatusId(2);
    $user->save();
    
    // CMS
    // Delete unnecessary entries
    Doctrine::getTable('UllCmsPage')->findOneBySlug('advanced-course-1')->delete();
    Doctrine::getTable('UllCmsMenuItem')->findOneBySlug('advanced-courses')->delete();
    Doctrine::getTable('UllCmsMenuItem')->findOneBySlug('inactive-entry')->delete();
    Doctrine::getTable('UllCmsMenuItem')->findOneBySlug('admin')->delete();
    Doctrine::getTable('UllCmsPage')->findOneBySlug('courses')->delete();
    
    // Restructure admin menu
    $entry = Doctrine::getTable('UllCmsMenuItem')->findOneBySlug('tools');
    $entry->Translation['en']->name = 'Demo versions';
    $entry->Translation['de']->name = 'Demo Versionen';
    $entry->sequence = 999;
    $entry->save();
    
    $entry = new UllCmsMenuItem;
    $entry->Translation['en']->name = 'Administration';
    $entry->Translation['de']->name = 'Administration';
    $entry->link = 'ullAdmin/index';
    $entry->sequence = 20;
    $entry->Parent = Doctrine::getTable('UllCmsMenuItem')->findOneBySlug('admin-menu');
    $entry->save();
    
    $adminEntry = $entry;
    
    $entry = new UllCmsMenuItem;
    $entry->Translation['en']->name = 'Users';
    $entry->Translation['de']->name = 'Benutzer';
    $entry->link = 'ullUser/list?single_redirect=false';
    $entry->sequence = 10;
    $entry->Parent = $adminEntry;
    $entry->save();
    
    $entry = new UllCmsMenuItem;
    $entry->Translation['en']->name = 'Groups';
    $entry->Translation['de']->name = 'Gruppen';
    $entry->link = 'ullTableTool/list?table=UllGroup';
    $entry->sequence = 20;
    $entry->Parent = $adminEntry;
    $entry->save();    
    
    
    
    
    
    
    
    
    
    
    

  }
  
  

  
}