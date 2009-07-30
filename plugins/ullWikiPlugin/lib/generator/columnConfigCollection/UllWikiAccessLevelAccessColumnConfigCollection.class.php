<?php

class UllWikiAccessLevelAccessColumnConfigCollection extends UllEntityColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['model_id']->setLabel(__('Access level', null, 'ullWikiMessages'));
  }
}
