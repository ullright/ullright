<?php
/**
 * sfForm for ullWiki
 *
 * ullWiki*Doc*Form - "doc" to distinguish it form the generated UllWikiForm
 *
 */
class ullWikiDocForm extends ullGeneratorForm
{

  public function updateObject($values = null)
  {
    $object = parent::updateObject();

    $object->body = htmlentities(ullHTMLPurifier::purifyForWiki($object->body), ENT_QUOTES, 'UTF-8');   
    $object->setEditCounter($object->getEditCounter() + 1);
    $object->setTags($this->getValue('duplicate_tags_for_search'));
    
    return $object;
  }  
  
}