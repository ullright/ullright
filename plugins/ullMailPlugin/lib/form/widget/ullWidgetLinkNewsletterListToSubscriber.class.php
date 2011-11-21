<?php

class ullWidgetLinkNewsletterListToSubscriber extends ullWidget
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $id = $value['id'];
    
    $return = '';
    
    $return .= link_to(
      __('Subscribers', null, 'ullMailMessages'),
      'ullTableTool/list?table=UllNewsletterMailingListSubscriber&filter[ull_newsletter_mailing_list_id]=' . $id
    );
    
    return $return;
  }
  
}