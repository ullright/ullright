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
	public function __construct($hostname = null, $remote = null, $options = array())
	{
		if (is_null(self::$test))
		{
      		$browser = new sfBrowser($hostname, $remote, $options);
      
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

		parent::__construct($browser, self::$test);
	}

	/**
	 * Clears the database and reloads the fixtures
	 */
	public function resetDatabase()
	{
		self::$test->reset();

		return $this;
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

		ob_start();
		$result = parent::call($uri, $method, $parameters, $changeStack);
		ob_end_clean();
		
		// Deactivated for compatibility with sf 1.3 lime
		//echo lime_colorizer::colorize(sprintf("> %s %s\n", $method, $uri), array('fg' => 'cyan'));
		echo sprintf("> %s %s\n", $method, $uri);
		
		return $result;
	}
	

	public function diag($string)
	{
		self::$test->diag(str_replace('ok', 'OK', $string));

		return $this;
	}
	
	private function colorizeTag($string)
	{
		return lime_colorizer::colorize($string, array('fg' => 'cyan'));
	}
	
	private function colorizeAttribute($string)
	{
		$parts = explode('=', $string);
		
		return lime_colorizer::colorize($parts[0], array('fg' => 'magenta'))
				. lime_colorizer::colorize('=', array('fg' => 'cyan'))
				. lime_colorizer::colorize($parts[1], array('fg' => 'blue'));
	}
	
	public function colorize(array $strings)
	{
		$pattern = '/[\w:]+="[^"]+"/';
		
		preg_match_all($pattern, $strings[0], $attributes);
		$parts = preg_split($pattern, $strings[0]);
		
		$result = $this->colorizeTag($parts[0]);
		
		for ($i=1; $i<count($parts); ++$i)
		{
			$result .= $this->colorizeAttribute($attributes[0][$i-1]);
			$result .= $this->colorizeTag($parts[$i]);
		}
		
		return $result;
	}

	public function dump()
	{
	  echo "--- parameters ---\n";
	  var_dump($this->getRequest()->getParameterHolder()->getAll());
	  
	  echo "--- session data ---\n";
    var_dump($this->getUser()->getAttributeHolder()->getAll());
	  
		echo "--- html source ---\n";
		
		echo preg_replace_callback('/<[^>]+>/', array($this, 'colorize'), 
				$this->getResponse()->getContent());
				
		return $this;
		// check if program 'highlight' exists
		// installation under debian/ubuntu: sudo aptitude install highlight
		if (strstr(shell_exec('which highlight'), 'highlight'))
		{
			$html = escapeshellarg($this->getResponse()->getContent());
			echo shell_exec('echo ' . $html . ' | highlight -S xml -A');
		}
		else
		{
			echo $this->getResponse()->getContent() . "\n";
		}
		echo "--- /html source ---\n";
		 
		return $this;
	}

	public function dumpdie()
	{
		$this->dump();

		throw new exception('Dying as requested...');
	}


}