<?php //ullCoreTools::printR($ull_form); exit(); ?>

<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<?php echo form_tag('ullTableTool/list'); ?>

<!-- TODO: add ordered list for options/actions -->

<div class='action_buttons'>
    
  <div class='action_buttons_left'>
  
    <?php echo button_to(__('Create', null, 'common'), 'ullTableTool/create?table=' . $table_name); ?> &nbsp;

   
    <?php 
      if (@$table_info_search_fields) {
        echo input_tag('search', $sf_params->get('search'), 'size=12');
        echo input_hidden_tag('table', $table_name);
        echo submit_tag(__('Search', null, 'common'), 'style=margin: 0;');
        echo ' &nbsp; ';
      }
    ?> 

    <?php
       echo '&nbsp; &nbsp;';
       echo button_to(__('Edit column info', null, 'common'), 
        'ullTableTool/list?table=ull_column_info&search=' . $table_name);
    ?> &nbsp;
    
  </div>
  <div class='clear'></div>
</div>
 
</form>
<br />

<?php include_partial('ullTableTool/ullPagerTop',
        array('pager' => $ull_table_pager)
      ); ?>  
      
<br />
  
<table class='result_list'>


<!-- header -->
<?php $fields_info = $ull_form->getFieldsInfo(); ?>
<thead>
<tr>  
  <?php foreach ($fields_info as $field_name => $field_info): ?>
    <?php if (@$field_info['enabled'] && @$field_info['show_in_list']): ?>
      <th><?php echo $field_info['name_humanized']; ?></th>
    <?php endif; ?>
  <?php endforeach; ?>
  <th></th>
</tr>
</thead>

<!-- data -->
<tbody>


<?php $odd = false; ?>
<?php foreach ($ull_form->getFieldsData() as $row): ?>
<?php //$fields_data = $ull_form->getFieldsData();   ?>

<?php //$i = 0; ?>
<?php //foreach ($ull_table_pager->getResults() as $obj): ?>

  <?php //ullCoreTools::printR($obj); //$row = $obj->get ?>
  
  <?php
    if ($odd) {
      $odd_style = ' class=\'odd\'';
      $odd = false;
    } else {
      $odd_style = '';
      $odd = true;
    }
  ?>
  
  <tr<?php echo $odd_style; ?>> 
    <?php foreach ($row as $field_name => $field): ?>
    <?php $field_info = $fields_info[$field_name];?>
    <?php if (@$field_info['enabled'] && @$field_info['show_in_list']): ?>
        <td>
          <?php
            if (@$field['function']) {
              $value = call_user_func_array($field['function'], $field['parameters']);
            } else {
              $value = $field['value'];
            }
          ?>  
          <?php if (@$field_info['primary_key']): ?>
            <?php
              $id = $field['value'];
              echo link_to(
                $value
                , 'ullTableTool/edit?table=' . $table_name . '&id=' . $id
              );
                
            ?>
          <?php else: ?>
            <?php echo $value; ?>
          <?php endif; ?>
        </td>
      <?php endif; // end of enabled && show_in_list ?> 
    <?php endforeach; ?>
    <td>
      <?php 
//        echo link_to(
//          __('Delete', null, 'common')
//          , 'ullTableTool/delete?table=' . $table_name . '&id=' . $id
//        );

          echo ull_icon(
            'ullTableTool/edit?table=' . $table_name . '&id=' . $id 
            , 'edit'
            , __('Edit', null, 'common')
          );
      
          echo ull_icon(
            'ullTableTool/delete?table=' . $table_name . '&id=' . $id
            , 'delete'
            , __('Delete', null, 'common')
            , 'confirm='.__('Are you sure?', null, 'common')
          );
     ?>  
  </tr>
<?php endforeach; ?>  
  

</tbody>
</table>

<br />

<?php include_partial('ullTableTool/ullPagerBottom',
        array('pager' => $ull_table_pager)
      ); ?>


<br />
<div class='action_buttons'>
  <div class='action_buttons_left'>
  
    <?php echo button_to(__('Create', null, 'common'), 'ullTableTool/create?table=' . $table_name); ?> &nbsp;
    
  </div>
  <div class='clear'></div>
</div>


