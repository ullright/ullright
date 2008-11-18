<?php echo $breadcrumbTree->getHtml(ESC_RAW) ?>

<?php echo $ull_filter->getHtml(ESC_RAW) ?>

<?php // detect empty table_tool ?>
<?php if (!$generator->getRow()->isModified()): ?>

	<!-- Action row -->
	<?php echo ull_form_tag(); ?>
	
	<ul class='ull_action'>
	  
	    <li>
        <?php if (isset($app)): ?>
          <?php echo ull_button_to(__('Create', null, 'common'), 'ullFlow/create?app=' . $app->slug); ?>
        <? endif ?>
      </li>
	
      <?php ?>	   
	    <?php echo $filter_form ?>   
	    <li><?php echo submit_tag('&gt;');?></li>
      <?php ?> 
	
	</ul>
	 
	</form>	
	

<!-- pager: num of results -->

<?php include_partial('ullTableTool/ullPagerTop',
        array('pager' => $pager)
      ); ?> 
<br />

  <table class='result_list'>
  
  <!-- header -->
  <thead>
  <tr>  
    <th>&nbsp;</th>
    <?php foreach ($generator->getLabels() as $field_name => $label): ?>
      <th>
        <?php 
        if ($order == $field_name) {
          $arrow  = ($order_dir == 'desc') ? ' ↑' : ' ↓';
          $dir    = ($order_dir == 'desc') ? 'asc' : 'desc';
        } else {
          $arrow = '';
          $dir = 'asc'; // always default to 'asc' order for a new column
        }
        
        echo ull_link_to(
          $label . $arrow
          , array(
              'order' => $field_name,
              'order_dir' => $dir,
            )
        );
      ?>      
      </th>
    <?php endforeach; ?>
  </tr>
  </thead>
  
  <!-- data -->
  
  <tbody>
  <?php $odd = false; ?>
  <?php foreach($generator->getForms() as $row => $form): ?>
      <?php
        if ($odd) {
          $odd_style = ' class=\'odd\'';
          $odd = false;
        } else {
          $odd_style = '';
          $odd = true;
        }
        $identifier = $generator->getIdentifierUrlParams($row);
      ?>
    <tr <?php echo $odd_style ?>>
      <td>          
        <?php
            echo ull_icon(
              'ullFlow/edit?' . $identifier
              , 'edit'
              , __('Edit', null, 'common')
            );
        
            echo ull_icon(
              'ullFlow/delete?table=' . $identifier
              , 'delete'
              , __('Delete', null, 'common')
              , 'confirm='.__('Are you sure?', null, 'common')
            );
        ?>
      </td>
      <?php echo $form ?>
    </tr>
  <?php endforeach; ?>
  
  </tbody>
  </table>
<?php else: ?>
  <p class='form_error'><?php echo __('No results found', null, 'common') ?></p>
<?php endif ?>

<?php /*


  
<?php 
  if ($app_slug):
    $create_link = 
      link_to(
        __('Create %1%', array('%1%' => ullCoreTools::getI18nField($app, 'doc_caption')))
        , 'ullFlow/create?app=' . $app_slug
      ) . ' &nbsp ';
    echo $create_link;
  endif;
  
  echo __('Status') . ': ';
  echo ull_reqpass_form_tag(array('page' => '', 'flow_action' => ''), array('class' => 'inline'));

  // flow_action select
  $select_children = objects_for_select(
      $flow_actions
      , 'getSlug'
      , '__toString'
      , null
      , null
    );

  $select_children = 
    '<option value=""></option>'
    . '<option value="">' . __('All active') . '</option>'
    . '<option value="all">' . __('All') . '</option>'
    . $select_children
  ;

  echo select_tag('flow_action', $select_children, array('onchange' => 'submit()'));
  echo '</form>';
?>
<br /><br />

<?php 
  echo format_number_choice(
    '[0]No results found|[1]1 result found|(1,+Inf]%1% results found',
    array('%1%' => $ull_flow_doc_pager->getNbResults()),
    $ull_flow_doc_pager->getNbResults()
    , 'common'
  ); 
?>.
 
<?php
  ullCoreTools::printR('xxx' .$ull_flow_doc_pager->getNbResults());
//  $cursor = $entries_pager->getFirstIndice();
  echo __(
    'Displaying results %1% to %2%'
    , array (
      '%1%' => ($ull_flow_doc_pager->getNbResults()) ? $ull_flow_doc_pager->getFirstIndice() : 0
      ,'%2%' => $ull_flow_doc_pager->getLastIndice()
    )
    , 'common'
  );
?>.

&nbsp; aaaaaaaaaaaa

<?php
  // switch list/tabular style
  echo ull_link_to(
          __('Tabular view', null, 'common')
          , array(
              'action' => 'tabular'
            )
        );
?>




<br /><br />



<?php foreach ($ull_flow_doc_pager->getResults() as $ull_flow_doc): ?>

    <?php // echo $ull_flow_doc->getTitle() . '<br />'; ?>

    <?php 
      include_component('ullFlow', 'ullFlowHeader', array(
        'ull_flow_doc'  => $ull_flow_doc
        , 'app_slug'    => $app_slug
//        ,'cursor' => $cursor
      )); 
    ?>

  <?php //++$cursor; ?>
<?php endforeach; ?>

<br />

<?php if ($ull_flow_doc_pager->haveToPaginate()): ?>
  <?php echo ull_link_to('&laquo;', array('page' => $ull_flow_doc_pager->getFirstPage())) ?>
  <?php echo ull_link_to('&lt;', array('page' => $ull_flow_doc_pager->getPreviousPage())) ?>
  
  <?php $links = $ull_flow_doc_pager->getLinks(); foreach ($links as $page): ?>
    <?php echo ($page == $ull_flow_doc_pager->getPage()) ? $page : ull_link_to($page, array('page' => $page)) ?>
    <?php if ($page != $ull_flow_doc_pager->getCurrentMaxLink()): ?> - <?php endif ?>
  <?php endforeach ?>
  <?php echo ull_link_to('&gt;', array('page' => $ull_flow_doc_pager->getNextPage())) ?>
  <?php echo ull_link_to('&raquo;', array('page' => $ull_flow_doc_pager->getLastPage())) ?>
<?php endif ?>

<br /><br />

<?php if ($app_slug): echo $create_link; endif;?>

<?php */ ?>

