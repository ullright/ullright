<?php echo $breadcrumb_tree ?>
<?php $user_widget = $sf_data->getRaw('user_widget') ?>

<?php if ($generator->getForm()->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  <?php echo $generator->getForm()->renderGlobalErrors() ?>
  </div>  
  
<?php endif; ?>

<?php if ($generator->getRow()->exists()): ?>
  <?php include_partial('ullFlowHeader', array('doc' => $doc, 'user_widget' => $user_widget)) ?>
<?php endif ?>

<?php echo form_tag('ullFlow/edit?app=' . $app->slug . ($doc->id ? '&doc=' . $doc->id : '')
  , 'id=edit_form'); ?>  
  


<div class="edit_container">
<table class="edit_table">
<tbody>

<?php foreach ($generator->getActiveColumns() as $column_name => $columns_config): ?>
  <?php echo $generator->getForm()->offsetGet($column_name)->renderRow() ?>
<?php endforeach ?>

</tbody>
</table>

<?php if ($generator->getDefaultAccess() == 'w'): ?>
  <div class='edit_action_buttons color_light_bg'>
      <h3><?php echo __('Actions', null, 'common')?></h3>
      
      <div class='edit_action_buttons_left'>
      
      <?php if (count($generator->getListOfUllFlowActionHandlers())): ?>
        
        <p>
          <label for="fields_memory_comment">
            <?php echo __('Comment for this action') . ':'; ?>
          </label><br />
          <?php echo $generator->getForm()->offsetGet('memory_comment')->render() ?>
          <?php echo $generator->getForm()->offsetGet('memory_comment')->renderError() ?>
        </p>
        
        <?php if ($app['enter_effort']): ?>
          <p>
            <label for="fields_duration_seconds">
              <?php echo __('Effort', null, 'ullFlowMessages') . ':'; ?>
            </label>
            <?php echo $generator->getForm()->offsetGet('duration_seconds')->render() ?>
            <?php echo $generator->getForm()->offsetGet('duration_seconds')->renderError() ?>
            <dfn><?php echo __('Hours : minutes', null, 'ullFlowMessages') ?></dfn>
          </p>        
        <?php endif ?>
        
        <ul>
          <?php
          foreach ($generator->getListOfUllFlowActionHandlers() as $action_handler): ?>
            <li>
            <?php echo $action_handler->render(ESC_RAW); ?>
            </li>
          <?php endforeach ?>
        </ul>
        
      <?php else: ?>
        <p class='no_access_info'>
          <?php echo __('You cannot perfom any workflow actions at the moment, because the document is not assigned to you.') ?>
        </p>         
      <?php endif; ?>
  
    </div>
  
      
    <div class='edit_action_buttons_right'>
      <ul>
        
        <?php if ($generator->getDefaultAccess() == 'w'): ?>
  
          <li>
          <?php 
            echo ull_submit_tag(
              __('Save only', null, 'common'),
              array('name' => 'submit|action_slug=save_only', 'form_id' => 'edit_form', 'display_as_link' => true)
            ); 
          ?>
          </li>
          
          <li>  
          <?php 
            echo ull_submit_tag(
              __('Save and return to list', null, 'common'),
              array('name' => 'submit|action_slug=save_close', 'form_id' => 'edit_form', 'display_as_link' => true)
            ); 
          ?>
          </li>
          
          
          <?php /*if ($doc_id): ?>
            <li>
            <?php 
              echo link_to(
                __('Delete', null, 'common'), 
                'ullFlow/delete?doc='.$doc_id, 
                'confirm='.__('Are you sure?', null, 'common')
                ); 
            ?>
            </li> 
          <?php endif; */?>
          
        <?php endif; ?>
      
        <li>
        <?php /*
          echo ull_link_to(
            __('Cancel', null, 'common') 
            , $referer_edit
            , 'ull_js_observer_confirm=true'
          );
        */?>
        </li>
      </ul>  
    </div>
  
    <!-- <div class="clear"></div>  -->
 </div>
<?php endif; ?>



<?php /* echo input_hidden_tag('ull_flow_action') ?>
<?php echo input_hidden_tag('external') ?>
<?php echo input_hidden_tag('external_field') */?>

<?php if ($doc->exists()): ?>
  <div id="ull_memory" class="ull_memory_background">
  <h3><?php echo __('Progress')?></h3>
  <ul>
    <?php 
      $tempdate = -1;
      
      foreach ($doc->getUllFlowMemoriesOrderedByDate() as $memory): ?>
      <?php
        if ($tempdate != substr($memory->created_at, 0, 10)) {
          if ($tempdate != -1) 
            echo '</ul></li>';
            
          echo '<li class="ull_memory_date">' . ull_format_date($memory->created_at) . '</li>' .
                  '<li class="ull_memory_day"><ul class="ull_memory_day">';
        } ?>
      <li>
        <span class="ull_memory_light">
        <?php echo substr($memory->created_at, 11, 5) ?>
        </span>&ndash;
        <?php echo $memory->UllFlowAction->label ?>
        <?php if ($memory->UllFlowAction->is_show_assigned_to): ?>
          <?php echo $user_widget->render(null, $memory->assigned_to_ull_entity_id) ?>
        <?php endif ?>
        <?php echo __('by'); ?>
        <?php echo $user_widget->render(null, $memory->creator_user_id) ?>
        
        <?php if ($comment = $memory->comment): ?>
          <ul class="ull_memory_comment">
            <li class="ull_memory_lightsmall"> <?php echo $comment ?>
             </li>
          </ul>
        <?php endif ?>
      </li>
    <?php
      $tempdate = substr($memory->created_at, 0, 10);
    endforeach ?>
  </ul></li>
  </ul>
  <br />
  </div>
<?php endif ?>

</div> <!-- end of edit_container -->

</form>

<?php
  echo ull_js_observer("edit_form");
//  ullCoreTools::printR($ull_form);
?>   
