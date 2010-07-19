<?php echo $breadcrumb_tree ?>

<?php include_partial('ullTableTool/globalError', array('form' => $generator->getForm())) ?>

<?php echo form_tag($form_uri, array('multipart' => 'true', 'id' => 'ull_tabletool_form')) ?>

<div class="edit_container">

<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>

<?php include_partial('ullTableTool/editTable', array('generator' => $generator)) ?>

<div class='edit_action_buttons color_light_bg'>
  <h3><?php echo __('Actions', null, 'common')?></h3>
  
  <div class='edit_action_buttons_left'>
    <?php
      if ($generator->getRow()->exists() && $generator->isVersionable() && $generator->getEnableFutureVersions())
      {
        echo ' <label for="fields_scheduled_update">';
        echo __('Schedule changes on this date', null, 'common') . ':';
        echo '</label><br />'; 
        echo $generator->getForm()->offsetGet('scheduled_update_date')->render();
        echo $generator->getForm()->offsetGet('scheduled_update_date')->renderError();
      }
    ?>
    <ul>
        <li>
          <?php             
            echo ull_submit_tag(
              __('Save and return to list', null, 'common'),
              array('name' => 'submit|action_slug=save_close')
            );  
          ?>
        </li>
        
        <?php if (isset($edit_action_buttons)): ?>
          <?php  include_partial('ullTableTool/editActionButtons', array('buttons' => $edit_action_buttons)) ?>
        <?php endif ?>        
    </ul>
  </div>

  <div class='edit_action_buttons_right'>
    <ul>
    
      <li>
        <?php 
          echo ull_submit_tag(
            __('Save only', null, 'common'), 
            array('name' => 'submit|action_slug=save_only', 'form_id' => 'ull_tabletool_form', 'display_as_link' => true)
          ); 
        ?>
      </li>    
    
      <li>
        <?php 
          echo ull_submit_tag(
            __('Save and new', null, 'common'), 
            array('name' => 'submit|action_slug=save_new', 'form_id' => 'ull_tabletool_form', 'display_as_link' => true)
          ); 
        ?>
      </li>    
    
      <li>
    <?php if ($generator->getRow()->exists()): ?>    
          <?php 
            if ($generator->getAllowDelete() && isset($table_name))
            {
	            echo link_to(
	              __('Delete', null, 'common')
	              , 'ullTableTool/delete?table=' . $table_name . '&' . $generator->getIdentifierUrlParams(0)
	              , 'confirm='.__('Are you sure?', null, 'common')
	              );
            }
          ?> &nbsp; 
        <?php endif; ?>
      </li>
      
      <?php if (isset($edit_action_buttons)): ?>
          <?php  include_partial('ullTableTool/editActionButtonsRight', array('buttons' => $edit_action_buttons)) ?>
        <?php endif ?>
    </ul>
  </div>

  <div class="clear"></div>  
  
</div>
</div>
</form>   

<?php 
  echo ull_js_observer("ull_tabletool_form");
?>  

<?php if ($generator->hasFutureVersions()): ?>
  <div id="edit_future_versions">
  <h2><?php echo __('Scheduled updates', null, 'common') ?></h2>
    
  <?php 
    $fg = $generator->getFutureGenerators();
      
    $cnt = count($fg);
    for ($i = 0; $i < $cnt; $i++)
    { 
      $id = $fg[$i]->getIdentifierArray();
      //var_dump($id);
      echo '<div class="edit_container">';
      echo '<br /><h4>' . ull_format_date($fg[$i]->getScheduledUpdateDate()) . '</h4>' .
            //__('Future version ', null, 'common') . ($i + 1) . ' - ' .
            __('by', null, 'common') . ' ' . $fg[$i]->getUpdator() . ' - ' .
            ull_format_datetime($fg[$i]->getUpdatedAt()) . ' - ' .
            ull_link_to(ull_image_tag('delete'), 
            ullCoreTools::appendParamsToUri(
              $delete_future_version_base_uri,
                'id=' . $id['id'] .
                '&version=' . $id['version'])
                , 'confirm='.__('Are you sure?', null, 'common')
                ) .
              '<br /><br />';
      echo '<table class="edit_table"><tbody>';
      echo ($fg[$i]->hasColumns()) ? $fg[$i]->getForm() : '<tr><td>' . __('No changes') . '</td></tr>';
      echo '</tbody></table>';
      echo '</div>';
    }
  ?>
  </div>
<?php endif ?>


<?php include_partial('ullTableTool/history', array(
  'generator' => $generator
))?>


<?php use_javascripts_for_form($generator->getForm()) ?>
<?php use_stylesheets_for_form($generator->getForm()) ?>