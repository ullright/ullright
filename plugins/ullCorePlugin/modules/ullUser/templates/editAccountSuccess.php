<?php //echo $breadcrumb_tree ?>

<h2><?php echo __('Edit your account data', null, 'ullCoreMessages') ?></h2>

<?php include_partial('ullTableTool/globalError', array('form' => $generator->getForm())) ?>

<?php echo form_tag('ullUser/editAccount?username=' . $user->username, array('multipart' => 'true', 'id' => 'ull_tabletool_form')) ?>

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
              __('Save', null, 'common'),
              array('name' => 'submit|action_slug=save_exit')
            );  
          ?>
        </li>
    </ul>
  </div>

  <div class='edit_action_buttons_right'>
    <ul>
      <li>
        <?php             
          echo link_to(
            __('Set your status to inactive permanently', null, 'common'), 
            'ullUser/setInactive', 
            array('confirm' => __('Are you sure you want to set your status to inactive permanently?', null, 'common'))
          );  
        ?>
      </li>
    </ul>
  </div>

  <div class="clear"></div>  
  
</div>
</div>
</form>   

<?php echo ull_js_observer("ull_tabletool_form") ?>  

<?php use_javascripts_for_form($generator->getForm()) ?>
<?php use_stylesheets_for_form($generator->getForm()) ?>