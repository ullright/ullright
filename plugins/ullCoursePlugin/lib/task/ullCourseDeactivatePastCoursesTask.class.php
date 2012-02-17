<?php

class ullCourseDeactivatePastCoursesTask extends ullBaseTask
{
  protected function configure()
  {
  	$this->namespace        = 'ull_course';
    $this->name             = 'deactivate-past-courses';
  	$this->briefDescription = 'Deactivated past courses';
  	$this->detailedDescription = <<<EOF
The [{$this->name} task|INFO] runs this task

Call it with:

  [php symfony {$this->namespace}:{$this->name}|INFO]
EOF;

  	$this->addArgument('application', sfCommandArgument::OPTIONAL,
  	  'The application name', 'frontend');
  	$this->addArgument('env', sfCommandArgument::OPTIONAL,
  	  'The environment', 'prod');
  }
  

  protected function execute($arguments = array(), $options = array())
  {
   // $this->logSection($this->name, 'Initializing');
    
    $this->initializeDatabaseConnection($arguments, $options);
    
    $q = new ullQuery('UllCourse');
    
    $q->addWhere('end_date < ?', date('Y-m-d'));
    $q->addWhere('is_active = ?', true);
    
    $courses = $q->execute();
    
    if (!count($courses))
    {
      exit();
    }
    
    sfContext::createInstance($this->configuration);
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('ull'));
    
    foreach ($courses as $course)
    {
      $course->is_active = false;
      $course->save();
      $this->log('Deactivated course ' . $course . ' - ' . ull_format_date($course->end_date));
    }

  }
  
}
