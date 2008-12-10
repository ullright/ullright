<?php

class BaseullWikiComponents extends sfComponents
{
  public function executeUllWikiHeader() 
  {
    $show_link = array('module' => 'ullWiki', 'action' => 'show', 'docid' => $this->doc->id);
    $this->edit_link = array('module' => 'ullWiki', 'action' => 'edit', 'docid' => $this->doc->id);

    if ($this->getActionName() == 'show') 
    {

      // show edit link only with sufficent rights
      if (UllUserTable::hasGroup('MasterAdmins'))
      {
        $this->subject_link = $this->edit_link;
      } 
      else 
      {
        $this->subject_link = $show_link;
      }

    }
    else 
    {
//      $this->subject_link = 'wiki/show?id='.$this->wiki->getID().'&cursor='.$this->cursor;          
      $this->subject_link = $show_link;
    }
  }

  public function executeUllWikiHeaderShow() 
  {
    $this->executeUllWikiHeader();
  }

  public function executeUllWikiHeadFootActionIcons() 
  {
    $this->access = false;
    if (UllUserTable::hasGroup('MasterAdmins')) 
    {
      $this->access = true;
    }
  }

  public function executeHeaderSearch() 
  {
    $this->form = new ullWikiFilterForm;
  }
}