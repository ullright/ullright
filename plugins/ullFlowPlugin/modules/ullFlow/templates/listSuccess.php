<?php echo $breadcrumbTree->getHtml(ESC_RAW) ?>

<?php echo $ull_filter->getHtml(ESC_RAW) ?>

	<!-- Action row -->
	<?php echo ull_form_tag(); ?>
	
	<ul class='ull_action color_light_bg'>
	  
	    <li>
        <?php if (isset($app)): ?>
          
          <?php echo ull_button_to(__('Create', null, 'common'), 'ullFlow/create?app=' . $app->slug); ?>
        <? endif ?>
      </li>
	
      <?php ?>	   
	    <?php echo $filter_form ?>   
	    <?php echo submit_image_tag(ull_image_path('search'),
              array('class' => 'tc_search_quick_top_img'));
              //echo submit_tag('&gt;');
          ?>
      <?php ?> 
	
	</ul>
	 
	</form>	
	

<!-- pager: num of results -->
<?php include_partial('ullTableTool/ullPagerTop',
        array('pager' => $pager)
      ); ?> 

<br />
<?php if ($generator->getRow()->exists()): ?>
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
            echo ull_link_to(ull_image_tag('edit'), 'ullFlow/edit?' . $identifier);
            echo ull_link_to(ull_image_tag('delete'), 'ullFlow/delete?table=' . $identifier,
                'confirm='.__('Are you sure?', null, 'common')); 
            
//            echo ull_icon(
//              'ullFlow/edit?' . $identifier
//              , 'edit'
//              , __('Edit', null, 'common')
//            );
//        
//            echo ull_icon(
//              'ullFlow/delete?table=' . $identifier
//              , 'delete'
//              , __('Delete', null, 'common')
//              , 'confirm='.__('Are you sure?', null, 'common')
//            );
        ?>
      </td>
      <?php echo $form ?>
    </tr>
  <?php endforeach; ?>
  
  </tbody>
  </table>
<?php endif ?>


