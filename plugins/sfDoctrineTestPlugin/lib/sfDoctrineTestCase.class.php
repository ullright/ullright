<?php

/**
 * Test class to test classes or applications that depend on a Doctrine
 * connection.
 */
class sfDoctrineTestCase extends lime_test
{
	protected
		$configuration		= null;
		
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
	 * Resets the database connection and reloads the fixtures
	 */
	public function reset()
	{
	  // reset user_id, otherwise some records  will set creator,... to the current user_id
	  $oldUserId = sfContext::getInstance()->getUser()->getAttribute('user_id');
	  sfContext::getInstance()->getUser()->setAttribute('user_id', null);
	  
		Doctrine::loadModels($this->modelsPath);
			
		if($this->resets==0)
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
//		$this->clearTables();
		
		++$this->resets;
		
		sfContext::getInstance()->getUser()->setAttribute('user_id', $oldUserId);
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
		$this->reset();
	}
	
	/**
	 * "Login by username"
	 *
	 * @param string $username
	 */
	public function loginAs($username)
	{
    $userId = Doctrine::getTable('UllUser')->findOneByUsername($username)->id;
    sfContext::getInstance()->getUser()->setAttribute('user_id', $userId);	
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
	private function loadFixtures()
	{
		Doctrine::loadData($this->fixturesPath);
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