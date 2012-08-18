<?php slot('sidebar') ?>
  <?php echo $sidebar_menu ?>
<?php end_slot() ?>

<div class="ull_cms_content ull_cms_page_<?php echo str_replace('-', '_', $doc->slug) ?> 
  ull_cms_content_type_<?php echo str_replace('-', '_', $doc->UllCmsContentType->slug) ?>">

  <h1 class="ull_cms_content_heading">
    <?php include_component('ullCms', 'editLink', array('doc' => $doc)) ?>
    <span class="ull_cms_content_heading_text"><?php echo $title ?></span>
  </h1>
  
  <div id="ull_cms_show_body">
    <?php echo auto_link_text($body, $link = 'all', array(
          'class'  => 'link_external',
          'target' => '_blank',
    )); ?>
  </div>
  
  <?php include_slot('ull_cms_additional_body') ?>
</div> 