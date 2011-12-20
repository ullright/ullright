<?php

/**
 * myModule actions.
 *
 * @package    myProject
 * @subpackage myModule
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class myModuleActions extends ullsfActions
{
  
  /**
   * Executes index action
   *
   */
  public function executeIndex($request)
  {
    $this->redirect('ullCms/show?slug=homepage');
  }
  
  
  /**
   * Toogle between webapp and website example layouts
   * 
   * @param sfRequest $request
   */
  public function executeToggleLayout($request)
  {
    $layout = $this->getUser()->getAttribute('layout');
    
    if ($layout == 'layout_webapp')
    {
      $layout = 'layout_website';
    }
    else
    {
      $layout = 'layout_webapp';
    }
    
    $this->getUser()->setAttribute('layout', $layout);
    
    $this->redirect('@homepage');
  }
  
  
  
  public function executeCsvUploadTest(sfRequest $request)
  {
    $this->checkPermission('csv_upload_test');
    
    $form = new ullCsvUploadForm();

    $generatorErrors = array();
    $mappingErrors = array();
    $numberRowsImported = 0;
    
    
    if ($request->isMethod('post'))
    {
//      var_dump($request->getParameterHolder()->getAll());
//      var_dump($this->getRequest()->getFiles());
      
      $form->bind(
        $request->getParameter('fields'), 
        $this->getRequest()->getFiles('fields')
      );
      
      if ($form->isValid())
      {
        $file = $form->getValue('file');
        $path = $file->getTempName();
        
        $importer = new ullCsvImporter($path);
        $rows = $importer->toArray();
        
        unlink($path);
        
        $mapping = array(
          'Vorname'       => 'first_name',
          'Nachname'      => 'last_name',
          'E-Mail'        => 'email',
          'Verteiler'     => 'UllNewsletterMailingLists'
        );
        
        $mapper = new ullMapper($rows, $mapping);
        $rows = $mapper->doMapping();
        $mappingErrors = $mapper->getErrors();
        
        $generator = new ullUserGenerator('w');
        $generator->getColumnsConfig()->disableAllExcept($mapping);
        $generator->getColumnsConfig()->offsetGet('email')->setIsRequired(true);        
        
        foreach ($rows as $rowNumber => $row)
        {
          $currentGenerator = clone $generator;
          
          $email = $row['email'];
          $user = Doctrine::getTable('UllUser')->findOneByEmail($email);
          
          if (!$user)
          {
            $user = new UllUser;
          }          
          
          $mailingListName = $row['UllNewsletterMailingLists'];
          
          
          if ($mailingListName) 
          {
            $mailingList = Doctrine::getTable('UllNewsletterMailingList')
              ->findOneByName($mailingListName);
              
            if ($mailingList)
            {
              $row['UllNewsletterMailingLists']= array($mailingList->id);
            }
            else
            {
              $row['UllNewsletterMailingLists'] = $mailingListName;
            }
          }          
          
          $currentGenerator->buildForm($user);

          if ($currentGenerator->getForm()->bindAndSave($row))
          {
            unset($currentGenerator);
            $numberRowsImported++;
          }
          else 
          {
            $generatorErrors[$rowNumber] = $currentGenerator;
          }
          
        } // foreach row
      } // end of if uploaded csv-file is valid
    } // end of if post
    
        
    $this->form = $form;
    $this->generatorErrors = $generatorErrors;
    $this->mappingErrors = $mappingErrors;
    $this->numberRowsImported = $numberRowsImported;
  }
  
  
}
