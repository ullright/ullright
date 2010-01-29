<?php if (!isset($disable_paging_hint)): $disable_paging_hint = false; endif ?>

<div class='pager'>

  <div class='pager_right'>
    <?php include_partial('ullTableTool/ullPager',
            array('pager' => $pager)); ?>  
  </div>

  <?php $list = array() ?>

  <?php $list[] = format_number_choice(
    '[0]No results found|[1]1 result found|(1,+Inf]%1% results found',
    array('%1%' => $pager->getNumResults()),
    $pager->getNumResults()
    , 'common'
  ) . '.' ?> 
       
  <?php if (isset($logged_in) && (!$logged_in) && ($pager->getNumResults() == 0)): ?>
    <?php $list[] = __('Not logged in', null, 'common') . '.'; ?>
  <?php endif ?>
  
  <?php if (!$disable_paging_hint): ?>
  
    <span class="paging_hint">
      <?php if ($paging == 'false'): ?>
        <?php $list[] = ull_link_to(__('Enable paging', null, 'common'),
          array('paging' => null)) ?>
      <?php endif?>
    
      <?php if ($pager->haveToPaginate()): ?>
        <?php $list[] = ull_link_to(__('Disable paging', null, 'common'), array('paging' => 'false')) ?>
      <?php endif ?>
    </span>
        
  <?php endif ?>
  
  <?php $list[] = ull_link_to(__('Export as spreadsheet', null, 'common'),
    array('export_csv' => 'true'))?>
      
  <div class='pager_left'>
    <?php echo implode(' &nbsp;&nbsp; ', $list) ?>      
  </div>
  
  <div class='clear_right'></div>
  
</div>