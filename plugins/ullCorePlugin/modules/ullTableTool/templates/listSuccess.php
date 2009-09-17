<?php echo $sf_data->getRaw('breadcrumb_tree')->getHtml() ?>
<?php $generator = $sf_data->getRaw('generator') ?>

<h3><?php echo $generator->getTableConfig()->getLabel() ?></h3>
<p><?php echo $generator->getTableConfig()->getDescription() ?></p>

<?php echo $ull_filter->getHtml(ESC_RAW) ?>

<?php echo ull_form_tag(array('page' => '', 'filter' => array('search' => ''))) ?>

<!-- TODO: add ordered list for options/actions -->

<ul class='list_action_buttons color_light_bg'>
  
  <li><?php echo ull_button_to(__('Create', null, 'common'), 'ullTableTool/create?table=' . $table_name); ?></li>
  
  <li><?php echo ull_button_to(__('Configure table'), 
      'ullTableTool/list?table=UllTableConfig&filter[search] =' . $table_name);?></li>                   
  
  <li>
    <?php echo $filter_form['search']->renderLabel() ?>: 
    <?php echo $filter_form['search']->render() ?>
    <?php echo submit_image_tag(ull_image_path('search', 16, 16, 'ullCore'),
              array('alt' => 'search_list', 'class' => 'image_align_middle_no_border')) ?>
  </li> 

</ul>
 
</form>

<?php include_partial('ullTableTool/ullPagerTop',
        array('pager' => $pager)
      ); ?>

<?php // detect empty table_tool ?>
<?php if ($generator->getRow()->exists()): ?>
  <table class='list_table'>
  
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
        
        $idAsArray = (array) $generator->getIdentifierUrlParamsAsArray($row);
        
      ?>
    <tr <?php echo $odd_style ?>>
      <td class='no_wrap'>          
        <?php
            echo ull_link_to(ull_image_tag('edit'), array('action' => 'edit') + $idAsArray);
            echo ull_link_to(ull_image_tag('delete'), array('action' => 'delete') +$idAsArray,
                'confirm=' . __('Are you sure?', null, 'common')); 
        ?>
      </td>
      <?php echo $form ?>
    </tr>
  <?php endforeach; ?>
  
  </tbody>
  </table>
<?php endif ?>
