<?php

class ullFlowFullPageWidgetUpload extends ullFlowFullPageWidget
{
  
  public function getInternalUri()
  {
    return 'ullFlow/upload?doc=' . $this->doc->id . '&column=' . $this->column;
  }
  
}
