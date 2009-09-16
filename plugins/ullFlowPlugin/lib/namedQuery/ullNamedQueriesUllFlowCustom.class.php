<?php

/**
 * Fallback class for custom ullFlow named queries
 * 
 * This class here in plugin/ullFlowPlugin/lib/namedQuery/
 * is only an empty fallback, in the case a customer doesn't provide
 * a custom ullNamedQueriesUllFlowCustom.class.php.
 * 
 * Normally a custom ullNamedQueriesUllFlowCustom.class.php
 * is provided in apps/frontend/modules/ullFlow/lib
 */

class ullNamedQueriesUllFlowCustom extends ullNamedQueries
{
  
  public function configure()
  {
  }
  
}