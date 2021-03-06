<?php if ($is_active): ?>
  <div class="ull_cms_content_area">
    <h1 class="ull_cms_content_area_heading">
      <?php  if ($allow_edit): ?>
        <span class="ull_cms_content_heading_edit_link">
          <?php
            echo ull_link_to(ull_image_tag('edit'),
              array('module' => 'ullCms', 'action' => 'edit', 'id' => $doc->id));
          ?>   
        </span>           
      <?php endif ?>
      <span class="ull_cms_content_area_heading_text"><?php echo $title ?></span>
    </h1>
    
    <?php echo auto_link_text($body, $link = 'all', array(
          'class'  => 'link_external',
          'target' => '_blank',
    )); ?>
    
    <?php //include_slot('ull_cms_additional_body') ?>
  </div> 
<?php endif ?>