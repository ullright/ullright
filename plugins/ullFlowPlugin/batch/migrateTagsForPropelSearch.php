<?php

define('SF_ROOT_DIR',    realpath(dirname(__FILE__).'/../../..'));
define('SF_APP',         'frontend');
define('SF_ENVIRONMENT', 'dev');
define('SF_DEBUG',       true);

//echo SF_ROOT_DIR;
//exit();

require_once(SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
sfContext::getInstance(); 

// get database connection for direct mysql statements
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();
$connection = Propel::getConnection();

// get all ullFlow docs
$ull_flow_docs = UllFlowDocPeer::doSelect($c = new Criteria);

foreach ($ull_flow_docs as $ull_flow_doc) 
{
  
  $tags = implode(', ', $ull_flow_doc->getTags());

  // some how preserving the updated_at values doesn't work, therefore we use 
  //   raw mysql
  /*
  $ull_wiki->setDuplicateTagsForPropelSearch($tags);
  
  // preserve edit-values
  $ull_wiki->setUpdatedAt($ull_wiki->getUpdatedAt());
  $ull_wiki->save();
  */
  
  $id = $ull_flow_doc->getId();
  
  $query = "UPDATE ull_flow_doc SET duplicate_tags_for_propel_search = '$tags' WHERE id = $id";
  $result = mysql_query($query);
  if (!$result) {
     echo "error: ", mysql_error();
  }
    
}