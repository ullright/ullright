<?php

/**
 * Test browser for ullTime tests
 * 
 * @author ts
 *
 */

class ullTimeTestBrowser extends ullTestBrowser
{
  /**
   * Sets the from_date filter
   * 
   * @param $fromDate Date for from_Date field (e.g. '01/01/2009')
   */
  public function setFromDateTo($fromDate = '01/01/2009')
  {
    $this
      ->setField('filter[from_date]',$fromDate)
      ->click('search_list')
      ->isRedirected()
      ->followRedirect()
      ->with('request')->begin()
        ->isParameter('module', 'ullTime')
        ->isParameter('action', 'reportProject')
      ->end()
    ;
  }
}