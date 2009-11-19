<h1>
  <?php echo $generator->getForm()->offsetGet('first_name')->render() ?>
  <?php echo $generator->getForm()->offsetGet('last_name')->render() ?>
</h1>

<div id="ull_user_popup_sidebar">
  <div id="ull_user_popup_photo">
    <?php echo $generator->getForm()->offsetGet('photo')->render() ?>
  </div>
  
  <?php if ($allow_edit): ?>
  
    <em><?php echo __('Actions', null, 'common') ?></em>
  
    <ul id="ull_user_popup_sidebar_list">
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
    </ul>
  <?php endif?>
  
  
</div>

<div class="edit_container" id="ull_user_popup">

<?php include_partial('ullTableTool/editTable', array('generator' => $generator)) ?>

<?php include_partial('ullTableTool/editTable', array('generator' => $location_generator)) ?>

</div>