<?php
    // TODO: refactor into a component
    $pager_range = $pager->getRange(
        'Sliding',
        array(
            'chunk' => 5
        )
    );
    $pages = $pager_range->rangeAroundPage();
?>    

<?php if ($pager->haveToPaginate()): ?>
  <?php echo '<span class="ull_pager_element color_light_bg">' . ull_link_to('&laquo;', array('page' => $pager->getFirstPage())) . '</span>' ?>
  <?php echo '<span class="ull_pager_element color_light_bg">' . ull_link_to('&lt;', array('page' => $pager->getPreviousPage())) . '</span>' ?>
  
  <?php foreach ($pages as $page): ?>
    <?php
    if ($page == $pager->getPage())
    {
      echo '<span class="ull_pager_element">' . $page . '</span>';
    }
    else
    {
      echo '<span class="ull_pager_element_inactive">' .
      ull_link_to($page, array('page' => $page)) . '</span>';
    }
    ?>
    
  <?php endforeach ?>
  <?php echo '<span class="ull_pager_element color_light_bg">' . ull_link_to('&gt;', array('page' => $pager->getNextPage())) . '</span>' ?>
  <?php echo '<span class="ull_pager_element color_light_bg">' . ull_link_to('&raquo', array('page' => $pager->getLastPage())) . '</span>' ?>
<?php endif ?>