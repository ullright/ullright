<?php echo $breadcrumb_tree ?>

<?php if ($generator->getForm()->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  <?php echo $generator->getForm()->renderGlobalErrors() ?>
  </div>
  <br /><br />
<?php endif; ?>
<div class="ull_wiki_autosave_status">
  <div class="ull_wiki_autosave_status_ok">
    <?php echo __('Saved', null, 'common') ?>
  </div>
  <div class="ull_wiki_autosave_status_saving">
    <?php echo __('Auto saving ...', null, 'common') ?>
    <?php echo image_tag('/ullCoreThemeNGPlugin/images/indicator.gif') ?>
  </div>
  <div class="ull_wiki_autosave_status_fail">
    <?php echo __('Auto save not possible because one or more fields are incorrect.', null, 'common') ?>
    <br />
    <input type="button" value=" <?php echo __('Show errors', null, 'common') ?> " onclick='$("#ull_wiki_form").submit();'>
  </div>
</div>
<?php 
  echo form_tag('ullWiki/edit?slug=' . $doc->slug, 
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
        <div id="ull_wiki_autosave_notice">
          <?php echo __('(Please click once to enable auto save)', null, 'common') ?>
        </div>
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

<?php echo javascript_tag('

  $(document).ready(function() 
  {
    // disable ajax autosave in create mode or having validation errors
    if (("' . $doc->id .'") && ( ! ($("#content > .form_error").length > 0)))
    {
      // Temp. disabled because of  http://www.ullright.org/ullFlow/edit/doc/1482
      setInterval("autoSaveWikiAjax()", 60000);
    }
    else
    {
      // Temp. disabled because of  http://www.ullright.org/ullFlow/edit/doc/1482
      $("#ull_wiki_autosave_notice").show();
    }
  }); 
  

  function autoSaveWikiAjax() 
  {
    // Perform ajax autosave only if form content was modified
    if (ull_js_observer_detect_change())
    {
      $(".ull_wiki_autosave_status div").hide();
      $(".ull_wiki_autosave_status_saving").fadeIn(400);
      
      //saves the current content of the FCKEditor
      document.getElementById("fields_body").value = FCKeditorAPI.GetInstance("fields_body").GetHTML(true);
      
      //get the form data
      var formData = $("#ull_wiki_form").serializeArray();
      
      //since serialize() seems to do strange things to newlines (in Firefox)
      //retrieve its raw value, normalize new lines and 
      var docContent =  document.getElementById("fields_body").value;
      docContent.replace(/(\r\n|\r|\n)/g, \'\n\');
      
      //replace the serialized body with the fixed value
      for (var i = 0; i < formData.length; i++)
      {
				if (formData[i].name == "fields[body]")
				{
					formData[i].value = docContent;
				}
			}
			
      $.ajax({
        url: "' . url_for('ullWiki/edit?docid=' . $doc->id . '') . '",
        type: "POST",
        data: formData,
        cache: false,
        success: function(data, textStatus, XMLHttpRequest)
        {
          ull_js_observer_update_initial_state();      
        
          var selector = ".ull_wiki_autosave_status_ok";
          
           $(selector).fadeIn(
            400, 
            function(){$(".ull_wiki_autosave_status_saving").fadeOut(400);}
           );
          
          setTimeout(
            function(){
              $(selector).fadeOut(400)
            },
            2500
          );
        },
        
        error: function(XMLHttpRequest, textStatus, errorThrown)
        {
          $(".ull_wiki_autosave_status_saving").fadeOut(100);
          $(".ull_wiki_autosave_status_fail").fadeIn(400);
        }
      }); //end of ajax    
    }
  }
')?>

<?php use_javascripts_for_form($generator->getForm()) ?>
<?php use_stylesheets_for_form($generator->getForm()) ?>