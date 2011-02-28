<?php foreach ($tree->getSubnodes() as $page_node): ?>
  <div class="sidebar_block">
    <?php include_component('ullCms', 'renderCmsPage', array('slug' => $page_node->getData()->slug)) ?>
  </div>
<?php endforeach ?>