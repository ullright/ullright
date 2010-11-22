<?php

/**
 * This class represents the comment column of an UllProjectReporting.
 * It uses the ullWidgetUllProjectReportingCommentRead class to render a
 * HTML link to the linked record, if one is set in the project reporting
 * record. 
 *
 * Needs identifier injection enabled!
 *
 */
class ullMetaWidgetUllProjectReportingComment extends ullMetaWidgetString
{
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetUllProjectReportingCommentRead(
      $this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }
}