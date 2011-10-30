<?php if ($allow_edit): ?>
  <span class="ull_cms_content_heading_edit_link">
    <?php
      echo link_to(ull_image_tag('edit'), $module . '/edit?id=' . $doc->id);
    ?>   
  </span>           
<?php endif ?>