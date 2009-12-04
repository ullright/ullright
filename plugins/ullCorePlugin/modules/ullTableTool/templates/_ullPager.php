<?php
    // TODO: refactor into a component
    $sliding_range = sfConfig::get('app_pager_sliding_range', 5);
    $pager_range = $pager->getRange(
        'Sliding',
        array(
            'chunk' => $sliding_range
        )
    );
    $pages = $pager_range->rangeAroundPage();
?>    

<?php if ($pager->haveToPaginate()): ?>
  <span class="ull_pager_element color_light_bg"><?php
    if ($pager->getPage() != 1)
    {
      echo ull_link_to(
        '<span class="ull_pager_aquo">&laquo;</span>', 
        array('page' => $pager->getFirstPage()), 
        array('title' => __('First page', null, 'ullCoreMessages'))
      );
    }   
    else
    {
      echo '<span class="ull_pager_aquo">&laquo;</span>';
    }
  ?></span>
   
  <span class="ull_pager_element color_light_bg"><?php
  if ($pager->getPage() != 1) 
    {
      echo ull_link_to(
        '&lt;', 
        array('page' => $pager->getPreviousPage()), 
        array('title' => __('Previous page', null, 'ullCoreMessages'))
      );
    }
    else
    {
      echo '&lt;';
    }
  ?></span>
  
  <?php if (pager_has_more_left_pages($pager, $sliding_range)): ?>
    <span class="ull_pager_element_inactive">...</span>
  <?php endif ?>
  
  <?php foreach ($pages as $page): ?>
    <?php if ($page == $pager->getPage()): ?>
      <span class="ull_pager_element"><?php echo $page ?></span>
    <?php else: ?>
      <span class="ull_pager_element_inactive"><?php 
        echo ull_link_to($page, array('page' => $page)) 
      ?></span>
    <?php endif ?>  
  <?php endforeach ?>
  
  <?php if (pager_has_more_right_pages($pager, $sliding_range)): ?>
    <span class="ull_pager_element_inactive">...</span>
  <?php endif ?>
  
  <span class="ull_pager_element color_light_bg"><?php 
    if($pager->getPage() != $pager->getLastPage()) 
    {
      echo ull_link_to(
        '&gt;', 
        array('page' => $pager->getNextPage()), 
        array('title' => __('Next page', null, 'ullCoreMessages'))
      );
    }
    else
    {
      echo '&gt;';
    }
  ?></span>
  
  <span class="ull_pager_element color_light_bg"><?php
    if($pager->getPage() != $pager->getLastPage()) 
    {
      echo ull_link_to(
        '<span class="ull_pager_aquo">&raquo;</span>', 
        array('page' => $pager->getLastPage()), 
        array('title' => __('Last page', null, 'ullCoreMessages'))
      );
    }
    else
    {
      echo '<span class="ull_pager_aquo">&raquo;</span>';
    }
  ?></span>
<?php endif ?>