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
  <?php echo ull_link_to('&laquo;', array('page' => $pager->getFirstPage())) ?>
  <?php echo ull_link_to('&lt;', array('page' => $pager->getPreviousPage())) ?>
  
  <?php foreach ($pages as $page): ?>
    <?php echo ($page == $pager->getPage()) ? $page : ull_link_to($page, array('page' => $page)) ?>
  <?php endforeach ?>
  <?php echo ull_link_to('&gt;', array('page' => $pager->getNextPage())) ?>
  <?php echo ull_link_to('&raquo;', array('page' => $pager->getLastPage())) ?>
<?php endif ?>