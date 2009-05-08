<?php include_partial('ullTableTool/jQueryRequirements')?>

<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<?php if ($generator->getForm()->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  <?php echo $generator->getForm()->renderGlobalErrors() ?>
  </div>  
  <br /><br />
<?php endif; ?>


<?php 
  echo form_tag('ullVentory/edit?id=' . $doc->id, 
    array('id' => 'ull_ventory_form', 'name' => 'edit_form')) 
?>

<div class="edit_container">

<table class='edit_table'>
<tbody>

<?php echo $generator->getForm() ?>

</tbody>
</table>


<br />

  <div class='edit_action_buttons color_light_bg'>
    <h3><?php echo __('Actions', null, 'common')?></h3>
      <div class='edit_action_buttons_left'>

        <ul>

		      <li>
            <?php             
              echo ull_submit_tag(
                __('Save and show', null, 'common'),
                array('name' => 'submit|action_slug=save_show')
              );  
            ?>
	        </li>
          <li>
            <?php             
              echo ull_submit_tag(
                __('Save and close', null, 'common'),
                array('name' => 'submit|action_slug=save_close')
              );  
            ?>
          </li>

        </ul> 
  </div>    

  <div class='edit_action_buttons_right'>

    <ul>
      
      <li>
        <?php 
          echo ull_submit_tag(
            __('Save only', null, 'common'), 
            array('name' => 'submit|action_slug=save_only', 'form_id' => 'ull_ventory_form', 'display_as_link' => true)
          ); 
        ?>
      </li>
      <li>
		    <?php if ($doc->id): ?>    
		      <?php 
		        echo link_to(
		          __('Delete', null, 'common'), 
		          'ullVentory/delete?id=' . $doc->id, 
		          'confirm='.__('Are you sure?', null, 'common')
		          ); 
		      ?>
		    <?php endif; ?>
      </li>

	    </ul>
	
	  </div>
	
	  <div class="clear"></div>  
  </div>
</div>


</form>

<?php
  echo ull_js_observer("ull_ventory_form");
  use_javascript('/sfFormExtraPlugin/js/jquery.autocompleter.js');
  use_stylesheet('/sfFormExtraPlugin/css/jquery.autocompleter.css'); 
?>
