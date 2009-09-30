<?php

class CreateTimePeriodsTask extends sfBaseTask
{

  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'create-time-periods';
    $this->briefDescription = 'Create time periods';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] automatically creates time periods for the ullTime module
    foreach month for the given startdate and enddate.

    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
EOF;

    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
    
    $this->addOption('start-date', null, sfCommandOption::PARAMETER_OPTIONAL, 
      'Start month. Default is the current month. Format: YYYY-MM', date('Y-m'));
    $this->addOption('end-date', null, sfCommandOption::PARAMETER_REQUIRED, 
      'End date, Format: YYYY-MM');
    $this->addOption('languages', null, sfCommandOption::PARAMETER_OPTIONAL, 
      'Comma separated list of languages. Default: "en,de"', 'en,de');
  }


  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection($this->name, 'Initializing');

    $configuration = ProjectConfiguration::getApplicationConfiguration(
    $arguments['application'], $arguments['env'], true);
    $databaseManager = new sfDatabaseManager($configuration);
    
    sfLoader::loadHelpers(array('ull'));
    
    if (!$options['end-date'])
    {
      $this->logBlock('Option --end-date is required', 'ERROR');
    }
    
    $options['languages'] = explode(',', $options['languages']);
    foreach ($options['languages'] as &$value)
    {
      $value = trim($value);
    }
    
    
    
    $this->logSection($this->name, 'Creating time periods...');
    
    $startDateStamp = strtotime($options['start-date']. '-01');
    $endDateStamp = strtotime(date('Y-m-t', strtotime($options['end-date']. '-01')));
    
    for ($i = $startDateStamp; $i < $endDateStamp; $i = strtotime('+1 month', $i))
    {
      $period = new UllTimePeriod;
      $period->from_date = date('Y-m-d', $i);
      $period->to_date = date('Y-m-t', strtotime($period->from_date));
      foreach ($options['languages'] as $language)
      {
        $period->Translation[$language]->name = format_datetime($i, 'MMMM yyyy', $language);
      }
      
      if ($overlap = UllTimePeriodTable::periodExists($period->from_date, $period->to_date))
      {
        $this->logBlock(
          'Period ' . 
          $period->from_date . 
          ' -> ' . 
          $period->to_date . 
          ' overlapps the following existing periods and will not be created:' .
          "\n\n" .
          print_r($overlap, true),
          'ERROR'
        );
      }
      else
      {
        $period->save();
        $this->log('Created period ' . $period->slug);
      }
    }

  }
}