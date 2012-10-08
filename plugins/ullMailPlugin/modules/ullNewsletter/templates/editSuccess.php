<?php echo $breadcrumb_tree ?>

<?php include_partial('ullTableTool/globalError', array('form' => $generator->getForm())) ?>

<?php echo form_tag($form_uri, array('multipart' => 'true', 'id' => 'ull_tabletool_form')) ?>

<div class="edit_container">

<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>

<?php include_partial('ullTableTool/editTable', array('generator' => $generator)) ?>

<div class='edit_action_buttons color_light_bg'>
  <h3><?php echo __('Actions', null, 'common')?></h3>
  
  <div class='edit_action_buttons_left'>
    <ul>
    
        <li>
          <?php             
            echo ull_submit_tag(
              __('Send test newsletter to myself', null, 'ullMailMessages'),
              array('name' => 'submit|action_slug=send_test')
            );  
          ?>
        </li>
        
        
        <li>
          <?php if (!$already_sent) :?>
            <?php             
              echo ull_submit_tag(
                __('Send newsletter', null, 'ullMailMessages'),
                array('name' => 'submit|action_slug=send', 'confirm' => __('Are you sure?', null, 'common'))
              );  
            ?>
          <?php else: ?>
            <?php echo __('This newsletter has already been sent', null, 'ullMailMessages') ?><br />
            <?php echo link_to(__('Copy the content of this newsletter into a new one', null, 'ullMailMessages'), 
              'ullNewsletter/create?clone=' . $generator->getRow()->id) ?> 
          <?php endif ?>
        </li>
        
        
    </ul>
  </div>

  <?php if (!$is_ajax): ?>
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
              __('Save and show', null, 'common'), 
              array('name' => 'submit|action_slug=save_show', 'form_id' => 'ull_tabletool_form', 'display_as_link' => true)
            ); 
          ?>
        </li>
                
        <li>
          <?php             
            echo ull_submit_tag(
              __('Save and return to list', null, 'common'),
              array('name' => 'submit|action_slug=save_close', 'form_id' => 'ull_tabletool_form', 'display_as_link' => true)
            );  
          ?>
        </li>        
        
      </ul>
    </div>
  <?php endif // is_ajax? ?>
  

  <div class="clear"></div>  
  
</div> <!-- end of edit action buttons -->


</div>
</form>   

<?php echo ull_js_observer("ull_tabletool_form") ?>  
<?php echo hide_advanced_form_fields() ?>

<?php use_javascripts_for_form($generator->getForm()) ?>
<?php use_stylesheets_for_form($generator->getForm()) ?>