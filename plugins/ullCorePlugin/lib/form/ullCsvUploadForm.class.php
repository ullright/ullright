<?php

/**
 * This class represents a simple form for the import of csv files
 */
class ullCsvUploadForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'file'   => new sfWidgetFormInputFile(array(), array()),
    ));
    
    $this->getWidgetSchema()->setLabels(
      array('file' => __('CSV file', null, 'ullCoreMessages'))
    );
    
    $this->getWidgetSchema()->setHelps(
      array('file' => __('A CSV file separated by semicolons or tabs containing a column header row at the top.', null, 'ullCoreMessages'))
    );
    

    $this->setValidators(array(
      'file' => new sfValidatorFile(
        array('mime_types' => array(
          'text/plain',
          'text/csv',
          'text/comma-separated-values', 
          'application/csv',
        ))
      ),
    ));
    
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
    $this->getWidgetSchema()->setFormFormatterName('ullTable');
    
    
  }
}