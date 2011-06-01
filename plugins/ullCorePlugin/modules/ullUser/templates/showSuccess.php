<h1>
  <?php echo $generator->getForm()->offsetGet('display_name')->render() ?>
</h1>

<div id="ull_user_popup_sidebar">
  <div id="ull_user_popup_photo">
    <?php try { echo $generator->getForm()->offsetGet('photo')->render(); } catch (Exception $e) {} ?>
  </div>
  
  <?php if ($show_orgchart_link || $allow_edit): ?>
    <em><?php echo __('Actions', null, 'common') ?></em>
  <?php endif ?>
  
  <ul id="ull_user_popup_sidebar_list">
  
    <?php if ($show_orgchart_link): ?>
      <li>
        <?php 
          echo ull_link_to(
             __('Show user in orgchart', null, 'ullCoreMessages'), 
            'ullOrgchart/list?user_id=' . $org_chart_link_user_id, 
            array('link_new_window' => true)
          ) 
        ?>
      </li>  
    <?php endif ?>
  
    <?php if ($allow_edit): ?>
  
      <li>
        <?php 
          echo ull_link_to(
             __('Edit user', null, 'ullCoreMessages'), 
            'ullTableTool/edit?table=UllUser&id=' . $user->id, 
            array('link_new_window' => true)
          ) 
        ?>
      </li>
      <li>
        <?php 
          echo ull_link_to(
             __('Show inventory', null, 'ullCoreMessages'), 
            'ullVentory/list?filter[ull_entity_id]=' . $user->id, 
            array('link_new_window' => true)
          ) 
        ?>
      </li>      

    <?php endif?>
  
  </ul>
  
  
</div>

<div class="edit_container" id="ull_user_popup">

<?php include_partial('ullTableTool/editTable', array('generator' => $generator)) ?>

<table class="edit_table">
<tbody>
<tr class="edit_table_spacer_row"><td colspan="3"></td></tr>
</tbody>
</table>

<?php if ($is_user): ?>
  <?php include_partial('ullTableTool/editTable', array('generator' => $location_generator)) ?>
<?php endif ?>

</div>