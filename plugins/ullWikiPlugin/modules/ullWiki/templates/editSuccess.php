<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<?php if ($generator->getForm()->getErrorSchema()->getErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  </div>  
  <?php echo $generator->getForm()->renderGlobalErrors(); ?>
  <br /><br />
<?php endif; ?>


<?php 
  echo form_tag('ullWiki/edit?docid=' . $doc->id, 
    array('id' => 'ull_wiki_form', 'name' => 'edit_form')) 
?>

<?php echo input_hidden_tag('return_var', $return_var); ?>

<div class="edit_main">

<table class='ull_wiki_edit'>
<tbody>

<?php echo $generator->getForm() ?>

</tbody>
</table>
</div>

<br />

<div id="action_buttons_edit_main">
  <div class='action_buttons_edit color_light_bg'>
    <h3><?php echo __('Actions', null, 'common')?></h3>
      <div class='action_buttons_edit_left'>

        <ul>

		      <li>
	         <?php echo submit_tag(__('Save and show', null, 'common'),
	             array('name' => 'submit_save_show')) ?>
	        </li>
          <li>
          <?php echo submit_tag(__('Save and close', null, 'common'),
               array('name' => 'submit_save_close')) ?>
          </li>

        </ul> 
  </div>    

  <div class='action_buttons_edit_right'>

    <ul>

      <li>
        <?php echo ull_submit_tag(__('Save only', null, 'common'), array('name' => 'submit_save_only', 'form_id' => 'ull_wiki_form', 'display_as_link' => true)); ?>
      </li>

      <li>
		    <?php // TODO: check why there's an exception thrown when creating an entry (KU)
          /* echo ull_link_to(
            __('Cancel', null, 'common') 
            , $refererHandler->getReferer('edit')
            , 'ull_js_observer_confirm=true'
          ); */
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
	
	  <div class="clear"></div>  
  </div>
</div>


</form>

<?php
  echo ull_js_observer("ull_wiki_form");
//  ullCoreTools::printR($ull_form);
?>
