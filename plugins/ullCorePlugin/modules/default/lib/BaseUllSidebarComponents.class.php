<?php

class BaseUllSidebarComponents extends sfComponents
{
  public function executeSidebar() {
    $this->flowApps = Doctrine::getTable('UllFlowApp')->findAll(); 
  }
}
