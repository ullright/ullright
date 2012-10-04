<?php

if (count($data))
{
  foreach ($data as $row)
  {
    $columns = array();
    
    foreach ($row as $column)
    {
      $columns[] = ullCoreTools::prepareCsvColumn($column);
    }
    
    $row = implode(';', $columns);
    
    echo $row . "\n";  
  }
}
