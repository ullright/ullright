<?php //echo $breadcrumb_tree ?>

<h2><?php echo __('Please enter your data to register', null, 'ullCoreMessages') ?></h2>

<?php include_partial('ullTableTool/globalError', array('form' => $generator->getForm())) ?>

<?php echo form_tag('ullUser/register', array('multipart' => 'true', 'id' => 'ull_tabletool_form')) ?>

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
              __('Register', null, 'ullCoreMessages'),
              array('name' => 'submit|action_slug=register')
            );  
          ?>
        </li>
    </ul>
  </div>

  <div class='edit_action_buttons_right'>
  </div>

  <div class="clear"></div>  
  
</div>
</div>
</form>   

<?php 
  echo ull_js_observer("ull_tabletool_form");
?>  