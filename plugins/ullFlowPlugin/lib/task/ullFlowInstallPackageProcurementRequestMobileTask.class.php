<?php

class ullFlowInstallPackageProcurementRequestMobileTask extends ullBaseTask
{
  
  
  protected function configure()
  {
    $this->camelcase_name = 'ProcurementRequestMobile';
    $this->short_name = 'ProcurementMobile'; // <= 32 chars
    $this->underscore_name = sfInflector::underscore($this->camelcase_name);
    $this->humanized_name = sfInflector::humanize($this->underscore_name);
    $this->hyphen_name = ullCoreTools::htmlId($this->underscore_name);
    
    $this->namespace        = 'ull_flow';
    $this->name             = 'install-package-' . $this->hyphen_name;
    $this->briefDescription = 'Creates a ' . $this->humanized_name . ' workflow';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] creates all database entires for
    a ullFlow workflow

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
    
    $this->setRecordNamespace('ull_flow_' . $this->short_name);
    
//    //groups
//    $groupDispatcher = $this->createRecord('UllGroup');
//    $groupDispatcher['display_name'] = 'Procurement Clerks';
//    $groupDispatcher->save();
    
    
    //app
    $app = $this->createRecord('UllFlowApp');
    $app['Translation']['en']['label'] = 'Procurement Request Mobile';
    $app['Translation']['en']['doc_label'] = 'Procurement Request Mobile';
    $app['Translation']['de']['label'] = 'Einkaufsanforderung Handy';
    $app['Translation']['de']['doc_label'] = 'Einkaufsanforderung Handy';    
//    $app['list_columns'] = 
//      'id,subject,customer_request_contact_name,priority,customer_request_due_date,assigned_to_ull_entity_id';
    $app['slug'] = $this->underscore_name;
    $app->save();
        
    
    // columns
    $columnConfig = $this->createRecord('UllFlowColumnConfig');
    $columnConfig['Translation']['en']['label'] = 'Person';
    $columnConfig['Translation']['de']['label'] = 'Person';
    $columnConfig['UllFlowApp'] = $app;
    $columnConfig['sequence'] = 1000;
    $columnConfig['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Caller');
    $columnConfig['is_mandatory'] = true;
    $columnConfig['is_subject'] = true;
    $columnConfig['slug'] = $this->underscore_name . '_person';
    $columnConfig->save();
    
    $select = new UllSelect();
    $select['Translation']['en']['label'] = "Mobile type";
    $select['Translation']['de']['label'] = "Handytyp";
    $select['slug'] = $this->underscore_name . '_type';
    $select['UllSelectChildren'][0]['Translation']['en']['label'] = "";
    $select['UllSelectChildren'][0]['Translation']['de']['label'] = "";
    $select['UllSelectChildren'][1]['Translation']['en']['label'] = "Basic";
    $select['UllSelectChildren'][1]['Translation']['de']['label'] = "Basismodell";
    $select['UllSelectChildren'][2]['Translation']['en']['label'] = "Advanced";
    $select['UllSelectChildren'][2]['Translation']['de']['label'] = "Standardmodell";
    $select['UllSelectChildren'][3]['Translation']['en']['label'] = "Smartphone";
    $select['UllSelectChildren'][3]['Translation']['de']['label'] = "Smartphone";        
    $select->save();
    
    $columnConfig = $this->createRecord('UllFlowColumnConfig');
    $columnConfig['Translation']['en']['label'] = $select['Translation']['en']['label'];
    $columnConfig['Translation']['de']['label'] = $select['Translation']['de']['label'];
    $columnConfig['UllFlowApp'] = $app;
    $columnConfig['sequence'] = 2000;
    $columnConfig['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('UllSelect');
    $columnConfig['is_mandatory'] = true;
    $columnConfig['slug'] = $this->underscore_name . '_type';
    $columnConfig['options'] = 'ull_select=' . $this->underscore_name . '_type';
    $columnConfig->save();
    
    $select = new UllSelect();
    $select['Translation']['en']['label'] = "Contract";
    $select['Translation']['de']['label'] = "Vertrag";
    $select['slug'] = $this->underscore_name . '_extras';
    $select['UllSelectChildren'][0]['Translation']['en']['label'] = "";
    $select['UllSelectChildren'][0]['Translation']['de']['label'] = "";    
    $select['UllSelectChildren'][1]['Translation']['en']['label'] = "Without private calls";
    $select['UllSelectChildren'][1]['Translation']['de']['label'] = "Ohne Privatgespräche";
    $select['UllSelectChildren'][2]['Translation']['en']['label'] = "Including private calls";
    $select['UllSelectChildren'][2]['Translation']['de']['label'] = "Mit Privatgesprächen";
    $select['UllSelectChildren'][3]['Translation']['en']['label'] = "All inclusive";
    $select['UllSelectChildren'][3]['Translation']['de']['label'] = "All inclusive";        
    $select->save();
    
    $columnConfig = $this->createRecord('UllFlowColumnConfig');
    $columnConfig['Translation']['en']['label'] = $select['Translation']['en']['label'];
    $columnConfig['Translation']['de']['label'] = $select['Translation']['de']['label'];
    $columnConfig['UllFlowApp'] = $app;
    $columnConfig['sequence'] = 3000;
    $columnConfig['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('UllSelect');
    $columnConfig['slug'] = $this->underscore_name . '_extras';
    $columnConfig['options'] = 'ull_select=' . $this->underscore_name . '_extras';
    $columnConfig->save();

    $columnConfig = $this->createRecord('UllFlowColumnConfig');
    $columnConfig['Translation']['en']['label'] = 'Note';
    $columnConfig['Translation']['de']['label'] = 'Bemerkung';
    $columnConfig['UllFlowApp'] = $app;
    $columnConfig['sequence'] = 4000;
    $columnConfig['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Textarea');
    $columnConfig['slug'] = $this->underscore_name . '_note';
    $columnConfig->save();       
    
    $columnConfig = $this->createRecord('UllFlowColumnConfig');
    $columnConfig['Translation']['en']['label'] = 'Due date';
    $columnConfig['Translation']['de']['label'] = 'Fällig am';
    $columnConfig['UllFlowApp'] = $app;
    $columnConfig['sequence'] = 5000;
    $columnConfig['is_due_date'] = true;
    $columnConfig['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Date');
    $columnConfig['slug'] = $this->underscore_name . '_due_date';
    $columnConfig->save();  

    $columnConfigAttachment = $this->createRecord('UllFlowColumnConfig');
    $columnConfigAttachment['Translation']['en']['label'] = 'Attachments';
    $columnConfigAttachment['Translation']['de']['label'] = 'Anhänge';
    $columnConfigAttachment['UllFlowApp'] = $app;
    $columnConfigAttachment['sequence'] = 6000;
    $columnConfigAttachment['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('Upload');
    $columnConfigAttachment['slug'] = $this->underscore_name . '_attachments';
    $columnConfigAttachment->save();      

    
    // steps
    $stepCreator = $this->createRecord('UllFlowStep');
    $stepCreator['Translation']['en']['label'] = 'Creator';
    $stepCreator['Translation']['de']['label'] = 'Ersteller';
    $stepCreator['UllFlowApp'] = $app;    
    $stepCreator['is_start'] = true;
    $stepCreator['slug'] = $this->underscore_name . '_creator';
    $stepCreator->save();

    $stepClerk = $this->createRecord('UllFlowStep');
    $stepClerk['Translation']['en']['label'] = 'Clerk';
    $stepClerk['Translation']['de']['label'] = 'Sachbearbeiter';
    $stepClerk['UllFlowApp'] = $app;    
    $stepClerk['slug'] = $this->underscore_name . '_clerk';
    $stepClerk->save();     
    
    $stepClosed = $this->createRecord('UllFlowStep');
    $stepClosed['Translation']['en']['label'] = 'Closed';
    $stepClosed['Translation']['de']['label'] = 'Abgeschlossen';
    $stepClosed['UllFlowApp'] = $app;    
    $stepClosed['slug'] = $this->underscore_name . '_closed';
    $stepClosed->save();         
    
    
    //actions for steps
    $stepAction = $this->createRecord('UllFlowStepAction');
    $stepAction['UllFlowStep'] = $stepCreator;
    $stepAction['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('send');
    $stepAction->save();

    $stepAction = $this->createRecord('UllFlowStepAction');
    $stepAction['UllFlowStep'] = $stepClerk;
    $stepAction['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('assign_to_user');
    $stepAction->save();
    
    $stepAction = $this->createRecord('UllFlowStepAction');
    $stepAction['UllFlowStep'] = $stepClerk;
    $stepAction['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('close');
    $stepAction->save();    

    $stepAction = $this->createRecord('UllFlowStepAction');
    $stepAction['UllFlowStep'] = $stepClosed;
    $stepAction['UllFlowAction'] = 
      Doctrine::getTable('UllFlowAction')->findOneBySlug('reopen');
    $stepAction->save();      
  }
}
