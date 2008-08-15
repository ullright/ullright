<?php //ullCoreTools::printR($ull_form);?>

<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<?php echo $sf_data->getRaw('ull_filter')->getHtml() ?>


<!-- Action row -->

<div class='action_buttons'>
  <div class='action_buttons_left'>  
  
    <?php
      // == Create link
      if ($app_slug):
        $create_link = 
          button_to(
            __('Create %1%', array('%1%' => ullCoreTools::getI18nField($app, 'doc_caption')))
            , 'ullFlow/create?app=' . $app_slug
          ) . ' &nbsp ';
        echo $create_link;
      endif;
  
      // == Status select
      echo __('Status') . ': ';
      echo ull_reqpass_form_tag(array('page' => '', 'flow_action' => ''), array('class' => 'inline'));

      // == flow_action select
      $select_children_db = objects_for_select(
          $flow_actions
          , 'getSlug'
          , '__toString'
          , $flow_action
          , null
        );
    
      $select_children = '<option value=""';
      $select_children .=  ($flow_action == '') ? ' selected="selected"' : '';
      $select_children .= '>' . __('All active') . '</option>';
      
      $select_children .= '<option value="all"';
      $select_children .= ($flow_action == 'all') ? ' selected="selected"' : '';
      $select_children .= '>' . __('All') . '</option>';
      
      $select_children .= $select_children_db;
    
      echo select_tag('flow_action', $select_children, array('onchange' => 'submit()'));
      echo '</form>';
      echo ' &nbsp ';
  
      // == search field
      //echo __('Search', null, 'common') . ': ';
      echo ull_reqpass_form_tag(array('page' => '', 'flow_search' => ''), array('class' => 'inline', 'name' => 'ull_flow_search_form'));
    
      echo input_tag('flow_search', $ull_flow_search , array('size' => '15', 'onchange' => 'submit()', 'title' => __('Searches for ID, subject and tags', null, 'common')));
      echo ull_button_to_function(__('Search', null, 'common'), 'document.ull_flow_search_form.submit();');
      
      echo '</form>';
      echo ' &nbsp ';
  
    ?>
  </div>
  <div class='clear'></div>
</div>
<br />

<!-- pager: num of results -->

<?php include_partial('ullTableTool/ullPagerTop',
        array('pager' => $ull_flow_doc_pager)
      ); ?>  

<?php
  // switch list/tabular style
//  echo ull_link_to(
//          __('List view', null, 'common')
//          , array(
//              'action' => 'list'
//            )
//        );
?>





<br />



<table class='result_list'>


<!-- header -->
<?php $fields_info = $ull_form->getFieldsInfo(); ?>
<thead>
<tr>  
  <?php foreach ($fields_info as $field_name => $field_info): ?>
    <?php //if (@$field_info['enabled'] && @$field_info['show_in_list']): ?>
      <th>
      <?php //echo $field_info['name_humanized']; ?>
      <?php

        if ($order == $field_name) {
//          $arrow  = ($order_dir == 'desc') ? ' &uArr;' : ' &dArr;';
          $arrow  = ($order_dir == 'desc') ? ' ↑' : ' ↓';
          $dir    = ($order_dir == 'desc') ? 'asc' : 'desc';
        } else {
          $arrow = '';
          $dir = 'asc'; // always default to 'asc' order for a new column
        }
        
        echo ull_link_to(
          $field_info['name_humanized'] . $arrow
          , array(
              'order' => $field_name
              , 'order_dir' => $dir
            )
        );
      ?>
      </th>
    <?php //endif; ?>
  <?php endforeach; ?>
  <th></th>
</tr>
</thead>

<!-- data -->
<tbody>
<?php //ullCoreTools::printR($ull_form); exit(); ?>

<?php $odd = false; ?>
<?php foreach ($ull_form->getFieldsData() as $row): ?>
  
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
    <?php //if (@$field_info['enabled'] && @$field_info['show_in_list']): ?>
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
                , 'ullFlow/edit?&doc=' . $id
              );
                
            ?>
          <?php elseif ($field_name == 'title'): ?>
            <?php
              echo link_to(
                $value
                , 'ullFlow/edit?&doc=' . $id
              );
                
            ?>
          <?php else: ?>
            <?php echo $value; ?>
          <?php endif; ?>
        </td>
      <?php //endif; // end of enabled && show_in_list ?> 
    <?php endforeach; ?>
    <td>
      <?php 
//        echo link_to(
//          __('Delete', null, 'common')
//          , 'ullTableTool/delete?table=' . $table_name . '&id=' . $id
//        );

          echo ull_icon(
            'ullFlow/edit?doc=' . @$id 
            , 'edit'
            , __('Edit', null, 'common')
          );
      
          echo ull_icon(
            'ullFlow/delete?doc=' . @$id
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
        array('pager' => $ull_flow_doc_pager)
      ); ?>  


<br />
<div class='action_buttons'>
  <div class='action_buttons_left'>
    <?php if ($app_slug): echo $create_link; endif;?>
  </div>
  <div class='clear'></div>
</div>
