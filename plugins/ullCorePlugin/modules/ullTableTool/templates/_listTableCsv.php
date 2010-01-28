<?php

foreach($generator->getForms() as $form)
{
  $row = array();

  foreach ($generator->getAutoRenderedColumns() as $column_name => $column_config)
  {
    $row[] = ullCoreTools::prepareCsvColumn($form[$column_name]->render());
  }

  echo implode(';', $row) . "\n";
}
