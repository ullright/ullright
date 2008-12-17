<?php echo $breadcrumbTree->getHtml(ESC_RAW) ?>

<?php echo $ull_filter->getHtml(ESC_RAW) ?>

	<!-- Action row -->
	<?php echo ull_form_tag(array('page' => '', 'filter' => array('status' => ''))) ?>
	
	<ul class='ull_action color_light_bg'>
	  
	    <li>
        <?php if (isset($app)): ?>
          
          <?php echo ull_button_to(__('Create', null, 'common'), 'ullFlow/create?app=' . $app->slug) ?>
        <? endif ?>
      </li>
	
	    <?php echo $filter_form ?>   
	    
      
      <?php        
      // == Status select
//      echo __('Status') . ': ';
////      echo ull_reqpass_form_tag(array('page' => '', 'status' => ''), array('class' => 'inline'));
//
//      // == flow_action select
//      $select_children_db = objects_for_select(
//          $flow_actions
//          , 'getSlug'
//          , '__toString'
//          , $flow_action
//          , null
//        );
//    
//      $select_children = '<option value=""';
//      $select_children .=  ($flow_action == '') ? ' selected="selected"' : '';
//      $select_children .= '>' . __('All active') . '</option>';
//      
//      $select_children .= '<option value="all"';
//      $select_children .= ($flow_action == 'all') ? ' selected="selected"' : '';
//      $select_children .= '>' . __('All') . '</option>';
//      
//      $select_children .= $select_children_db;
//    
//      echo select_tag('flow_action', $select_children, array('onchange' => 'submit()'));
//      echo '</form>';
//      echo ' &nbsp ';
      ?>
      
      <?php echo submit_image_tag(ull_image_path('search'),
              array('class' => 'image_align_middle_no_border', 'name' => '')) ?>                    
	
	</ul>
	 
	</form>	
	

<!-- pager: num of results -->
<?php include_partial('ullTableTool/ullPagerTop',
        array('pager' => $pager)
      ); ?> 

<br />
<?php if ($generator->getRow()->exists()): ?>
  <table class='result_list'>

  <?php include_partial('ullTableTool/ullResultListHeader', array(
      'generator' => $generator,
      'order'     => $order,
      'order_dir' => $order_dir,
  )); ?>
  
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
      <td class='no_wrap'>          
        <?php
            echo ull_link_to(ull_image_tag('edit'), 'ullFlow/edit?' . $identifier);
            echo ull_link_to(ull_image_tag('delete'), 'ullFlow/delete?' . $identifier,
                'confirm='.__('Are you sure?', null, 'common')); 
        ?>
      </td>
      <?php echo $form ?>
    </tr>
  <?php endforeach; ?>
  
  </tbody>
  </table>
<?php endif ?>


