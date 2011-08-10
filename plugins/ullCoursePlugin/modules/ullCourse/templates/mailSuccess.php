<?php //echo $breadcrumb_tree ?>

<h1><?php echo __('Send email to participants of course "%course%"', array('%course%' => $course->name), 'ullCourseMessages') ?></h1>

<?php include_partial('ullTableTool/globalError', array('form' => $form)) ?>

<?php echo form_tag('ullCourse/mail?slug=' . $course['slug'], array('id' => 'ull_course_mail_form')) ?>

<div class="edit_container">

<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>

<table class="edit_table">
<tbody>

  <?php echo $form ?>

</tbody>
</table>

<dfn><?php echo __('Fields marked with * are mandatory', null, 'ullCoreMessages')?></dfn>


<?php use_javascript('/ullCorePlugin/js/jq/jquery-min.js') ?>

<?php echo javascript_tag('
  $("table.edit_table :input:visible:enabled:first").focus();
  
  markErrorFormFields();
')?>


<div class='edit_action_buttons color_light_bg'>
  <h3><?php echo __('Actions', null, 'common')?></h3>
  
  <div class='edit_action_buttons_left'>
    <ul>
    
        <li>
          <?php             
            echo ull_submit_tag(
              __('Send', null, 'common'),
              array('name' => 'submit|action_slug=send')
            ) 
          ?>
        </li>
        
    </ul>
  </div>

  <div class='edit_action_buttons_right'>
    <ul>
    
      <li>
        <?php
          echo ull_submit_tag(
            __('Cancel', null, 'common'), 
            array(
              'name' => 'submit|action_slug=cancel', 
              'form_id' => 'ull_course_cancel_form', 
              'display_as_link' => true
            )
          ) 
        ?>
      </li>    
      
    </ul>
  </div>
  

  <div class="clear"></div>  
  
</div> <!-- end of edit action buttons -->


</div>
</form>   

<?php use_javascripts_for_form($form) ?>
<?php use_stylesheets_for_form($form) ?>