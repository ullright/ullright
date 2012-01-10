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
        <?php if (isset($edit_action_buttons)): ?>
          <?php  include_partial('ullTableTool/editActionButtonsRight', array('buttons' => $edit_action_buttons)) ?>
        <?php endif ?>
        
    <?php if ($generator->getRow()->exists()): ?>    
      <?php if ($generator->getAllowDelete() && isset($table_name)): ?>
        <li>
          <?php echo link_to(
	              __('Delete', null, 'common')
	              , 'ullTableTool/delete?table=' . $table_name . '&' . $generator->getIdentifierUrlParams(0)
	              , 'confirm='.__('Are you sure?', null, 'common')
	              ) ?>
        </li>            
      <?php endif ?>
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

<?php include_partial('ullTableTool/history', array(
  'generator' => $generator
))?>

<!-- auto fill in menu title -->
<script type="text/javascript">
  //<![CDATA[
  $(document).ready(function() {
	  <?php foreach ($cultures as $culture): ?>
      $("#fields_title_translation_<?php echo $culture ?>").blur(function() {
        //$("#fields_name_translation_<?php echo $culture ?>").length != 0 &&
        
        if ($("#fields_name_translation_<?php echo $culture ?>").length != 0 &&
          $("#fields_name_translation_<?php echo $culture ?>").val() == ""
        ){
          $("#fields_name_translation_<?php echo $culture ?>").val(
          	$("#fields_title_translation_<?php echo $culture ?>").val()
        	);
        }
      });
  <?php endforeach ?>
  });
  //]]>
</script>

<?php echo hide_advanced_form_fields() ?>

<?php use_javascripts_for_form($generator->getForm()) ?>
<?php use_stylesheets_for_form($generator->getForm()) ?>