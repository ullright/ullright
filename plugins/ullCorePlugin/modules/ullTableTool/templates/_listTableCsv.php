<?php

$rows = array();

// normal rows
foreach($generator->getForms() as $form)
{
  $columns = array();

  foreach ($generator->getAutoRenderedColumns() as $column_name => $column_config)
  {
    $columns[] = ullCoreTools::prepareCsvColumn($form[$column_name]->render());
  }

  $row = implode(';', $columns);
  
  $rows[] = $row;
}

// sum row
if ($generator->getCalculateSums())
{
  $columns = array();
  
  foreach ($generator->getSumForm() as $widget)
  {
    $columns[] = ullCoreTools::prepareCsvColumn($widget->render());
  }
  
  $row = implode(';', $columns);
  
  $rows[] = $row;
}

$output = implode("\n", $rows);

echo $output;
