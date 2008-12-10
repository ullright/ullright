<?php

class ullFlowFullPageWidgetWikiLink extends ullFlowFullPageWidget
{
  
  public function getInternalUri()
  {
    return 'ullFlow/wikiLink?doc=' . $this->doc->id . '&column=' . $this->column;
  }
  
}
