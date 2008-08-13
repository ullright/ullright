<?php

/**
 * Test class to test classes or applications that depend on a Doctrine
 * connection.
 * 
 * @author Bernhard Schussek
 */
class sfDoctrineTestCase extends lime_test
{
	protected
		$configuration		= null;
		
	private
		$fixturesPath		= '',
		$modelsPath			= '';
		
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
		if(!$this->isModelLoaded())
		{
			$this->recreateDatabase();
			$this->createTables();
		}
		else
		{
			Doctrine::loadModels($this->modelsPath);
			$this->eraseTables();
		}
		$this->loadFixtures();
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
	 * Deletes the database and creates it again
	 */
	private function recreateDatabase()
	{
		Doctrine::dropDatabases();
		Doctrine::createDatabases();
	}
	
	/**
	 * Creates all database tables
	 */
	private function createTables()
	{
		$loadModels = !$this->isModelLoaded();
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