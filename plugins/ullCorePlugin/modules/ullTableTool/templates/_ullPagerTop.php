<div class='pager'>

  <div class='pager_right'>
    <?php include_partial('ullTableTool/ullPager',
            array('pager' => $pager)
          ); ?>  
  </div>

  <div class='pager_left'><?php 
      echo format_number_choice(
        '[0]No results found|[1]1 result found|(1,+Inf]%1% results found',
        array('%1%' => $pager->getNumResults()),
        $pager->getNumResults()
        , 'common'
      ); 
    ?>.
    <?php
    if (isset($logged_in) && (!$logged_in) && ($pager->getNumResults() == 0))
    {
      echo __('Not logged in', null, 'common') . '.';
    }
    
    if ($paging == 'false')
    {
      echo '<span class="paging_hint">' . __(
        ull_link_to('Enable paging', array('paging' => null)),
        null, 'common'
      ) . '</span>';
    }
    
    if ($pager->haveToPaginate())
    {
      echo '<span class="paging_hint">' .
        ull_link_to('Disable paging', array('paging' => 'false')) .
        '</span>';
    }
  ?></div>
  

  
  <div class='clear_right'></div>
  
</div>