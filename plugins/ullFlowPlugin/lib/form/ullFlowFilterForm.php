<?php

class ullFlowFilterForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'search'  => new sfWidgetFormInput(array(), array(
        'size'      => '15',
        'onchange'  => 'submit()',
        'title'     => __('Search', null, 'common')
        )
      ),
      'status' => new ullWidgetUllFlowAction(array(
        ), array('onchange'  => 'submit()')
      )
    ));

    $this->widgetSchema->setLabels(array(
      'search'  => __('Search', null, 'common'),
      'status'  => __('Status')
    ));
    
    $this->setValidators(array(
      'search'  => new sfValidatorPass(),
      'status'  => new sfValidatorPass(),
    ));
    
    $this->getWidgetSchema()->setNameFormat('filter[%s]');
    
    $this->getWidgetSchema()->setFormFormatterName('ullUnorderedList');
  }
}
