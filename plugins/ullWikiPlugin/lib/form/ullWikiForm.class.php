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

    $object->setEditCounter($object->getEditCounter() + 1);
    $object->setTags($this->getValue('duplicate_tags_for_search'));
    
    return $object;
  }  
  
}