<?php

class ullFlowInstallPackageOnboardingProcurementCarTask extends ullBaseTask
{
  
  
  protected function configure()
  {
    $this->camelcase_name = 'OnboardingProcurementCar';
    $this->short_name = 'OnboardProcCar'; // <= 32 chars
    $this->underscore_name = sfInflector::underscore($this->camelcase_name);
    $this->humanized_name = sfInflector::humanize($this->underscore_name);
    $this->hyphen_name = ullCoreTools::htmlId($this->underscore_name);
    
    $this->namespace        = 'ull_flow';
    $this->name             = 'install-package-' . $this->hyphen_name;
    $this->briefDescription = 'Creates a ' . $this->humanized_name . ' request workflow';
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
    
    //groups
    $groupDispatcher = $this->createRecord('UllGroup');
    $groupDispatcher['display_name'] = 'Procurement Clerks';
    $groupDispatcher->save();
    
    
    //app
    $app = $this->createRecord('UllFlowApp');
    $app['Translation']['en']['label'] = 'Onboarding - Procurement - Car';
    $app['Translation']['en']['doc_label'] = 'Onboarding - Procurement - Car';
    $app['Translation']['de']['label'] = 'Onboarding - Einkauf - Auto';
    $app['Translation']['de']['doc_label'] = 'Onboarding - Einkauf - Auto';    
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
    $select['Translation']['en']['label'] = "Type";
    $select['Translation']['de']['label'] = "Typ";
    $select['slug'] = $this->underscore_name . '_type';
    $select['UllSelectChildren'][0]['Translation']['en']['label'] = "";
    $select['UllSelectChildren'][0]['Translation']['de']['label'] = "";
    $select['UllSelectChildren'][1]['Translation']['en']['label'] = "Skoda Fabia Kombi";
    $select['UllSelectChildren'][1]['Translation']['de']['label'] = "Skoda Fabia Kombi";
    $select['UllSelectChildren'][2]['Translation']['en']['label'] = "Golf Plus";
    $select['UllSelectChildren'][2]['Translation']['de']['label'] = "Golf Plus";
    $select['UllSelectChildren'][3]['Translation']['en']['label'] = "Audi A4";
    $select['UllSelectChildren'][3]['Translation']['de']['label'] = "Audi A4";        
    $select->save();
    
    $columnConfig = $this->createRecord('UllFlowColumnConfig');
    $columnConfig['Translation']['en']['label'] = 'Type';
    $columnConfig['Translation']['de']['label'] = 'Typ';
    $columnConfig['UllFlowApp'] = $app;
    $columnConfig['sequence'] = 2000;
    $columnConfig['UllColumnType'] = 
      Doctrine::getTable('UllColumnType')->findOneByLabel('UllSelect');
    $columnConfig['is_mandatory'] = true;
    $columnConfig['slug'] = $this->underscore_name . '_type';
    $columnConfig['options'] = 'ull_select=' . $this->underscore_name . '_type';
    $columnConfig->save();
    
    $select = new UllSelect();
    $select['Translation']['en']['label'] = "Extras";
    $select['Translation']['de']['label'] = "Extras";
    $select['slug'] = $this->underscore_name . '_extras';
    $select['UllSelectChildren'][0]['Translation']['en']['label'] = "";
    $select['UllSelectChildren'][0]['Translation']['de']['label'] = "";    
    $select['UllSelectChildren'][1]['Translation']['en']['label'] = "Cruise control";
    $select['UllSelectChildren'][1]['Translation']['de']['label'] = "Tempomat";
    $select['UllSelectChildren'][2]['Translation']['en']['label'] = "Seat heating";
    $select['UllSelectChildren'][2]['Translation']['de']['label'] = "Sitzheizung";
    $select['UllSelectChildren'][3]['Translation']['en']['label'] = "Navigaton device";
    $select['UllSelectChildren'][3]['Translation']['de']['label'] = "Navigationsgerät";        
    $select->save();
    
    $columnConfig = $this->createRecord('UllFlowColumnConfig');
    $columnConfig['Translation']['en']['label'] = 'Extras';
    $columnConfig['Translation']['de']['label'] = 'Extras';
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
