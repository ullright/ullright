<?php

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
    $headers = fgetcsv($this->handle, 0, $this->getDelimiter());
    
    return $headers;
  }
  
  
  /**
   * Read the csv file and convert it to an array indexed by the header column
   * names
   */
  public function toArray()
  {
    $return = array();
    
    // Note that the handle already points to the second line due to readHeaders()
    while ($line = fgetcsv($this->handle ,0, $this->getDelimiter()))
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
        
        $return[] = $returnLine;
      }
    }
    
    return $return;
  }
  
  
  /**
   * Try to detect the used csv delimiter
   * 
   * Choose the most often used delimiter
   */
  protected function detectDelimiter()
  {
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