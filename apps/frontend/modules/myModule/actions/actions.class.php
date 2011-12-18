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
        
        foreach($rows as $rowNumber => $row)
        {
          $email = $row['E-Mail'];
          $user = Doctrine::getTable('UllUser')->findOneByEmail($email);
          
          if (!$user)
          {
            $user = new UllUser;
          }          
          
          $fields = array();
          $fields['first_name'] = $row['Vorname'];
          $fields['last_name']  = $row['Nachname'];
          $fields['email']      = $email;          
          
          $generator = new ullUserGenerator('w');
          $generator->getColumnsConfig()->disableAllExcept(array(
            'first_name',
            'last_name',
            'email',
          ));
          $generator->getColumnsConfig()->offsetGet('email')->setIsRequired(true);
          
          
          $generator->buildForm($user);

          if ($generator->getForm()->bindAndSave($fields))
          {
          }
          else 
          {
            $generatorErrors[$rowNumber] = $generator;
          }

          
//          $mailingListName = $row['Verteiler'];
//          
//          if ($mailingListName) 
//          {
//            $mailingList = Doctrine::getTable('UllNewsletterMailingList')
//              ->findOneByName($mailingListName);
//              
//            if (!$mailingList)
//            {
//              $errors[] = 'Unbekannter Verteiler: ' . $mailingListName . "\n"; 
//            }
//            else
//            {
//              $user->UllNewsletterMailingLists[] = $mailingList;
//            }
//          }
//          
//          $user->save();
        }
        
      }
      
    }
    
        
    $this->form = $form;
    $this->generatorErrors = $generatorErrors;
  }
  
  
}
