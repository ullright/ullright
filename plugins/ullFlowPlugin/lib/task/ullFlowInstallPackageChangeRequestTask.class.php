<?php

class ullFlowInstallPackageChangeRequestTask extends ullBaseTask
{

  protected function configure()
  {
    $this->namespace        = 'ull_flow';
    $this->name             = 'install-package-change_request';
    $this->briefDescription = 'Creates a change_request workflow';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] creates all database entires for
    a change request workflow

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
    
    $this->setRecordNamespace('ull_flow_change_request');
    
    //groups
    $groupDispatcher = $this->createRecord('UllGroup');
    $groupDispatcher['display_name'] = 'Change Request Approver';
    $groupDispatcher->save();
    
    $groupDispatcher = $this->createRecord('UllGroup');
    $groupDispatcher['display_name'] = 'Change Management';
    $groupDispatcher->save();    
    
    
    //app
    $app = $this->createRecord('UllFlowApp');
    $app['Translation']['en']['label'] = 'Change request';
    $app['Translation']['en']['doc_label'] = 'Change request';
    $app['Translation']['de']['label'] = 'Change Request';
    $app['Translation']['de']['doc_label'] = 'Change Request';    
    $app['list_columns'] = 
      'id,subject,priority,assigned_to_ull_entity_id,created_at,updated_at';
    $app['slug'] = 'change_request';
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
    $columnConfigSubject['slug'] = 'change_request_subject';
    $columnConfigSubject->save();
    
    $columnConfigDescription = $this->createRecord('UllFlowColumnConfig');
    $columnConfigDescription['Translation']['en']['label'] = 'Description';
    $columnConfigDescription['Translation']['de']['label'] = 'Beschreibung';
    $columnConfigDescription['UllFlowApp'] = $app;
    $columnConfigDescription['sequence'] = 2000;
    $columnConfigDescription['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Textarea');
    $columnConfigDescription['options'] = 'rows=8';
    $columnConfigDescription['is_mandatory'] = true;
    $columnConfigDescription['slug'] = 'change_request_description';
    $columnConfigDescription->save();

    $columnConfigReason = $this->createRecord('UllFlowColumnConfig');
    $columnConfigReason['Translation']['en']['label'] = 'Reason';
    $columnConfigReason['Translation']['de']['label'] = 'Begründung';
    $columnConfigReason['UllFlowApp'] = $app;
    $columnConfigReason['sequence'] = 3000;
    $columnConfigReason['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Textarea');
    $columnConfigReason['options'] = 'rows=8';
    $columnConfigReason['is_mandatory'] = true;
    $columnConfigReason['slug'] = 'change_request_reason';
    $columnConfigReason->save();       
    
    $columnConfigTotalCosts = $this->createRecord('UllFlowColumnConfig');
    $columnConfigTotalCosts['Translation']['en']['label'] = 'Total costs';
    $columnConfigTotalCosts['Translation']['de']['label'] = 'Gesamtkosten';
    $columnConfigTotalCosts['UllFlowApp'] = $app;
    $columnConfigTotalCosts['sequence'] = 4000;
    $columnConfigTotalCosts['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Integer');
    $columnConfigTotalCosts['is_mandatory'] = true;
    $columnConfigTotalCosts['slug'] = 'change_request_priority';
    $columnConfigTotalCosts->save();

    $columnConfigComment = $this->createRecord('UllFlowColumnConfig');
    $columnConfigComment['Translation']['en']['label'] = 'Desired implementation date';
    $columnConfigComment['Translation']['de']['label'] = 'Erwünschtes Umsetzungsdatum';
    $columnConfigComment['UllFlowApp'] = $app;
    $columnConfigComment['sequence'] = 5000;
    $columnConfigComment['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Date');
    $columnConfigComment['slug'] = 'change_request_implementation_date';
    $columnConfigComment->save();  

    $columnConfigAttachment = $this->createRecord('UllFlowColumnConfig');
    $columnConfigAttachment['Translation']['en']['label'] = 'Attachments';
    $columnConfigAttachment['Translation']['de']['label'] = 'Anhänge';
    $columnConfigAttachment['UllFlowApp'] = $app;
    $columnConfigAttachment['sequence'] = 6000;
    $columnConfigAttachment['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Upload');
    $columnConfigAttachment['slug'] = 'change_request_attachment';
    $columnConfigAttachment->save();      
    
    $columnConfigWikiLink = $this->createRecord('UllFlowColumnConfig');
    $columnConfigWikiLink['Translation']['en']['label'] = 'Wiki links';
    $columnConfigWikiLink['Translation']['de']['label'] = 'Wiki Links';
    $columnConfigWikiLink['UllFlowApp'] = $app;
    $columnConfigWikiLink['sequence'] = 7000;
    $columnConfigWikiLink['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Wiki link');
    $columnConfigWikiLink['slug'] = 'change_request_wiki_link';
    $columnConfigWikiLink->save();      
    
    $columnConfigTags = $this->createRecord('UllFlowColumnConfig');
    $columnConfigTags['Translation']['en']['label'] = 'Tags';
    $columnConfigTags['Translation']['de']['label'] = 'Tags';
    $columnConfigTags['UllFlowApp'] = $app;
    $columnConfigTags['sequence'] = 8000;
    $columnConfigTags['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Taggable');
    $columnConfigTags['slug'] = 'change_request_tags';
    $columnConfigTags->save();      
    
    
    // steps
    $stepCreator = $this->createRecord('UllFlowStep');
    $stepCreator['Translation']['en']['label'] = 'Creator';
    $stepCreator['Translation']['de']['label'] = 'Ersteller';
    $stepCreator['UllFlowApp'] = $app;    
    $stepCreator['is_start'] = true;
    $stepCreator['slug'] = 'change_request_creator';
    $stepCreator->save();

    $stepApproval = $this->createRecord('UllFlowStep');
    $stepApproval['Translation']['en']['label'] = 'Approval';
    $stepApproval['Translation']['de']['label'] = 'Genehmigung';
    $stepApproval['UllFlowApp'] = $app;   
    $stepApproval['slug'] = 'change_request_approval'; 
    $stepApproval->save();

    $stepDispatching = $this->createRecord('UllFlowStep');
    $stepDispatching['Translation']['en']['label'] = 'Dispatching';
    $stepDispatching['Translation']['de']['label'] = 'Dispatching';
    $stepDispatching['UllFlowApp'] = $app;    
    $stepDispatching['slug'] = 'change_request_dispatching';
    $stepDispatching->save();

    $stepChangeManager = $this->createRecord('UllFlowStep');
    $stepChangeManager['Translation']['en']['label'] = 'Change manager';
    $stepChangeManager['Translation']['de']['label'] = 'Change Manager';
    $stepChangeManager['UllFlowApp'] = $app;    
    $stepChangeManager['slug'] = 'change_request_change_manager';
    $stepChangeManager->save();     
    
    $stepClosed = $this->createRecord('UllFlowStep');
    $stepClosed['Translation']['en']['label'] = 'Closed';
    $stepClosed['Translation']['de']['label'] = 'Abgeschlossen';
    $stepClosed['UllFlowApp'] = $app;    
    $stepClosed['slug'] = 'change_request_closed';
    $stepClosed->save();         
    
    
    //actions for steps
    $stepActionCreator1 = $this->createRecord('UllFlowStepAction');
    $stepActionCreator1['UllFlowStep'] = $stepCreator;
    $stepActionCreator1['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('send');
    $stepActionCreator1->save();

    $stepActionApproval1 = $this->createRecord('UllFlowStepAction');
    $stepActionApproval1['UllFlowStep'] = $stepApproval;
    $stepActionApproval1['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('approve');
    $stepActionApproval1->save();

    $stepActionApproval2 = $this->createRecord('UllFlowStepAction');
    $stepActionApproval2['UllFlowStep'] = $stepApproval;
    $stepActionApproval2['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('reject');
    $stepActionApproval2->save();  

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

    $stepActionChangeManager1 = $this->createRecord('UllFlowStepAction');
    $stepActionChangeManager1['UllFlowStep'] = $stepChangeManager;
    $stepActionChangeManager1['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('return');
    $stepActionChangeManager1->save();        
    
    $stepActionClosed1 = $this->createRecord('UllFlowStepAction');
    $stepActionClosed1['UllFlowStep'] = $stepClosed;
    $stepActionClosed1['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('reopen');
    $stepActionClosed1->save();      
  }
}
