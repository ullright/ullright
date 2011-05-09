<?php slot('sidebar') ?>
  <?php echo $sidebar_menu ?>
<?php end_slot() ?>

<div class="ull_cms_content ull_cms_page_<?php echo $doc->slug ?>">
  <h1 class="ull_cms_content_heading">
    <?php include_component('ullCms', 'editLink', array('doc' => $doc)) ?>
    <span class="ull_cms_content_heading_text"><?php echo $title ?></span>
  </h1>
  
  <?php echo auto_link_text($body, $link = 'all', array(
        'class'  => 'link_external',
        'target' => '_blank',
  )); ?>
  
  <?php include_slot('ull_cms_additional_body') ?>
</div> 