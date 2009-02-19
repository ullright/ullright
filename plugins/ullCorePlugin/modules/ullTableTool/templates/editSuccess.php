<?php include_partial('ullTableTool/jQueryRequirements')?>

<?php echo $sf_data->getRaw('breadcrumb_tree')->getHtml() ?>

<?php $generator = $sf_data->getRaw('generator') ?>

<?php if ($generator->getForm()->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  <?php echo $generator->getForm()->renderGlobalErrors() ?>
  </div>  
  <br /><br />
<?php endif; ?>

<?php echo form_tag($sf_context->getModuleName() . '/edit?table=' . $table_name . '&' . $generator->getIdentifierUrlParams(0), 
    array('id' => 'ull_tabletool_form')) ?>

<div class="edit_container">
<table class="edit_table">
<tbody>

<?php echo $generator->getForm(); ?>

</tbody>
</table>


<br />

<div class='edit_action_buttons color_light_bg'>
  <h3><?php echo __('Actions', null, 'common')?></h3>
  
  <div class='edit_action_buttons_left'>
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
		        echo link_to(
		          __('Delete', null, 'common')
		          , 'ullTableTool/delete?table=' . $table_name . '&' . $generator->getIdentifierUrlParams(0)
		          , 'confirm='.__('Are you sure?', null, 'common')
		          ); 
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

<br />
<br />

<?php
  if ($generator->hasGeneratedVersions())
  {
    echo '<br /><h2>'. __('Version history', null, 'common') . '</h2>';
    
    $hg = $generator->getHistoryGenerators();
    
    $cnt = count($hg);
    for ($i = $cnt; $i > 0; $i--)
    { 
      echo '<div class="edit_container">';
      echo '<br /><h4>' . ull_format_datetime($hg[$i - 1]->getUpdatedAt()) . '</h4>' .
            __('Version ', null, 'common') . $i . ' - ' .
            __('by', null, 'common') . ' ' . $hg[$i - 1]->getUpdator() .
            '<br /><br />';
      echo '<table class="edit_table"><tbody>';
      echo $hg[$i - 1]->getForm();
      echo '</tbody></table>';
      echo '</div><br />';
    }
  }
?>