<?php if ($allow_edit): ?>
  <span class="ull_cms_content_heading_edit_link">
    <?php
      echo ull_link_to(ull_image_tag('edit'),
        array('module' => 'ullCourse', 'action' => 'edit', 'id' => $doc->id));
    ?>   
  </span>           
<?php endif ?>