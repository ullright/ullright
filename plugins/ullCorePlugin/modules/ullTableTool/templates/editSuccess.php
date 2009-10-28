<?php echo $breadcrumb_tree ?>

<?php if ($generator->getForm()->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  <?php echo $generator->getForm()->renderGlobalErrors() ?>
  </div>  
<?php endif; ?>

<?php echo form_tag($form_uri, array('id' => 'ull_tabletool_form')) ?>

<div class="edit_container">
<table class="edit_table">
<tbody>

<?php
  foreach ($generator->getForm()->getWidgetSchema()->getPositions() as $column_name)
  {
    $ccc = $generator->getColumnsConfig();
    
    $attributes = array();
    if (isset($ccc[$column_name]) && $ccc[$column_name]->getInjectIdentifier())
    {
      $attributes = array('identifier' => $generator->getIdentifierValue());
    }
    
    if ($column_name != 'scheduled_update_date')
    {
      echo $generator->getForm()->offsetGet($column_name)->renderRow($attributes);
    }
    
    if (isset($ccc[$column_name]) && $ccc[$column_name]->getShowSpacerAfter())
    {
      echo '<tr class="edit_table_spacer_row"><td colspan="3"></td></tr>';
    }
  }
?>

</tbody>
</table>


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
        <?php echo submit_tag(__('Save', null, 'common')) ?>
      </li>
    </ul>
  </div>

  <div class='edit_action_buttons_right'>
    <ul>
      <li>
    <?php if ($generator->getRow()->exists()): ?>    
          <?php 
            if ($generator->getAllowDelete())
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
      echo '</div><br />';
    }
  ?>
  </div>
<?php endif ?>

<?php if ($generator->hasGeneratedVersions()): ?>
  <div id="edit_versions">
  <h2><?php echo __('Version history', null, 'common') ?></h2>
  
  <?php   
    $hg = $generator->getHistoryGenerators();
    $cnt = count($hg);
    for ($i = $cnt; $i > 0; $i--)
    { 
      echo '<div class="edit_container">';
      echo '<br /><h4>' . ull_format_datetime($hg[$i - 1]->getUpdatedAt()) . '</h4>' .
            __('Version ', null, 'common') . $i . ' - ';
      if ($hg[$i - 1]->wasScheduledUpdate())
      {
        echo __('Scheduled update', null, 'common') . ' ' . __('by', null, 'common') . ' ' . $hg[$i - 1]->getScheduledUpdator();
      }
      else
      {
        echo __('by', null, 'common') . ' ' . $hg[$i - 1]->getUpdator();
      }
      echo '<br />';
      echo '<table class="edit_table"><tbody>';
      echo ($hg[$i - 1]->hasColumns()) ? $hg[$i - 1]->getForm() : '<tr><td>' . __('No changes') . '</td></tr>';
      echo '</tbody></table>';
      echo '</div><br />';
    }
  ?>
  </div>
<?php endif ?>