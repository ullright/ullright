<?php if ($allow_delete): ?>
  <span class="ull_cms_content_heading_delete_link inline_editing">
    <?php
      echo link_to(
        ull_image_tag('delete'), 
        $module . '/delete?id=' . $doc->id,
        array('confirm' => __('Are you sure?', null, 'common'))
      )
    ?>   
  </span>           
<?php endif ?>