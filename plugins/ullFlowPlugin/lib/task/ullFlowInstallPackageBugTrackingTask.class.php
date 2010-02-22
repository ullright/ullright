<?php

class ullFlowInstallPackageBugTrackingTask extends ullBaseTask
{

  protected function configure()
  {
    $this->namespace        = 'ull_flow';
    $this->name             = 'install-package-bug_tracking';
    $this->briefDescription = 'Creates a bug tracking workflow';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] creates all database entires for
    a bug tracking workflow

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
    
    $this->setRecordNamespace('ull_flow_bug_tracking');
    
    //groups
    $groupDispatcher = $this->createRecord('UllGroup');
    $groupDispatcher['display_name'] = 'Bug Reporting Dispatcher';
    $groupDispatcher->save();
    
    $app = $this->createRecord('UllFlowApp');
    $app['Translation']['en']['label'] = 'Bug Tracking';
    $app['Translation']['en']['doc_label'] = 'Bug Report';
    $app['Translation']['de']['label'] = 'Bug Tracking';
    $app['Translation']['de']['doc_label'] = 'Bug Report';    
    $app['list_columns'] = 
      'id,subject,priority,assigned_to_ull_entity_id,created_at,updated_at';
    $app['slug'] = 'bug_tracking';
    $app->save();
        
    
    // columns
    $columnConfigSubject = $this->createRecord('UllFlowColumnConfig');
    $columnConfigSubject['Translation']['en']['label'] = 'Subject';
    $columnConfigSubject['Translation']['de']['label'] = 'Betreff';
    $columnConfigSubject['UllFlowApp'] = $app;
    $columnConfigSubject['sequence'] = 1000;
    $columnConfigSubject['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('String');
    $columnConfigSubject['is_mandatory'] = true;
    $columnConfigSubject['is_subject'] = true;
    $columnConfigSubject['slug'] = 'bug_tracking_subject';
    $columnConfigSubject->save();
    
    $columnConfigText = $this->createRecord('UllFlowColumnConfig');
    $columnConfigText['Translation']['en']['label'] = 'Text';
    $columnConfigText['Translation']['de']['label'] = 'Text';
    $columnConfigText['UllFlowApp'] = $app;
    $columnConfigText['sequence'] = 2000;
    $columnConfigText['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Information update');
    $columnConfigText['slug'] = 'bug_tracking_text';
    $columnConfigText->save();    
    
    $columnConfigPriority = $this->createRecord('UllFlowColumnConfig');
    $columnConfigPriority['Translation']['en']['label'] = 'Priority';
    $columnConfigPriority['Translation']['de']['label'] = 'Wichtigkeit';
    $columnConfigPriority['UllFlowApp'] = $app;
    $columnConfigPriority['sequence'] = 3000;
    $columnConfigPriority['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Priority');
    $columnConfigPriority['default_value'] = 3;
    $columnConfigPriority['is_priority'] = true;
    $columnConfigPriority['slug'] = 'bug_tracking_priority';
    $columnConfigPriority->save();

    $columnConfigComment = $this->createRecord('UllFlowColumnConfig');
    $columnConfigComment['Translation']['en']['label'] = 'Comment';
    $columnConfigComment['Translation']['de']['label'] = 'Kommentar';
    $columnConfigComment['UllFlowApp'] = $app;
    $columnConfigComment['sequence'] = 4000;
    $columnConfigComment['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Textarea');
    $columnConfigComment['slug'] = 'bug_tracking_comment';
    $columnConfigComment->save();  

    $columnConfigAttachment = $this->createRecord('UllFlowColumnConfig');
    $columnConfigAttachment['Translation']['en']['label'] = 'Attachments';
    $columnConfigAttachment['Translation']['de']['label'] = 'Anhänge';
    $columnConfigAttachment['UllFlowApp'] = $app;
    $columnConfigAttachment['sequence'] = 5000;
    $columnConfigAttachment['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Upload');
    $columnConfigAttachment['slug'] = 'bug_tracking_attachment';
    $columnConfigAttachment->save();      
    
    $columnConfigWikiLink = $this->createRecord('UllFlowColumnConfig');
    $columnConfigWikiLink['Translation']['en']['label'] = 'Wiki links';
    $columnConfigWikiLink['Translation']['de']['label'] = 'Wiki Links';
    $columnConfigWikiLink['UllFlowApp'] = $app;
    $columnConfigWikiLink['sequence'] = 6000;
    $columnConfigWikiLink['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Wiki link');
    $columnConfigWikiLink['slug'] = 'bug_tracking_wiki_link';
    $columnConfigWikiLink->save();      
    
    $columnConfigTags = $this->createRecord('UllFlowColumnConfig');
    $columnConfigTags['Translation']['en']['label'] = 'Tags';
    $columnConfigTags['Translation']['de']['label'] = 'Tags';
    $columnConfigTags['UllFlowApp'] = $app;
    $columnConfigTags['sequence'] = 7000;
    $columnConfigTags['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Taggable');
    $columnConfigTags['slug'] = 'bug_tracking_tags';
    $columnConfigTags->save();      
    
    
    // steps
    $stepCreator = $this->createRecord('UllFlowStep');
    $stepCreator['Translation']['en']['label'] = 'Creator';
    $stepCreator['Translation']['de']['label'] = 'Ersteller';
    $stepCreator['UllFlowApp'] = $app;    
    $stepCreator['is_start'] = true;
    $stepCreator['slug'] = 'bug_tracking_creator';
    $stepCreator->save();

    $stepDispatching = $this->createRecord('UllFlowStep');
    $stepDispatching['Translation']['en']['label'] = 'Dispatching';
    $stepDispatching['Translation']['de']['label'] = 'Dispatching';
    $stepDispatching['UllFlowApp'] = $app;   
    $stepDispatching['slug'] = 'bug_tracking_dispatching'; 
    $stepDispatching->save();

    $stepDevelopment = $this->createRecord('UllFlowStep');
    $stepDevelopment['Translation']['en']['label'] = 'Development';
    $stepDevelopment['Translation']['de']['label'] = 'Entwicklung';
    $stepDevelopment['UllFlowApp'] = $app;    
    $stepDevelopment['slug'] = 'bug_tracking_development';
    $stepDevelopment->save();     
    
    $stepClosed = $this->createRecord('UllFlowStep');
    $stepClosed['Translation']['en']['label'] = 'Closed';
    $stepClosed['Translation']['de']['label'] = 'Abgeschlossen';
    $stepClosed['UllFlowApp'] = $app;    
    $stepClosed['slug'] = 'bug_tracking_closed';
    $stepClosed->save();         
    
    
    //actions for steps
    $stepActionCreator1 = $this->createRecord('UllFlowStepAction');
    $stepActionCreator1['UllFlowStep'] = $stepCreator;
    $stepActionCreator1['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('send');
    $stepActionCreator1->save();
    
    $stepActionCreator2 = $this->createRecord('UllFlowStepAction');
    $stepActionCreator2['UllFlowStep'] = $stepCreator;
    $stepActionCreator2['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('assign_to_user');
    $stepActionCreator2->save();
    
    $stepActionDispatching1 = $this->createRecord('UllFlowStepAction');
    $stepActionDispatching1['UllFlowStep'] = $stepDispatching;
    $stepActionDispatching1['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('assign_to_user');
    $stepActionDispatching1->save();

    $stepActionDispatching2 = $this->createRecord('UllFlowStepAction');
    $stepActionDispatching2['UllFlowStep'] = $stepDispatching;
    $stepActionDispatching2['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('close');
    $stepActionDispatching2->save();        
    
    $stepActionDevelopment1 = $this->createRecord('UllFlowStepAction');
    $stepActionDevelopment1['UllFlowStep'] = $stepDevelopment;
    $stepActionDevelopment1['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('return');
    $stepActionDevelopment1->save();        
    
    $stepActionDevelopment2 = $this->createRecord('UllFlowStepAction');
    $stepActionDevelopment2['UllFlowStep'] = $stepDevelopment;
    $stepActionDevelopment2['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('reject');
    $stepActionDevelopment2->save();

    $stepActionDevelopment3 = $this->createRecord('UllFlowStepAction');
    $stepActionDevelopment3['UllFlowStep'] = $stepDevelopment;
    $stepActionDevelopment3['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('assign_to_user');
    $stepActionDevelopment3->save();

    $stepActionClosed1 = $this->createRecord('UllFlowStepAction');
    $stepActionClosed1['UllFlowStep'] = $stepClosed;
    $stepActionClosed1['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('reopen');
    $stepActionClosed1->save();      
  }
}
