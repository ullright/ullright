<?php

class BaseUllSidebarComponents extends sfComponents
{
  public function executeSidebar(sfRequest $request) {
    $this->flow_apps = UllFlowAppTable::findAllOrderByName();

    if ($request->getParameter('module') == 'ullPhone')
    {
      $this->locations = Doctrine::getTable('UllLocation')->findAllOrderedByNameAsArray();
      $this->quickSearchForm = new ullPhoneQuickSearchForm();
    }
  }
}
