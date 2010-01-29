<?php

$generator = $sf_data->getRaw('generator');

$labels = $generator->getAutoRenderedLabels();

foreach ($labels as $key => $label)
{
  $labels[$key] = ullCoreTools::prepareCsvColumn($label);
}

$row = implode(';', $labels);

echo $row . "\n";