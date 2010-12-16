<?php

/**
 * Test class to test classes or applications that depend on a Doctrine
 * connection.
 */
class sfDoctrineTestCase extends lime_test
{
	protected
		$configuration		= null,
		$mode             = 'mysql_dumps'
	;
		
	private
		$fixturesPath		= '',
		$modelsPath			= '',
		$resets				= 0;
		
	/**
	 * Sets the default fixtures and models path and initializes the database
	 *
	 * @param  int $plan
	 * @param  lime_output $output
	 * @param  sfApplicationConfiguration $configuration
	 */
	public function __construct($plan, lime_output $output, 
			sfApplicationConfiguration $configuration)
	{
		parent::__construct($plan, $output);
		
		$this->configuration = $configuration;
		
		$this->setFixturesPath($configuration->getRootDir() . '/data/fixtures');
		$this->setModelsPath($configuration->getRootDir() . '/lib/model/doctrine');
		
		$manager = new sfDatabaseManager($configuration);
	}
	
	/**
	 * Sets the path where Doctrine models are searched
	 *
	 * @param  string $modelsPath
	 */
	public function setModelsPath($modelsPath)
	{
		$this->modelsPath = $modelsPath;
	}
		
	/**
	 * Sets the path where database fixtures are searched
	 *
	 * @param  string $fixturesPath
	 */
	public function setFixturesPath($fixturesPath)
	{
		$this->fixturesPath = $fixturesPath;
	}
	
	/**
	 * Sets the test mode.
	 * 
   * 'mysql_dumps' (default)
   * A mysql dump is created in the cache/ directory foreach fixture path
   * If a dump is found, the database is reseted using that dump
   * Initial creation takes about 10 sec
   * Subsequent reload takes about 2sec (KU 2010-03-15)
   * TODO: check why some test fail is this mode (ullFlowFormTest, ullMetaWidgetUllEntityTest)
   *
   * 'yml_fixtures'
   * The "classic" way. Foreach reset() the database is recreated and the
   * yml fixtures are loaded for the given fixture path.
   * Is slow currently, because creating the many db tables takes 
   * about 6-7 sek (KU 2010-03-15)
   *
	 * @param string $mode   'mysql_dumps' (default),  'yml_fixtures'
	 * @return self
	 */
	public function setMode($mode)
	{
	  $this->mode = $mode;
	  
	  return $this;
	}

	/**
	 * Gets the current test mode
	 * 
	 * @return string
	 */
	public function getMode()
	{
	  return $this->mode;
	}	

	
	/**
	 * Resets the database connection and reloads the fixtures
	 * 
	 * Renamed because of name collision with sf1.3 lime
	 */
	public function reset1()
	{
	  $con = Doctrine_Manager::getInstance()->getCurrentConnection(); 
	  
	  if ($this->mode == 'mysql_dumps' && $con->getDriverName() == 'Mysql')
	  {
//	  timelog('start');
	  
  	  $testDbDumpFile = sfConfig::get('sf_cache_dir') . '/' .
  	   ullCoreTools::sluggify(str_replace(sfConfig::get('sf_root_dir'), '', $this->fixturesPath)) . 
  	   '.mysql.dump';
  
      $dbName = Doctrine_Manager::getInstance()->getCurrentConnection()->getName();
      
      $connectionOptions = Doctrine_Manager::getInstance()->getCurrentConnection()->getOptions();
    
      $dbUsername = $connectionOptions['username'];
      $dbPassword = $connectionOptions['password'];	  
	  
  	  if (!file_exists($testDbDumpFile))
  	  {
  	    $this->diag("No cached mysql test database dump found - recreating - please wait a moment...");
  	    
        $this->recreateDatabase();
        $this->createTables();
        
        $this->clearTables();
        $this->loadFixtures(true);      	    
	    
//        $this->recreateDatabase();
//        $task = new sfDoctrineBuildTask(new sfEventDispatcher, new sfFormatter);
//        chdir(sfConfig::get('sf_root_dir'));
//        $task->run(array(), array(
//          'env'       => 'test',
//          'db'        => true,
//          'and-load'  => true,
//          'no-confirmation' => true,
//        ));
      
//        timelog('sfDoctrineBuildTask');
          
        $cmd = "mysqldump -u {$dbUsername} --password={$dbPassword} {$dbName} > {$testDbDumpFile}";
        shell_exec($cmd);
        
//        timelog('mysql dump');
  	  }
  	  else
  	  {
  	    $this->recreateDatabase();
  	    
//  	    timelog('recreate db');
  	    
        $cmd = "mysql -u {$dbUsername} --password={$dbPassword} {$dbName} < {$testDbDumpFile}";
        shell_exec($cmd);
        
        $this->clearTables();
        
//        timelog('mysql load');
  	  }
  	  
  	  
	  }
	  
	  // other modes 
	  else 
	  {
      // reset user_id, otherwise some records  will set creator,... to the current user_id
      $oldUserId = sfContext::getInstance()->getUser()->getAttribute('user_id');
      sfContext::getInstance()->getUser()->setAttribute('user_id', null);
      
      Doctrine::loadModels($this->modelsPath);
        
      if ($this->resets == 0)
      {
        $this->recreateDatabase();
        $this->createTables();
      }
      else
      {
        $this->eraseTables();
      }
      
      $this->clearTables();
      $this->loadFixtures();
  //    $this->clearTables();
      
      ++$this->resets;
      
      sfContext::getInstance()->getUser()->setAttribute('user_id', $oldUserId);
	  }
	}
	
	/**
	 * Displays the given message and reloads the database
	 *
	 * @see    diag()
	 * @see    reset()
	 * @param  string $message
	 */
	public function begin($message)
	{
		$this->diag($message);
		$this->reset1();
	}
	
	/**
	 * "Login by username"
	 * 
	 * Set the given user_id in the session
	 *
	 * @param string $username
	 */
	public function loginAs($username)
	{
    $userId = Doctrine::getTable('UllUser')->findOneByUsername($username)->id;
    sfContext::getInstance()->getUser()->setAttribute('user_id', $userId);	
	}
	
  /**
   * "Logout"
   * 
   * Deletes the user_id in the session
   *
   * @param string $username
   */
  public function logout()
  {
    sfContext::getInstance()->getUser()->getAttributeHolder()->remove('user_id');  
  }	
	
	/**
	 * Deletes the database and creates it again
	 */
	private function recreateDatabase()
	{
		$con =  Doctrine_Manager::getInstance()->getCurrentConnection();
		
		if($con->getDriverName() === 'Mysql')
		{
			Doctrine::dropDatabases();
			Doctrine::createDatabases();
		}
		else
		{
			$con->close();
			$con->connect();
		}
	}
	
	/**
	 * Creates all database tables
	 */
	private function createTables()
	{
		$loadModels = !$this->isModelLoaded();
		// createTablesFromModels loads also the models of templates!
		Doctrine::createTablesFromModels($loadModels ? $this->modelsPath : null);
	}
	
	private function eraseTables()
	{
		$con = Doctrine_Manager::getInstance()->getCurrentConnection(); 
		$dbh = $con->getDbh();
		$models = Doctrine::getLoadedModels();
		
		if($con->getDriverName() === 'Mysql')
		{
			$dbh->exec("SET FOREIGN_KEY_CHECKS = 0");
		
			foreach($models as $model)
			{
				$dbh->exec("TRUNCATE " . Doctrine::getTable($model)->getTableName());
			}
		
			$dbh->exec("SET FOREIGN_KEY_CHECKS = 1");
		}
		else
		{
			foreach($models as $model)
			{
				$dbh->exec("DELETE FROM " . Doctrine::getTable($model)->getTableName());
			}
			
		}
	}
	
	/** 
	 * Clears the first level cache (identityMap) of the models
   *
   * This method ensures that records are reloaded from the db
	 */ 
	private function clearTables()
	{
		$models = Doctrine::getLoadedModels();
		
		foreach($models as $model)
		{
			$table = Doctrine::getTable($model)->clear();
		}
	}
	
	/**
	 * Loads the fixtures specified with setFixturesPath()
	 * 
	 * @see setFixturesPath()
	 */
	private function loadFixtures($append = false)
	{
		Doctrine::loadData($this->fixturesPath, $append);
	}
	
	/**
	 * Returns whether the models have already been loaded into memory
	 *
	 * @return bool
	 */
	private function isModelLoaded()
	{
		return count(Doctrine::getLoadedModels()) != 0;
	}
}