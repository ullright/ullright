<?php
class UllFlowCustomQueries {

  public function getAllQueries() {
    $custqueries = array('Tickets assigned to group IT-Helpdesk' =>
      'ullFlow/list?app=trouble_ticket&group=2');
    
    //$custqueries['custquery2'] = 'custvalue2';
      
    return $custqueries;
  }
}
?>