<?php

$generator = $sf_data->getRaw('generator');

$output = '';

if ($generator->getRow()->exists())
{
  $output .= get_partial('ullTableTool/ullResultListHeaderCsv', array(
    'generator' => $generator,
  ));

  $output .= get_partial('ullTableTool/listTableCsv', array(
    'generator' => $generator,
  ));
}

echo $output;