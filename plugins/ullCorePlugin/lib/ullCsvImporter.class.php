<?php

/**
 * Simple importer for comma separated value files (csv)
 * 
 * - Currently takes a path to a file 
 * - Features auto-detection of delimiter 
 * - Currently supports comma, semicolon and tabs as delimiters
 * - Returns an array with the column headers as keys:
 * 
 * csv:
 *  "First name","Email"
 *  "Claudio","claudio@example.com"
 *  "Hugo",
 * 
 * array:
 *    1 => 
 *      'First name' => 'Claudio'
 *      'Email' => 'claudio@example.com'
 *    2 => 
 *      'First name' => 'Hugo'
 *      'Email' => null
 *      
 * @see unit test ullCsvImporterTest.php for more examples
 *
 * Assumptions:
 *
 * - The first line of the csv file are the column header names
 * - Enclosure is currently fixed to double quotes "
 * - Escape is currently fixed to backslash \
 * 
 *
 * @todo allow to give csv as string, allow config of enclosure
 *
 * @author klemens.ullmann-marx@ull.at
 */
class ullCsvImporter
{
  protected
    $path,
    $handle,
    $delimiter,
    $validDelimitersAsciiValues = array(
      59,   // ';'
      44,   // ','
      9,    // Tab
    ),
    $headers
  ;
  
  /**
   * Constructor
   *
   * @param string $path
   */
  public function __construct($path)
  {
    $this->path = $path; 
    
    $this->handle = fopen($this->path, 'r');
    
    $this->delimiter = $this->detectDelimiter();
    
    $this->headers = $this->readHeaders();
  }

  
  /**
   * Return the currently used delimiter
   */
  public function getDelimiter()
  {
    return $this->delimiter;
  }

  /**
   * Get the column names from the first line
   */
  public function getHeaders()
  {
    return $this->headers;
  }

  
  /**
   * Read the headers from the first line (column names)
   */
  public function readHeaders()
  {
    rewind($this->handle); 
    
    $headers = fgetcsv($this->handle, 0, $this->getDelimiter());
    
    return $headers;
  }
  
  
  /**
   * Read the csv file and convert it to an array indexed by the header column
   * names
   */
  public function toArray()
  {
    // Rewind file handle pointer and set to beginning of first data line 
    rewind($this->handle); 
    
    // TODO: KU 2012-10-04 is this line necessary?
    fgetcsv($this->handle, 0, $this->getDelimiter());
    
    $return = array();
    $lineNumber = 1;
    
    while ($line = fgetcsv($this->handle, 0, $this->getDelimiter()))
    {
      // Ignore empty lines
      if (!(count($line) === 1 && $line[0] === null))
      {
        $returnLine = array();
        
        // Create index names also for empty cells
        foreach ($this->headers as $columnNum => $name)
        {
          if (isset($line[$columnNum]))
          {
            $value = trim($line[$columnNum]);
          }
          else
          {
            $value = null;
          }
          
          $returnLine[$name] = $value;
        }
        
        $return[$lineNumber] = $returnLine;
      }
      
      $lineNumber++;
    }
    
    return $return;
  }
  
  
  /**
   * Try to detect the used csv delimiter by doing a bit of statistics
   * on the most commonly used separater characters
   * 
   * Choose the most often used delimiter
   */
  protected function detectDelimiter()
  {
    // Get the first 1024 characters
    $csv = file_get_contents($this->path, false, null, -1, 1024);
    
    $array = array();
    
    for ($i=0; $i<strlen($csv); $i++)
    { 
      $asciiValue = ord($csv[$i]);
      
      if (in_array($asciiValue, $this->validDelimitersAsciiValues))
      {
        if (isset($array[$asciiValue]))
        {
          $array[$asciiValue]++;
        }
        else 
        {
          $array[$asciiValue] = 1;
        }
      }
    }

    asort($array, SORT_NUMERIC);
    
    return chr(key($array));
  }
    
}