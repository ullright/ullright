<?php if ($pager->getNumResults()): ?>

  <div class="pager" id="pager_bottom">
  
    <div class="pager_right">
      <?php include_partial('ullTableTool/ullPager',
              array('pager' => $pager)
            ); ?>  
    </div>
  
    <div class="pager_left">
      <?php echo __(
          'Displaying results %1% to %2%'
            , array (
              '%1%' => $pager->getFirstIndice(),
              '%2%' => $pager->getLastIndice()
            )
            , 'common'
          ) . '.';
      ?>
    </div>
    
  </div>

<?php endif ?>