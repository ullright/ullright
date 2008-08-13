<?php

/**
 * Custom test browser that closes the doctrine connection before each call.
 *
 * @author Bernhard Schussek
 */
class sfDoctrineTestBrowser extends sfTestBrowser
{

	private
		$path			= '';

	/**
	 * Creates a composited test case
	 *
	 * @param  string $hostname
	 * @param  string $remote
	 * @param  array $options
	 */
	public function initialize($hostname = null, $remote = null, $options = array())
	{
		if (is_null(self::$test))
		{
			if(!array_key_exists('configuration', $options) ||
					!$options['configuration'] instanceof sfApplicationConfiguration)
			{
				throw new InvalidArgumentException(
						"An instance of class sfApplicationConfiguration is required when initializing the browser the first time");
			}
			
    		$output = isset($options['output']) 
    				? $options['output'] 
    				: new lime_output_color;
    		
			self::$test = new sfDoctrineTestCase(null, $output, 
					$options['configuration']);
					
    		unset($options['configuration']);
    		unset($options['output']);
		}
		
		parent::initialize($hostname, $remote, $options);
	}

	/**
	 * Clears the database and reloads the fixtures
	 */
	public function resetDatabase()
	{
		self::$test->reset();
	}

	/**
	 * Specifies the fixture YAML file or a directory containing YAML files
	 * which are loaded on every database reset.
	 *
	 * @param string $path
	 */
	public function setFixturesPath($path)
	{
		self::$test->setFixturesPath($path);
	}
	
	/**
	 * Specifies the directory containing the models to load.
	 *
	 * @param string $path
	 */
	public function setModelsPath($path)
	{
		self::$test->setModelsPath($path);
	}

	/**
	 * Closes the current database connection
	 *
	 * @param  string $uri
	 * @param  string $method
	 * @param  array $parameters
	 * @param  bool $changeStack
	 * @return sfDoctrineTestBrowser
	 */
	public function call($uri, $method = 'get', $parameters = array(), $changeStack = true)
	{
		// close old connection
		Doctrine_Manager::getInstance()->getCurrentConnection()->close();

		return parent::call($uri, $method, $parameters, $changeStack);
	}

}