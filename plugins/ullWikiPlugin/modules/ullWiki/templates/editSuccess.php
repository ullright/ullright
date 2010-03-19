<?php echo $breadcrumb_tree ?>

<?php if ($generator->getForm()->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  <?php echo $generator->getForm()->renderGlobalErrors() ?>
  </div>  
  <br /><br />
<?php endif; ?>


<?php 
  echo form_tag('ullWiki/edit?docid=' . $doc->id, 
    array('id' => 'ull_wiki_form', 'name' => 'edit_form')) 
?>

<?php echo input_hidden_tag('return_var', $return_var); ?>

<div class="edit_container">

<table class='edit_table'>
<tbody>

<?php echo $generator->getForm() ?>

</tbody>
</table>


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
                __('Save and return to list', null, 'common'),
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
            array('name' => 'submit|action_slug=save_only', 'form_id' => 'ull_wiki_form', 'display_as_link' => true)
          ); 
        ?>
      </li>
      
      <li>
        <?php 
          echo ull_submit_tag(
            __('Save and new', null, 'common'), 
            array('name' => 'submit|action_slug=save_new', 'form_id' => 'ull_wiki_form', 'display_as_link' => true)
          ); 
        ?>
      </li>       
      
      <li>
		    <?php if ($doc->id): ?>    
		      <?php 
		        echo link_to(
		          __('Delete', null, 'common'), 
		          'ullWiki/delete?docid='.$doc->id, 
		          'confirm='.__('Are you sure?', null, 'common')
		          ); 
		      ?>
		    <?php endif; ?>
      </li>

	    </ul>
	
	  </div>
	
	<!--   <div class="clear"></div>  -->
  </div>
</div>


</form>

<?php echo ull_js_observer("ull_wiki_form") ?>

<?php use_javascript('/ullCorePlugin/js/fckeditor/fckeditor.js') ?>
