<?php

class ullFlowInstallPackageCustomerRequestTask extends ullBaseTask
{

  protected function configure()
  {
    $this->namespace        = 'ull_flow';
    $this->name             = 'install-package-customer_request';
    $this->briefDescription = 'Creates a customer request workflow';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] creates all database entires for
    a customer request workflow

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
    
    $this->setRecordNamespace('ull_flow_customer_request');
    
    //groups
    $groupDispatcher = $this->createRecord('UllGroup');
    $groupDispatcher['display_name'] = 'Customer Request Dispatcher';
    $groupDispatcher->save();    
    
    
    //app
    $app = $this->createRecord('UllFlowApp');
    $app['Translation']['en']['label'] = 'Customer request';
    $app['Translation']['en']['doc_label'] = 'Customer request';
    $app['Translation']['de']['label'] = 'Kundenanfrage';
    $app['Translation']['de']['doc_label'] = 'Kundenanfrage';    
    $app['list_columns'] = 
      'id,subject,customer_request_contact_name,priority,customer_request_due_date,assigned_to_ull_entity_id';
    $app['slug'] = 'customer_request';
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
    $columnConfigSubject['slug'] = 'customer_request_subject';
    $columnConfigSubject->save();
    
    $columnConfigDescription = $this->createRecord('UllFlowColumnConfig');
    $columnConfigDescription['Translation']['en']['label'] = 'Text';
    $columnConfigDescription['Translation']['de']['label'] = 'Text';
    $columnConfigDescription['UllFlowApp'] = $app;
    $columnConfigDescription['sequence'] = 2000;
    $columnConfigDescription['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Information update');
    $columnConfigDescription['slug'] = 'customer_request_text';
    $columnConfigDescription->save();
    
    $columnConfigSubject = $this->createRecord('UllFlowColumnConfig');
    $columnConfigSubject['Translation']['en']['label'] = 'Contact person';
    $columnConfigSubject['Translation']['de']['label'] = 'Ansprechpartner';
    $columnConfigSubject['UllFlowApp'] = $app;
    $columnConfigSubject['sequence'] = 3000;
    $columnConfigSubject['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('String');
    $columnConfigSubject['slug'] = 'customer_request_contact_name';
    $columnConfigSubject->save();

    $columnConfigSubject = $this->createRecord('UllFlowColumnConfig');
    $columnConfigSubject['Translation']['en']['label'] = 'Contact\'s email';
    $columnConfigSubject['Translation']['de']['label'] = 'E-Mail des Ansprechpartners';
    $columnConfigSubject['UllFlowApp'] = $app;
    $columnConfigSubject['sequence'] = 3500;
    $columnConfigSubject['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Email');
    $columnConfigSubject['slug'] = 'customer_request_contact_email';
    $columnConfigSubject->save();     
    
    $columnConfigPriority = $this->createRecord('UllFlowColumnConfig');
    $columnConfigPriority['Translation']['en']['label'] = 'Priority';
    $columnConfigPriority['Translation']['de']['label'] = 'Wichtigkeit';
    $columnConfigPriority['UllFlowApp'] = $app;
    $columnConfigPriority['sequence'] = 4000;
    $columnConfigPriority['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Priority');
    $columnConfigPriority['default_value'] = 3;
    $columnConfigPriority['is_priority'] = true;
    $columnConfigPriority['slug'] = 'customer_request_priority';
    $columnConfigPriority->save();    

    $columnConfigComment = $this->createRecord('UllFlowColumnConfig');
    $columnConfigComment['Translation']['en']['label'] = 'Due date';
    $columnConfigComment['Translation']['de']['label'] = 'Fällig am';
    $columnConfigComment['UllFlowApp'] = $app;
    $columnConfigComment['sequence'] = 5000;
    $columnConfigComment['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Date');
    $columnConfigComment['slug'] = 'customer_request_due_date';
    $columnConfigComment->save();  

    $columnConfigAttachment = $this->createRecord('UllFlowColumnConfig');
    $columnConfigAttachment['Translation']['en']['label'] = 'Attachments';
    $columnConfigAttachment['Translation']['de']['label'] = 'Anhänge';
    $columnConfigAttachment['UllFlowApp'] = $app;
    $columnConfigAttachment['sequence'] = 6000;
    $columnConfigAttachment['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Upload');
    $columnConfigAttachment['slug'] = 'customer_request_attachment';
    $columnConfigAttachment->save();      
    
    $columnConfigWikiLink = $this->createRecord('UllFlowColumnConfig');
    $columnConfigWikiLink['Translation']['en']['label'] = 'Wiki links';
    $columnConfigWikiLink['Translation']['de']['label'] = 'Wiki Links';
    $columnConfigWikiLink['UllFlowApp'] = $app;
    $columnConfigWikiLink['sequence'] = 7000;
    $columnConfigWikiLink['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Wiki link');
    $columnConfigWikiLink['slug'] = 'customer_request_wiki_link';
    $columnConfigWikiLink->save();      
    
    $columnConfigTags = $this->createRecord('UllFlowColumnConfig');
    $columnConfigTags['Translation']['en']['label'] = 'Tags';
    $columnConfigTags['Translation']['de']['label'] = 'Tags';
    $columnConfigTags['UllFlowApp'] = $app;
    $columnConfigTags['sequence'] = 8000;
    $columnConfigTags['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Taggable');
    $columnConfigTags['slug'] = 'customer_request_tags';
    $columnConfigTags->save();      

    
    // steps
    $stepCreator = $this->createRecord('UllFlowStep');
    $stepCreator['Translation']['en']['label'] = 'Creator';
    $stepCreator['Translation']['de']['label'] = 'Ersteller';
    $stepCreator['UllFlowApp'] = $app;    
    $stepCreator['is_start'] = true;
    $stepCreator['slug'] = 'customer_request_creator';
    $stepCreator->save();

    $stepDispatching = $this->createRecord('UllFlowStep');
    $stepDispatching['Translation']['en']['label'] = 'Dispatching';
    $stepDispatching['Translation']['de']['label'] = 'Vergabestelle';
    $stepDispatching['UllFlowApp'] = $app;    
    $stepDispatching['slug'] = 'customer_request_dispatching';
    $stepDispatching->save();

    $stepPersonResponsible = $this->createRecord('UllFlowStep');
    $stepPersonResponsible['Translation']['en']['label'] = 'Person responsible';
    $stepPersonResponsible['Translation']['de']['label'] = 'Sachbearbeiter';
    $stepPersonResponsible['UllFlowApp'] = $app;    
    $stepPersonResponsible['slug'] = 'customer_request_person_responsible';
    $stepPersonResponsible->save();     
    
    $stepClosed = $this->createRecord('UllFlowStep');
    $stepClosed['Translation']['en']['label'] = 'Closed';
    $stepClosed['Translation']['de']['label'] = 'Abgeschlossen';
    $stepClosed['UllFlowApp'] = $app;    
    $stepClosed['slug'] = 'customer_request_closed';
    $stepClosed->save();         
    
    
    //actions for steps
    $stepActionCreator1 = $this->createRecord('UllFlowStepAction');
    $stepActionCreator1['UllFlowStep'] = $stepCreator;
    $stepActionCreator1['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('send');
    $stepActionCreator1->save();

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

    $stepActionPersonResponsible1 = $this->createRecord('UllFlowStepAction');
    $stepActionPersonResponsible1['UllFlowStep'] = $stepPersonResponsible;
    $stepActionPersonResponsible1['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('return');
    $stepActionPersonResponsible1->save();
    
    $stepActionPersonResponsible2 = $this->createRecord('UllFlowStepAction');
    $stepActionPersonResponsible2['UllFlowStep'] = $stepPersonResponsible;
    $stepActionPersonResponsible2['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('close');
    $stepActionPersonResponsible2->save();    

    $stepActionClosed1 = $this->createRecord('UllFlowStepAction');
    $stepActionClosed1['UllFlowStep'] = $stepClosed;
    $stepActionClosed1['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('reopen');
    $stepActionClosed1->save();      
  }
}
