<?php

/**
 * PluginUllWiki form.
 *
 * @package    form
 * @subpackage UllWiki
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class PluginUllWikiForm extends BaseUllWikiForm
{
  
	public function setup()
  {
    parent::setup();
    
    // Remove all widgets we don't want to show
    unset(
      $this['id'],
      $this['namespace'],
      $this['creator_user_id'],
      $this['updator_user_id'],
      $this['read_counter'],
      $this['edit_counter'],
      $this['duplicate_tags_for_search'],
      $this['created_at'],
      $this['updated_at'],
      $this['deleted'],
      $this['version']
    );
    
    $this->widgetSchema['subject']  = new sfWidgetFormInput(array(), array('size' => '50'));
    $this->widgetSchema['body']     = new sfWidgetFormTextareaFCKEditor(array(
        'width'                    => '800',
        'height'                   => '400',
        'CustomConfigurationsPath' => '/ullWikiPlugin/js/FCKeditor_config.js',
        'BasePath'                 => '/ullWikiPlugin/js/fckeditor/',
    ));

    $this->widgetSchema->setLabels(array(
        'subject'                   => __('Subject', null, 'common'),
        'body'                      => __('Text', null, 'common'),
    ));
    
    $this->getWidgetSchema()->setFormFormatterName('ullTable');
  }
  
  public function updateObject()
  {
    $object = parent::updateObject();

    $object->setEditCounter($object->getEditCounter() + 1);
    
    $tags = sfContext::getInstance()->getRequest()->getParameter('tags');
    
    $tagsArray = explode(',', strtolower($tags));
    foreach ($tagsArray as $tag) 
    {
      $object->addTag(trim($tag));
      
    }
    $this->object->duplicate_tags_for_search = strtolower($tags);

    return $object;
  }  
  
}