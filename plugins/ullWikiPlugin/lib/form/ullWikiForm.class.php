<?php
/**
 * sfForm for ullWiki
 *
 */
class ullWikiForm extends ullGeneratorForm
{

  public function updateObject($values = null)
  {
    $object = parent::updateObject();

    $cachePath = sfConfig::get('sf_cache_dir') . '/htmlpurifier';
    if (!file_exists($cachePath))
    {
    	mkdir($cachePath);
    }
    
    $config = HTMLPurifier_Config::createDefault();
    $config->set('Cache', 'SerializerPath', $cachePath);
    $config->set('Attr', 'EnableID', true);
    $purifier = new HTMLPurifier($config);
 
    $object->body = htmlentities($purifier->purify($object->body), ENT_QUOTES, 'UTF-8');   
    $object->setEditCounter($object->getEditCounter() + 1);
    $object->setTags($this->getValue('duplicate_tags_for_search'));
    
    return $object;
  }  
  
}