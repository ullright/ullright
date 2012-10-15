<?php

class BaseUllNewsletterLayoutColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['html_head']
      ->setLabel(__('HTML head', null, 'ullMailMessages'))
      ->setWidgetOption('rows', 20)
      ->setWidgetOption('cols', 80)
      ->setDefault("<style type=\"text/css\">
          
  body {
    margin: 5;
  }

</style>          
    ");
    ;    
    $this['html_body']
      ->setLabel(__('HTML body', null, 'ullMailMessages'))
      ->setHelp(__('The following placeholders are available: 
[ONLINE_LINK] - Inserts the link to online version ("Read the newsletter online"); 
[CONTENT] - Inserts the actual email content;
[UNSUBSCRIBE] - Prints the unsubscribe links;
[TRACKING] - Insert the code for tracking ("Who read the email")', null, 'ullMailMessages')) 
      ->setMetaWidgetClassName('ullMetaWidgetFCKEditor')
    ;

    $this['is_default']
      ->setLabel(__('Is default layout', null, 'ullMailMessages'))
    ;
  }
}