<?php /* Set defaults */ ?>
<?php if (!isset($form_html_id)) { $form_html_id = 'ull_tabletool_form'; } ?>
<?php if (!isset($is_ajax)) { $is_ajax = false; } ?>

<?php echo $breadcrumb_tree ?>

<?php include_partial('ullTableTool/globalError', array('form' => $generator->getForm())) ?>

<?php echo form_tag($form_uri, array('multipart' => 'true', 'id' => $form_html_id)) ?>

<div class="edit_container">

<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>

<?php include_partial('ullTableTool/editTable', array('generator' => $generator)) ?>

<div class='edit_action_buttons color_light_bg'>
  <h3><?php echo __('Actions', null, 'common')?></h3>
  
  <div class='edit_action_buttons_left'>
    <?php if ($is_ajax): ?>
      <ul>
          <li>
            <script type="text/javascript">
//<![CDATA[
            $(document).ready(function()
              {
                $('input#edit_action_buttons_left_save_button').click(function()
                {
                  /* Prevent multiple submits */
                  
                  //is this the first time the button was clicked?
                  if ($(this).data().alreadyClicked)
                  {
                    //if not, cancel operation
                    return false;
                  }
                  else
                  {
                    //if yes, save the fact
                    $(this).data('alreadyClicked', true);
                  }

                  /* End of multi submit prevention */
                  
                  $.ajax({  
                    type: "POST",  
                    url: "<?php echo url_for($form_uri); ?>",  
                    data: $("#<?php echo $form_html_id ?>").serialize(), 
                    success: function(data) {
                      // A json response containing the id of the object means ok
                      try {
                        var json = jQuery.parseJSON(data);
                        // save object id for overlay creator (=original edit page) 
                        window.overlayId = json.id;
                        window.overlayString = json.string;
                        // save modified status for overlay creator to trigger widget reload
                        window.overlayIsModified = true;
                        $("#overlay").overlay().close();
                      }
                      // Otherwise a validation error occured.
                      // We got the normal html markup (form with error msgs) and
                      //   re-render it 
                      catch (e) {
                        var wrap = $("#overlay").overlay().getOverlay().find(".overlayContentWrap");
                        wrap.html(data);
                        wrap.scrollTop(0);
                      }  
                    }  
                  });
                  
                  return false;
                });
              });
//]]>
            </script>
            <input id="edit_action_buttons_left_save_button" type="button"
              value="<?php echo __('Save', null, 'common'); ?>" />
          </li>     
      </ul>      
    
    <?php else: ?>
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
    <?php endif // is_ajax ?>
  </div>

  <?php if (!$is_ajax): ?>
    <div class='edit_action_buttons_right'>
      <ul>
      
        <li>
          <?php
            echo ull_submit_tag(
              __('Save only', null, 'common'), 
              array('name' => 'submit|action_slug=save_only', 'form_id' => $form_html_id, 'display_as_link' => true)
            ); 
          ?>
        </li>    
      
        <li>
          <?php 
            echo ull_submit_tag(
              __('Save and new', null, 'common'), 
              array('name' => 'submit|action_slug=save_new', 'form_id' => $form_html_id, 'display_as_link' => true)
            ); 
          ?>
        </li>    
          <?php if (isset($edit_action_buttons)): ?>
            <?php  include_partial('ullTableTool/editActionButtonsRight', array('buttons' => $edit_action_buttons)) ?>
          <?php endif ?>
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
        
      </ul>
    </div>
  <?php endif // is_ajax? ?>
  

  <div class="clear"></div>  
  
</div> <!-- end of edit action buttons -->


</div>
</form>   

<?php 
  echo ull_js_observer($form_html_id);
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

<?php include_partial('ullTableTool/overlay') ?>

<?php echo hide_advanced_form_fields() ?>

<?php use_javascripts_for_form($generator->getForm()) ?>
<?php use_stylesheets_for_form($generator->getForm()) ?>