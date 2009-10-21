<?php

class BaseUllSidebarComponents extends sfComponents
{
  public function executeSidebar(sfRequest $request) {
    $this->flowApps = Doctrine::getTable('UllFlowApp')->findAll();

    if ($request->getParameter('module') == 'ullPhone')
    {
      $this->locations = Doctrine::getTable('UllLocation')->findAllOrderedByNameAsArray();
      $this->quickSearchForm = new ullPhoneQuickSearchForm();
    }
  }
}
