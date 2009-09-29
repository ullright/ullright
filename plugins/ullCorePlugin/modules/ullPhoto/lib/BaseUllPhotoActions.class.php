<?php

/**
 * ullPhoto actions.
 *
 * @package    ullright
 * @subpackage ullPhoto
 * @author     Klemens Ullmann-Marx <klemens.ullmann.marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class BaseUllPhotoActions extends ullsfActions
{
  
  /**
   * Execute  before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#ullpreExecute()
   */
  public function ullpreExecute()
  {
    $defaultUri = $this->getModuleName() . '/index';
    $this->getUriMemory()->setDefault($defaultUri);  
  }
  
  
  /**
   * Executes index action
   *
   */
  public function executeIndex($request)
  {
    $this->checkAccess('LoggedIn');
    
    $this->getUserFromRequest();
    
    $this->form = new ullPhotoUploadForm;
    
    $this->form_uri = 'ullPhoto/index' . (($this->username) ? '?username=' . $this->username : '');
    
    $this->breadcrumbForIndex();
    
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('fields'), $this->getRequest()->getFiles('fields'));

      if ($this->form->isValid())
      {
        $file = $this->form->getValue('photo');
        
        $path = $this->getWorkingDirectory();
        
        if (!file_exists($path))
        {
          mkdir($path, 0777, true);
        }
        
        if ($file->getType() == 'application/zip')
        {
          $zip = new ZipArchive;
          $resource = $zip->open($file->getTempName());
          if ($resource) 
          {
            $zip->extractTo($path);
            $zip->close();
          }
          unlink($file->getTempName());
        }
        else
        {
          $filename = $file->getOriginalName();
          $fullPath = $path . '/' . $filename;
          $file->save($fullPath);         
        }
        
        $cleared = ullCoreTools::clearDirectoryByFileExtension($path, array('jpg', 'png'));
        
        if ($cleared !== false)
        {
          $this->getUser()->setFlash('cleared', 
            __('Ignored some files with invalid types', null, 'common') . ': ' . implode(',', $cleared));
        }
        
        if (ullCoreTools::isEmptyDir($path))
        {
          $this->getUser()->setFlash('no_more_photos',
            __('There are no photos left to be processed', null, 'ullCoreMessages'));
        }
        else
        {
          $redirect = 'ullPhoto/edit' . (($this->user->username) ? '?username=' . $this->user->username : '');
          $this->redirect($redirect);
        }
      }
    }

   if ($request->isMethod('get'))
   {
     $this->getUriMemory()->setReferer();
   }
  }
  
  
  /**
   * Execute edit action
   * 
   * @param $request
   * @return unknown_type
   */
  public function executeEdit($request)
  {
    $this->checkAccess('LoggedIn');
    $this->getUserFromRequest();
    
    $this->buildFormUri();

    $path = $this->getWorkingDirectory();
    $webPath = $this->getWebWorkingDirectory();
    
    $file = ullCoreTools::getFirstFileInDirectory($path, '/^edited_/');
    
    if (!$file)
    {
      $this->getUser()->setFlash('no_more_photos',
        __('There are no photos left to be processed', null, 'ullCoreMessages'));
      $this->redirect('ullPhoto/index');
    }
    
    $editedFile = 'edited_' . $file;
    if (!is_file($path . '/' . $editedFile))
    {
      copy($path . '/' . $file, $path . '/' . $editedFile);
    }
    
    $this->photo = $webPath . '/' . $editedFile;
    
    $this->form = new ullPhotoEditForm;
    $this->form->setDefault('photo', $editedFile);
    $this->form->setDefault('ull_user_id', $this->user->id);

    // override plugin's default
    sfConfig::set('app_sfImageTransformPlugin_default_adapter', 'ImageMagick');
    
    $img = new sfImage($path . '/' . $editedFile, 'image/png' );
    $maxHeight = sfConfig::get('app_ull_photo_display_height', 400);
    $this->display_height = ($img->getHeight() > $maxHeight) ? $maxHeight : $img->getHeight();  
    
    $this->breadcrumbForEdit();
    
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('fields'));

      if ($this->form->isValid())
      {
        $values = $this->form->getValues();
        
        $this->user = Doctrine::getTable('UllUser')->findOneById($values['ull_user_id']);
        $this->buildFormUri();
        
        $this->form->setDefault('ull_user_id', $values['ull_user_id']);
        
        $actionSlug = $request->getParameter('action_slug');
        
        if (in_array($actionSlug, array('rotate_counterclockwise', 'flip', 'rotate_clockwise')))
        {
          $img = new sfImage($path . '/' . $values['photo'],'image/png' );
          switch ($actionSlug)
          {
            case 'rotate_counterclockwise':
              $img->rotate(270);
              break;
            case 'flip':
              $img->rotate(180);
              break;
            case 'rotate_clockwise':
              $img->rotate(90);
              break;    
          }
          $img->save();
        }
        
        elseif ($actionSlug == 'crop')
        {
          $coordinates = $this->getCoordinatesFromForm($values);
          
          $img = new sfImage($path . '/' . $values['photo'],'image/png' );
          $adjustedValues = $this->adjustCoordinates(
            $coordinates, $img->getHeight(), $this->display_height);
            
          $img->crop($adjustedValues['x1'], $adjustedValues['y1'], $adjustedValues['width'], $adjustedValues['height']);
          
          $this->resizePhoto($img);
          $img->save();
        }
        
        elseif ($actionSlug == 'save')
        {
          $img = new sfImage($path . '/' . $values['photo'], 'image/jpg' );
          $this->resizePhoto($img);
          
          $fullPath = 
            sfConfig::get('sf_web_dir') . 
            sfConfig::get('app_ull_photo_upload_path', '/uploads/userPhotos') .
            '/' .
            $this->user->username .
            '.jpg'
          ;            
          $img->saveAs($fullPath);
          
          $this->user->photo = $this->user->username . '.jpg';
          $this->user->save();
        }
        
        elseif ($actionSlug == 'revert')
        {
          copy($path . '/' . $file, $path . '/' . $editedFile);
        }
        
        if (in_array($actionSlug, array('save', 'skip')))
        {
          unlink($path . '/' . $file);
          unlink($path . '/' . $editedFile);
          
          if (ullCoreTools::isEmptyDir($path))
          {
            $this->getUser()->setFlash('no_more_photos',
              __('There are no photos left to be processed', null, 'ullCoreMessages'));
              
            $referer = $this->getUriMemory()->get('index');
            if (strstr($referer, 'ullTableTool/edit/table/UllUser'))
            {
              $this->redirect($referer);
            }              
            $this->redirect('ullPhoto/index');
          }
          // When there are multiple photos to process
          //  preselecting a given user doesn't make sense
          else
          {
            $this->user = new UllUser;
            $this->buildFormUri();
          }
        }        
        
        $this->redirect($this->form_uri);
      }
    }
  }
  
  
  /**
   * Get the user by username from the request
   * @return unknown_type
   */
  protected function getUserFromRequest()
  {
    $username = $this->getRequestParameter('username');
    
    if ($username)
    {
      $this->user = Doctrine::getTable('UllUser')->findOneByUsername($username);     
    }
    else
    {
      $this->user = new UllUser;
    }
  }
  
  
  
  
  /**
   * Get the working directory for the currently logged in user
   * @return unknown_type
   */
  protected function getWorkingDirectory()
  {        
    $path = sfConfig::get('sf_web_dir') . 
      sfConfig::get('app_ull_photo_upload_path', '/uploads/userPhotos') 
      . '/temp/' 
      . $this->getLoggedInUsername()
    ;
    
    return $path;
  }
  
  /**
   * Get the web-path to the current working directory
   * 
   * @return unknown_type
   */
  protected function getWebWorkingDirectory()
  {
    $path = str_replace(sfConfig::get('sf_web_dir'), '', $this->getWorkingDirectory());
    
    return $path;
  }
  
  
  /**
   * Get crop coordinates from the form
   * 
   * @param $formValues
   * @return array
   */
  protected function getCoordinatesFromForm($formValues)
  {
    $coordinates = array();
    $fields = array('x1', 'y1', 'width', 'height');
    foreach ($fields as $fieldName)
    {
      $coordinates[$fieldName] = $formValues[$fieldName];  
    }
    
    return $coordinates;
  }
  
  
  /**
   * After the upload the photo is displayed with a reduced size.
   * Here we calculate the adjusted crop coordinates
   * 
   * @param array $coordinates
   * @param integer $originalHeight
   * @param integer $displayedHeight
   * @return array
   */
  protected function adjustCoordinates($coordinates, $originalHeight, $displayedHeight)
  {
    $factor = $originalHeight / $displayedHeight;
    foreach ($coordinates as $key => $coordinate)
    {
      $coordinates[$key] = round($coordinate * $factor);
    }

    return $coordinates;
  }
  
  /** 
   * Build form uri
   * 
   * @return string
   */
  protected function buildFormUri()
  {
    $this->form_uri = 'ullPhoto/edit' . (($this->user->username) ? '?username=' . $this->user->username : '');
  }
  
  
  /**
   * Resize the photo
   * 
   * @param sfImage $img
   * @return none
   */
  protected function resizePhoto(sfImage $img)
  {
    $maxWidth = sfConfig::get('app_ull_photo_width', 200);
    $maxHeight = sfConfig::get('app_ull_photo_height', 200);
    
    if ($img->getWidth() > $maxWidth || $img->getHeight() > $maxHeight)
    {
      // always comply to the max size
      if ($img->getWidth() > $img->getHeight())
      {
        $img->resize($maxWidth, 0);
      }
      else
      {
        $img->resize(0, $maxHeight);
      }    
    }
  }
  
  
  /**
   * Breadcrumbs for index action
   * 
   * @return unknown_type
   */
  protected function breadcrumbForIndex()
  {
    $this->breadcrumbTree = new ullAdminBreadcrumbTree;
    $this->breadcrumbTree->add(__('Upload user photos', null, 'ullCoreMessages'), 'ullPhoto/index');
  }
  
  
  /**
   * Breadcrumbs for edit action
   * @return unknown_type
   */
  protected function breadcrumbForEdit()
  {
    $this->breadcrumbTree = new ullAdminBreadcrumbTree;
    $this->breadcrumbTree->add(__('Upload user photos', null, 'ullCoreMessages'), 'ullPhoto/index');
    $this->breadcrumbTree->add(__('Edit', null, 'common'));
  }  
}
