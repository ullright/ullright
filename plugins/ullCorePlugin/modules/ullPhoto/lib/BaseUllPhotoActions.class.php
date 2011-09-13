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
    $this->checkPermission('ull_photo');
    
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
        
        //delete files which do not have allowed extensions
        $allowedFileExtensions = array('jpg', 'jpeg', 'png');
        $cleared = ullCoreTools::walkDirectory($path, 'ullCoreTools::deleteFileIfNotExtension', $allowedFileExtensions);
        
        //delete files which are not valid image files or not of allowed type
        $allowedImageTypes = array('JPEG', 'PNG'); 
        $cleared += ullCoreTools::walkDirectory($path, 'ullCoreTools::deleteFileIfNotFromType', $allowedImageTypes);
        
        if (count($cleared) > 0)
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
    $this->checkPermission('ull_photo');
    
    $this->getUserFromRequest();
    
    $this->buildFormUri();

    $path = $this->getWorkingDirectory();
    $webPath = $this->getWebWorkingDirectory();
    
    $this->cropAspectRatio = sfConfig::get('app_ull_photo_crop_aspect_ratio', '1:1');
    if (!preg_match('/^(\d+):(\d+)$/',  $this->cropAspectRatio))
    {
      throw new RuntimeException('crop_aspect_ratio in app.yml must be of format \'x:y\', e.g. \'3:4\'');
    }
    
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
      chmod($path . '/' . $editedFile, 0666);
    }
    
    $this->photo = $webPath . '/' . $editedFile;
    
    $this->form = new ullPhotoEditForm;
    $this->form->setDefault('photo', $editedFile);
    $this->form->setDefault('ull_user_id', $this->user->id);

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
        
       /* elseif ($actionSlug == 'crop')
        {
          $coordinates = $this->getCoordinatesFromForm($values);
          
          $img = new sfImage($path . '/' . $values['photo'],'image/png' );
          $adjustedValues = $this->adjustCoordinates(
            $coordinates, $img->getHeight(), $this->display_height);
            
          $img->crop($adjustedValues['x1'], $adjustedValues['y1'], $adjustedValues['width'], $adjustedValues['height']);
          
          $this->resizePhoto($img);
          $img->save();
        }*/
        
        elseif ($actionSlug == 'save')
        {
          $coordinates = $this->getCoordinatesFromForm($values);
          
          $img = new sfImage($path . '/' . $values['photo'],'image/png' );
          $adjustedValues = $this->adjustCoordinates(
            $coordinates, $img->getHeight(), $this->display_height);
            
          $img->crop($adjustedValues['x1'], $adjustedValues['y1'], $adjustedValues['width'], $adjustedValues['height']);
          
         // $this->resizePhoto($img);
         // $img->save();
          
          //$img = new sfImage($path . '/' . $values['photo'], 'image/jpg' );
          $this->resizePhoto($img);
          
          $fullPath = 
            sfConfig::get('sf_web_dir') . 
            sfConfig::get('app_ull_photo_upload_path', '/uploads/userPhotos') .
            '/' .
            $this->user->username .
            '.jpg'
          ;            
          $img->saveAs($fullPath);
          chmod($fullPath, 0666);
          
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
            
            if (strstr($referer, 'ullUser/edit'))
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
      . UllUserTable::findLoggedInUsername()
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
   * Ajax request for image upload
   * 
   * Counterpart for plupload
   * 
   * Used e.g. by ullWidgetGalleryWrite
   * @param $request
   */
  public function executeImageUpload(sfRequest $request)
  {
    sfConfig::set('sf_web_debug', false);
    
    $model = $request->getParameter('model');
    $column = $request->getParameter('column');
    
    $columnConfig = $this->buildColumnConfig($model, $column);
    
    // let columnConfig be modified by metaWidget (defaults etc)
    $metaWidgetClassName = $columnConfig->getMetaWidgetClassName();
    $metaWidget = new $metaWidgetClassName($columnConfig, new sfForm);
    
//    throw new Exception($columnConfig->getOption('mime_types'));
    
    $form = new ullWidgetGalleryForm();
    $form->getValidatorSchema()->offsetSet('file',  
      new ullValidatorFile(array(
        'required'   => true,
        'path'       => $columnConfig->getOption('path'),
        'mime_types' => $columnConfig->getOption('mime_types'),
        'imageWidth' => $columnConfig->getOption('image_width'),
        'imageHeight' => $columnConfig->getOption('image_height'),
      ))
    );
    
    $form->bind(array('file' => $request->getParameter('name')), $this->getRequest()->getFiles());
    
    if ($form->isValid()) 
    {
      $file = $form->getValue('file');
      $file->save();
      
      $this->createThumbnail($file, $columnConfig);
      
      // create relative path
      $path = str_replace(sfConfig::get('sf_web_dir'), '', $file->getSavedName());
      
      return $this->renderText($path);
    }
    else
    {
      throw new RuntimeException('Validation error: ' . ullCoreTools::debugFormError($form, true));
    }
  }
  
  /**
   * Build column config for upload
   * 
   * @param $model
   * @param $column
   */
  protected function buildColumnConfig($model, $column)
  {
    // Try to get a custom columns config via event dispatching
    // Used e.g. for ullFlow
    $eventResult = sfContext::getInstance()->getEventDispatcher()->filter(
        new sfEvent($this, 'ull_photo.get_columns_config'), 
        array(
          'model'   => $model,
          'column'  => $column,
        )
    )->getReturnValue();
    
    if (isset($eventResult['columnsConfig']) && $eventResult['columnsConfig'] instanceof ullColumnConfigCollection)
    {
      $columnsConfig = $eventResult['columnsConfig'];
    }
    else
    {
      $columnsConfig = ullColumnConfigCollection::buildFor($model);
    }
    
    return $columnsConfig[$column];    
  }
  
  
  /**
   * Create thumbnail
   * 
   * @param unknown_type $file
   * @param ullColumnConfiguration $columnConfig
   */
  protected function createThumbnail($file, ullColumnConfiguration $columnConfig)
  {
    if ($columnConfig->getOption('create_thumbnails'))
    {
      $fileName = $file->getSavedName();
      $mimeType = $file->getType();
      
      $width = $columnConfig->getOption('thumbnail_width');
      $height = $columnConfig->getOption('thumbnail_height');
      
      // duplicate file because it is overwritten by validatedFile
      $destination = ullCoreTools::calculateThumbnailPath($fileName);
      copy($file, $destination);      
      
      /* Images */
      if (in_array($mimeType, ullValidatorFile::getWebImageMimeTypes()))
      {
        $validatedFile = new ullValidatedFile(
          null, null, $destination, null, $columnConfig->getOption('path')
        );
        $validatedFile->setImageWidth($width); 
        $validatedFile->setImageHeight($height);
        
        $validatedFile->save($destination);
      }
      
      /* office */
      elseif (in_array($mimeType, ullValidatorFile::getOfficeMimeTypes()))
      {
        $cmd = 'gsf-office-thumbnailer -i ' . $fileName . ' -o ' . $destination . ' -s ' . $width;
        
        shell_exec($cmd); 
      }      
      
      /* pfd */
      elseif (in_array($mimeType, ullValidatorFile::getTextMimeTypes()))
      {
        $cmd = 'convert -resize ' . $width . 'x' . $height . ' ' . $fileName . ' ' . $destination;
        
        shell_exec($cmd); 
      }
      else
      {
        copy(sfConfig::get('sf_web_dir') . '/ullCoreThemeNGPlugin/images/ull_admin_32x32.png', $destination);
      }
    }
  }
  
  
  /**
   * Ajax request to render gallery preview for ullWidgetGalleryWrite
   * 
   * @param $request
   */
  public function executeRenderGalleryPreview(sfRequest $request)
  {
    $images = $request->getParameter('images');

    return $this->renderText(ullWidgetGalleryWrite::renderPreview($images));
  }    
  
  /**
   * Delete an image - path encrypted by ullCrypt
   */
  public function executeImageDelete(sfRequest $request)
  {
    $image = $request->getParameter('s_image');
    
    $path = sfConfig::get('sf_web_dir') . $image;
    
    unlink($path);
    
    unlink(ullCoreTools::calculateThumbnailPath($path));
    
    return $this->renderText('');
  }
  
  
  /**
   * Breadcrumbs for index action
   * 
   * @return unknown_type
   */
  protected function breadcrumbForIndex()
  {
    $breadcrumbTree = new ullAdminBreadcrumbTree;
    $breadcrumbTree->add(__('Upload user photos', null, 'ullCoreMessages'), 'ullPhoto/index');
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }
  
  
  /**
   * Breadcrumbs for edit action
   * @return unknown_type
   */
  protected function breadcrumbForEdit()
  {
    $breadcrumbTree = new ullAdminBreadcrumbTree;
    $breadcrumbTree->add(__('Upload user photos', null, 'ullCoreMessages'), 'ullPhoto/index');
    $breadcrumbTree->add(__('Edit', null, 'common'));
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }  
}
