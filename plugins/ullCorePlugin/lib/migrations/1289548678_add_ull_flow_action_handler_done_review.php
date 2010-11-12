<?php

class AddUllFlowActionHandlerDoneReview extends Doctrine_Migration_Base
{
  public function up()
  {
  }
  
  public function postUp()
  {
    $action = new UllFlowAction;
    $action->namespace = 'ullFlow';
    $action->is_enable_validation = true;
    $action->is_notify_next = true;
    $action->is_in_resultlist = true;
    $action->slug = 'done_review';
    $action->Translation['en']->label = 'Assigned for Review';
    $action->Translation['de']->label = 'Zugewiesen zur Abnahme';
    $action->save();
  }

  public function down()
  {
  }
}
