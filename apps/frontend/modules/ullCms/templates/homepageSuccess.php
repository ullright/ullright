<?php
/**
 * Example custom ullCms template for a single CMS page with slug "homepage"
 * 
 * Custom templates have to be put in /apps/frontend/modules/ullCms/templates/
 * 
 * It includes the generic ullCms show template and adds a news section
 */
?>

<?php require_once(sfConfig::get('sf_plugins_dir') . '/ullCmsPlugin/modules/ullCms/templates/showSuccess.php') ?> 

<h1 id="news_headline">News</h1>

<div id="news">
  <?php include_component('ullNews', 'newsList') ?>
</div>