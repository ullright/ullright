<?php

/**
 * Simple importer for comma separated value files (csv)
 * 
 * - Features auto-detection of delimiter 
 * - Currently supports comma, semicolon and tabs as delimiters
 *
 * Assumptions:
 *
 * - The first line of the csv file is assumed to be the column header names
 * - Enclosure is currently fixed to double quotes "
 * - Escape is currently fixed to backslash \
 
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
    $headers,
    $includeEmptyLines = true
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
   * Set if empty lines should be included
   * 
   * By default empty lines are included to allow finding out the original
   * line number
   * 
   * @param boolean $boolean
   */
  public function setIncludeEmptyLines($boolean)
  {
    $this->includeEmptyLines = (boolean) $boolean;
    
    return $this;
  }

  
  /**
   * Get setting of if empty lines should be included
   */
  public function getIncludeEmptyLines()
  {
    return $this->includeEmptyLines;
  }
  
  
  /**
   * Read the csv file and convert it to an array indexed by the header column
   * names
   */
  public function toArray()
  {
    // Rewind file handle pointer and set to beginning of first data line
    rewind($this->handle);
    fgetcsv($this->handle, 0, $this->getDelimiter());
    
    $return = array();
    
    // Note: the handle already points to the second line due to readHeaders()
    while ($line = fgetcsv($this->handle, 0, $this->getDelimiter()))
    {
      // Detect empty line
      $isEmpty = (boolean) (count($line) === 1 && $line[0] === null);
      
      // Check includeEmptyLines setting
      $includeLine = (boolean) ! ( !$this->getIncludeEmptyLines() && $isEmpty);
      
      if ($includeLine)
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