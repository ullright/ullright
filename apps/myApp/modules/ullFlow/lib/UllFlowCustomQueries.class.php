<?php
class UllFlowCustomQueries {

  public function listQueries() {
    echo '<h2>';
    echo __('Custom queries');
    echo ':</h2>';
  
    echo '<ul>';
    
    echo '<li>' . link_to(__('Tickets assigned to group IT-Helpdesk'), 'ullFlow/list?app=helpdesk_tool&group=2') . '</li>';
      
    echo '</ul>';
  
  }
}
?>