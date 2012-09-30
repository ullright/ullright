<?php

/**
 * ullWidget actions.
 * 
 * For widget ajax actions
 *
 * @package    ullright
 * @subpackage ullCore
 * @author     Klemens Ullmann-Marx <klemens.ullmann.marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class BaseUllWidgetActions extends ullsfActions
{

  /**
   * Ajax request for upload in ullWidgetGallery2
   * 
   * @param $request
   */
  public function executeGalleryUpload(sfRequest $request)
  {
    sfConfig::set('sf_web_debug', false);
    
//     var_dump($request->getParameterHolder()->getAll());die;

    $config = json_decode($request->getParameter('s_config'), true);
    
    $form = new ullWidgetGalleryForm();
    $form->getValidatorSchema()->offsetSet('file',  
      new ullValidatorFile(array(
        'required'   => true,
        'path'       => sfConfig::get('sf_upload_dir') . $config['path'],
        'mime_types' => $config['mime_types'],
        'imageWidth' => $config['image_width'],
        'imageHeight' => $config['image_height'],
      ))
    );
    
    $form->bind(
        array('file' => $request->getParameter('name')), 
        $this->getRequest()->getFiles()
    );
    
    if ($form->isValid()) 
    {
      $file = $form->getValue('file');
      $file->save();
      
      $this->createGalleryThumbnail($file, $config);
      
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
   * Create gallery thumbnail
   * 
   * @param  $file
   * @param  $config
   */
  protected function createGalleryThumbnail($file, $config)
  {
    if (!$config['create_thumbnails'])
    {
      return;
    }
    
    $fileName = $file->getSavedName();
    $mimeType = $file->getType();
    
    $width = $config['thumbnail_width'];
    $height = $config['thumbnail_height'];
    
    // duplicate file because it is overwritten by validatedFile
    $destination = ullCoreTools::calculateThumbnailPath($fileName);
    copy($file, $destination);      
    
    /* Images */
    if (in_array($mimeType, ullValidatorFile::getWebImageMimeTypes()))
    {
      $validatedFile = new ullValidatedFile(
        null, null, $destination, null, $config['path']
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
  
  
  /**
   * Ajax request to render gallery preview for ullWidgetGalleryWrite
   * 
   * 
   */
  public function executeGalleryPreview(sfRequest $request)
  {
    $images = $request->getParameter('images');

    return $this->renderText(ullWidgetGalleryWrite::renderPreview($images));
  }  
  
  
  /**
   * Ajax request to delete an gallery image
   * 
   * @param $request
   */
  public function executeGalleryDelete(sfRequest $request)
  {
    $image = $request->getParameter('s_image');
    
    $path = sfConfig::get('sf_web_dir') . $image;
    
    unlink($path);
    
    unlink(ullCoreTools::calculateThumbnailPath($path));
    
    return $this->renderText('');
  }  
  
  
  /**
   * ullWidgetContentElement action to edit an element
   * 
   * @param sfRequest $request
   */
  public function executeContentElement(sfRequest $request)
  {
//     var_dump($request->getParameterHolder()->getAll());die;
    
    $elementTypes  = json_decode($request->getParameter('element_types'), true);
    $elementType   = $request->getParameter('element_type');
    $elementId     = $request->getParameter('element_id');
    $fieldId       = $request->getParameter('field_id');
    $values        = $request->getParameter($elementType . '_' . $elementId . '_fields');
    
    
    $elementData = array(
      'type'    => $elementType,
      'id'      => $elementId,    
    );    
       
    $generator = new ullContentElementGenerator($elementType, $elementId);
    $generator->buildForm(new UllContentElement());
    
    $form = $generator->getForm();
    $form->bind($values);
    
    $return = array();
    
    if ($form->isValid())
    {
      $elementData['values'] = $form->getValues();
      
      $return['markup'] = $this->getPartial('ullWidget/ullContentElement', array(
        'element_data'    => $elementData,
        'element_types'   => $elementTypes,          
        'field_id'        => $fieldId,
      ));      
      
      $return['status'] = 'valid';
      
      return $this->renderText(json_encode($return));
      
    }
    
    // Invalid: return form only
    $return['markup'] = $this->getPartial('ullWidget/ullContentElementForm', array(
      'element_data'    => $elementData,
      'element_types'   => $elementTypes,
      'field_id'        => $fieldId,
      'generator'       => $generator,
    ));
    
    $return['status'] = 'invalid';      
    
    return $this->renderText(json_encode($return));

  }  

  /**
   * ullWidgetContentElement action to add an element
   * 
   * @param sfRequest $request
   */  
  public function executeContentElementAdd(sfRequest $request)
  {
    $elementTypes  = json_decode($request->getParameter('element_types'), true);
    $elementType   = $request->getParameter('element_type');
    $fieldId       = $request->getParameter('field_id');
    
    $elementId  = uniqid();
    
    $elementData = array(
      'type'    => $elementType,
      'id'      => $elementId,
      'values'  => array(),    
    );     
    
    $return = array();
    
    $return['element_id'] = $elementId;
    
    $return['markup'] = $this->getPartial('ullWidget/ullContentElement', array(
      'element_data'    => $elementData,
      'element_types'   => $elementTypes,
      'field_id'        => $fieldId,
      'do_render_html'  => false,
    ));    

    return $this->renderText(json_encode($return));

  }
    
  
}
