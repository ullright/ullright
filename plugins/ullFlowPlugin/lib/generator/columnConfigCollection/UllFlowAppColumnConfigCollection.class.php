<?php

class UllFlowAppColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   *
   */
  protected function applyCustomSettings()
  {
    $this['doc_label']->setLabel(__('Label of a document', null, 'ullFlowMessages'));
  }
}