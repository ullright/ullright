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
    
    if ($pager->haveToPaginate()) {
      //  $cursor = $pager->getFirstIndice();
        echo __(
          'Displaying results %1% to %2%'
          , array (
            '%1%' => $pager->getFirstIndice(),
            '%2%' => $pager->getLastIndice()
          )
          , 'common'
        ) . '.';
    }
  ?></div>
  

  
  <div class='clear_right'></div>
  
</div>