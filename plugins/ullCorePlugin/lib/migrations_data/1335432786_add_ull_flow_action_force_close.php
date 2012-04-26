<?php

class AddUllFlowActionForceClose extends Doctrine_Migration_Base
{
  public function up()
  {
    $action = new UllFlowAction;
    $action->namespace = 'ullFlow';
    $action->is_enable_validation = false;
    $action->is_notify_next = false;
    $action->is_in_resultlist = false;
    $action->slug = 'force_close';
    $action->Translation['en']->label = 'Forced close';
    $action->Translation['de']->label = 'Zwangs-abgeschlossen';
    $action->save();    
  }

  public function down()
  {
  }
}
