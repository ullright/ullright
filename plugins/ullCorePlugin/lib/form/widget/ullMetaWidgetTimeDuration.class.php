<?php
/**
 * Meta widget for time durations
 * Example: Buying shoes: 1:30h
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullMetaWidgetTimeDuration extends ullMetaWidgetTime
{
  protected
    $readWidget = 'ullWidgetTimeDurationRead',
    $writeWidget = 'ullWidgetTimeDurationWrite',
    $validator = 'ullValidatorTimeDuration'
  ;    
}