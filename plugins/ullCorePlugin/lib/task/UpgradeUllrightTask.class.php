<?php

class UpgradeUllrightTask extends sfBaseTask
{
  
  protected function configure()
  {
  	$this->namespace        = 'ullright';
    $this->name             = 'upgrade-ullright';
  	$this->briefDescription = 'upgrades ullright';
  	$this->detailedDescription = <<<EOF
The [{$this->name} task|INFO] upgrades ullright

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
    $this->logSection($this->name, 'Loading fixtures');

    $configuration = ProjectConfiguration::getApplicationConfiguration(
	  $arguments['application'], $arguments['env'], true);

	  $fixtures = file_get_contents(sfConfig::get('sf_root_dir') . '/data/fixtures/test');
    $fixtures = sfYaml::load($fixtures);
    
    //generic stuff
    foreach ($fixtures as $model => $records)
    {
      foreach ($records as $descriptor => $record)
      {
        // use existing admin_user
        foreach ($record as $name => $value)
        {
          if ($value == 'UllUser_1')
          {
            $fixtures[$model][$descriptor][$name] = 'admin_user';
          }
          
         if ($value == 'UllGroup_1')
          {
            $fixtures[$model][$descriptor][$name] = 'admin_group';
          }          
        }        
        
        // change creator/updator        
        if (isset($record['creator_user_id']))
        {
          $fixtures[$model][$descriptor]['Creator'] = $fixtures[$model][$descriptor]['creator_user_id'];
          $fixtures[$model][$descriptor]['Updator'] = $fixtures[$model][$descriptor]['updator_user_id'];
          
          unset(
            $fixtures[$model][$descriptor]['creator_user_id'],
            $fixtures[$model][$descriptor]['updator_user_id']
          );  
        }        
      }
    }
    
    
    $model = 'UllUser';
    $first = true;
    foreach ($fixtures[$model] as $descriptor => $record)
    {
      // delete old master admin
      if ($record['username'] == 'admin')
      {
        unset($fixtures[$model][$descriptor]);
        continue;
      }

      // manually set the first id to "2" to avoid conflict with default fixtures
      if ($first == true)
      {
        $fixtures[$model][$descriptor]['id'] = 2;
        $first = false;
      }
//      $fixtures[$model][$descriptor]['creator_user_id'] = '1';
//      $fixtures[$model][$descriptor]['updator_user_id'] = '1';

      $fixtures[$model][$descriptor]['Creator'] = 'admin_user';
      $fixtures[$model][$descriptor]['Updator'] = 'admin_user';

      unset(
        $fixtures[$model][$descriptor]['location_id'],  
        $fixtures[$model][$descriptor]['birthday']
//        $fixtures[$model][$descriptor]['Creator'],
//        $fixtures[$model][$descriptor]['Updator']
      );
    }


    $model = 'UllGroup';
    $invalidGroups = array();
    foreach ($fixtures[$model] as $descriptor => $record)
    {
      // delete old master admin group
      if ($record['caption'] == 'Master-Administrator' || isset($record['system']))
      {
        unset($fixtures[$model][$descriptor]);
        $invalidGroups[] = str_replace($model . '_', '', $descriptor);
        continue;
      }      
      
      $fixtures[$model][$descriptor]['display_name'] = $record['caption'];
      
      unset(
        $fixtures[$model][$descriptor]['caption']  
      );
    }    

    
    $model = 'UllUserGroup';
    foreach ($fixtures[$model] as $descriptor => $record)
    {
      
      // delete old master admin group membership
      if ($descriptor == 'UllUserGroup_1')
      {
        unset($fixtures[$model][$descriptor]);
        continue;
      }      

      $groupId = str_replace('UllGroup_', '', $record['ull_group_id']);
      if (in_array($groupId, $invalidGroups))
      {
        unset($fixtures[$model][$descriptor]);
        continue;
      }
      
      $fixtures[$model][$descriptor]['UllUser'] = $record['ull_user_id'];
      $fixtures[$model][$descriptor]['UllGroup'] = $record['ull_group_id'];
      
      unset(
        $fixtures[$model][$descriptor]['ull_user_id'],
        $fixtures[$model][$descriptor]['ull_group_id']
      );      
      
      // "convert" to UllEntityGroup
      $fixtures['UllEntityGroup'][$descriptor] = $fixtures[$model][$descriptor];
    }      
    unset($fixtures[$model]);


    $model = 'UllFlowApp';
    foreach ($fixtures[$model] as $descriptor => $record)
    {
      //i18n
      $fixtures[$model][$descriptor]['Translation']['en']['label'] = $record['caption_i18n_default'];
      $fixtures[$model][$descriptor]['Translation']['en']['doc_label'] = $record['doc_caption_i18n_default'];
      
      unset(
        $fixtures[$model][$descriptor]['caption_i18n_default'],  
        $fixtures[$model][$descriptor]['doc_caption_i18n_default'],
        $fixtures[$model][$descriptor]['icon'],
        $fixtures[$model][$descriptor]['ull_access_group_id'],
        $fixtures[$model][$descriptor]['default_list_columns']
      );
    }    
    
    
    $model = 'UllWiki';
    foreach ($fixtures[$model] as $descriptor => $record)
    {
      $fixtures[$model][$descriptor]['id'] = str_replace($model . '_', '', $descriptor);
      $fixtures[$model][$descriptor]['duplicate_tags_for_search'] = $record['duplicate_tags_for_propel_search'];
      
      unset(
        $fixtures[$model][$descriptor]['docid'],  
        $fixtures[$model][$descriptor]['current'],
        $fixtures[$model][$descriptor]['culture'],
        $fixtures[$model][$descriptor]['changelog_comment'],
        $fixtures[$model][$descriptor]['duplicate_tags_for_propel_search']
      );
    }
  
//    var_dump($fixtures);
    
    $yml = sfYaml::dump($fixtures, 5);
    var_dump($yml);
    
    file_put_contents(sfConfig::get('sf_root_dir') . '/data/fixtures/test.yml', $yml);
  }
  

}