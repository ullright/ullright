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

    
    $model = 'UllFlowAppI18n';
    $parentModel = substr($model, 0, -4);
    foreach ($fixtures[$model] as $descriptor => $record)
    {
      // only parse german translations
      if (substr($descriptor, -2, 2) != 'de')
      {
        continue;
      }
      
      $parentDescriptor = $record['id'];
      
      $fixtures[$parentModel][$parentDescriptor]['Translation']['de']['label'] = $record['caption_i18n'];
      $fixtures[$parentModel][$parentDescriptor]['Translation']['de']['doc_label'] = $record['doc_caption_i18n'];
    }       
    unset($fixtures[$model]);
    
    
    $model = 'UllFlowField';
    $ullColumnTypeMap = array(    
      1 => 'string',  
      2 => 'textarea',
      3 => 'checkbox',
      5 => 'foreign_key',
//      6 => null,          // date TODO: missing!
      7 => 'datetime',
      8 => 'integer',
      9 => 'ull_user',
      10 => 'ull_select',
      11 => 'password',
      12 => 'upload',
      13 => 'ull_select',
      14 => 'password',
      15 => 'wiki_link',
      16 => 'ull_select',
      17 => 'password',
//      18 => null,       // information_update TODO: missing!
      19 => 'taggable',
    );
    foreach ($fixtures[$model] as $descriptor => $record)
    {
      $ullFieldId = str_replace('UllField_', '', $record['ull_field_id']);
      
      // skip missing field types
      if (!isset($ullColumnTypeMap[$ullFieldId]))
      {
        unset($fixtures[$model][$descriptor]);
        continue;
      }
      
      $fixtures[$model][$descriptor]['UllFlowApp'] = $record['ull_flow_app_id'];
      $fixtures[$model][$descriptor]['UllColumnType'] = $ullColumnTypeMap[$ullFieldId];
//      $options = sfToolkit::stringToArray($record['options']);
//      if ($options)
//      {
//        
//      }
//      $fixtures[$model][$descriptor]['options']
      
      //i18n
      $fixtures[$model][$descriptor]['Translation']['en']['label'] = $record['caption_i18n_default'];
      
      $fixtures[$model][$descriptor]['is_enabled'] = (bool) $record['enabled'];
      $fixtures[$model][$descriptor]['is_mandatory'] = (bool) $record['mandatory'];
      $fixtures[$model][$descriptor]['is_subject'] = (bool) $record['is_title'];
      $fixtures[$model][$descriptor]['slug'] = ullCoreTools::sluggify($record['caption_i18n_default']);
      // special handling for columns which are also in UllFlowDoc
      if ($ullFieldId == 19 || $fixtures[$model][$descriptor]['slug'] == 'priority')
      {
        $fixtures[$model][$descriptor]['slug'] = 'column_' .  $fixtures[$model][$descriptor]['slug'];
      }
      
      unset(
        $fixtures[$model][$descriptor]['ull_flow_app_id'],
        $fixtures[$model][$descriptor]['ull_field_id'],
        $fixtures[$model][$descriptor]['caption_i18n_default'],  
        $fixtures[$model][$descriptor]['enabled'],
        $fixtures[$model][$descriptor]['mandatory'],
        $fixtures[$model][$descriptor]['is_title'],
        $fixtures[$model][$descriptor]['is_priority'],
        $fixtures[$model][$descriptor]['is_deadline'],
        $fixtures[$model][$descriptor]['is_custom_field1'],
        $fixtures[$model][$descriptor]['ull_access_group_id']
      );
    }     
    // "convert" to UllFlowColumnConfig
    $fixtures['UllFlowColumnConfig'] = $fixtures[$model];
    unset($fixtures[$model]);

    
    $model = 'UllFlowFieldI18n';
    $parentModel = 'UllFlowColumnConfig';
    foreach ($fixtures[$model] as $descriptor => $record)
    {
      // only parse german translations
      if (substr($descriptor, -2, 2) != 'de')
      {
        continue;
      }
      
      $parentDescriptor = $record['id'];
      
      $fixtures[$parentModel][$parentDescriptor]['Translation']['de']['label'] = $record['caption_i18n'];
    }       
    unset($fixtures[$model]);    
    
    
    
    $model = 'UllFlowStep';
    foreach ($fixtures[$model] as $descriptor => $record)
    {
      $fixtures[$model][$descriptor]['UllFlowApp'] = $record['ull_flow_app_id'];
      
      //i18n
      $fixtures[$model][$descriptor]['Translation']['en']['label'] = $record['caption_i18n_default'];
      
      $fixtures[$model][$descriptor]['is_start'] = (bool) $record['is_start'];
      
      unset(
        $fixtures[$model][$descriptor]['ull_flow_app_id'],
        $fixtures[$model][$descriptor]['caption_i18n_default']  
      );
    }    

    
    $model = 'UllFlowStepI18n';
    $parentModel = substr($model, 0, -4);
    foreach ($fixtures[$model] as $descriptor => $record)
    {
      // only parse german translations
      if (substr($descriptor, -2, 2) != 'de')
      {
        continue;
      }
      
      $parentDescriptor = $record['id'];
      
      $fixtures[$parentModel][$parentDescriptor]['Translation']['de']['label'] = $record['caption_i18n'];
    }       
    unset($fixtures[$model]);    
    
    

    
    
    $model = 'UllFlowStepAction';
    $ullFlowActionMap = array(
      1 => 'ull_flow_action_create',
      2 => null,                      // edit -> invalid
      3 => 'ull_flow_action_assign_to_user',
      4 => 'ull_flow_action_assign_to_group',
      5 => 'ull_flow_action_close',
      6 => 'ull_flow_action_save_close',
      7 => 'ull_flow_action_send',
      8 => 'ull_flow_action_reopen',
      9 => 'ull_flow_action_save_only',
      10 => 'ull_flow_action_send',
      11 => 'ull_flow_action_reject',
      12 => 'ull_flow_action_return',
      13 => 'ull_flow_action_save',        
    );
    foreach ($fixtures[$model] as $descriptor => $record)
    {
      $ullFlowActionId = str_replace('UllFlowAction_', '', $record['ull_flow_action_id']);
      
      // skip invalid actions
      if (!isset($ullFlowActionMap[$ullFlowActionId]))
      {
        unset($fixtures[$model][$descriptor]);
        continue;
      }      
      
      $fixtures[$model][$descriptor]['UllFlowStep'] = $record['ull_flow_step_id'];
      $fixtures[$model][$descriptor]['UllFlowAction'] = $ullFlowActionMap[$ullFlowActionId];
      
      unset(
        $fixtures[$model][$descriptor]['ull_flow_step_id'],
        $fixtures[$model][$descriptor]['ull_flow_action_id']
      );
    }      
    
    
    
    $model = 'UllFlowDoc';
    foreach ($fixtures[$model] as $descriptor => $record)
    {
      $fixtures[$model][$descriptor]['id'] = str_replace($model . '_', '', $descriptor);
      $fixtures[$model][$descriptor]['UllFlowApp'] = $record['ull_flow_app_id'];
      $fixtures[$model][$descriptor]['subject'] = $record['title'];
      $ullFlowActionId = str_replace('UllFlowAction_', '', $record['ull_flow_action_id']);
      $fixtures[$model][$descriptor]['UllFlowAction'] = $ullFlowActionMap[$ullFlowActionId];      
      // handle user/group -> entity transformation
      // assign to creator if not assigned
      if ($record['assigned_to_ull_user_id'] == 'UllUser_0' &&
        $record['assigned_to_ull_group_id'] == 'UllGroup_0')
      {
        $fixtures[$model][$descriptor]['UllEntity'] = $record['Creator'];
      }
      elseif ($record['assigned_to_ull_group_id'] != 'UllGroup_0')
      {
        $fixtures[$model][$descriptor]['UllEntity'] = $record['assigned_to_ull_group_id'];
      }
      else
      {
        $fixtures[$model][$descriptor]['UllEntity'] = $record['assigned_to_ull_user_id'];
      }

      $fixtures[$model][$descriptor]['UllFlowStep'] = $record['assigned_to_ull_flow_step_id'];
      
      $fixtures[$model][$descriptor]['duplicate_tags_for_search'] = $record['duplicate_tags_for_propel_search'];
      
      unset(
        $fixtures[$model][$descriptor]['ull_flow_app_id'],
        $fixtures[$model][$descriptor]['title'],
        $fixtures[$model][$descriptor]['assigned_to_ull_group_id'],
        $fixtures[$model][$descriptor]['assigned_to_ull_user_id'],
        $fixtures[$model][$descriptor]['assigned_to_ull_flow_step_id'],
        $fixtures[$model][$descriptor]['duplicate_tags_for_propel_search'],
        $fixtures[$model][$descriptor]['ull_flow_action_id'],
        $fixtures[$model][$descriptor]['read_ull_group_id'],
        $fixtures[$model][$descriptor]['write_ull_group_id'],
        $fixtures[$model][$descriptor]['custom_field1']
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
