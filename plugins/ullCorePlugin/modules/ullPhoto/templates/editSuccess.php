<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<div class="edit_container">

<?php use_javascript('/ullCorePlugin/js/jq/jquery.imgareaselect.pack.js') ?>
<?php use_stylesheet('/ullCorePlugin/css/jquery.imgareaselect/imgareaselect-default.css')?>

<h3>
  <?php if ($user->exists()): ?>
    <?php echo __('Upload a new photo for %user%', array('%user%' => $user->display_name), 'ullCoreMessages') ?>
  <?php else: ?>
    <?php echo __('Upload new photos', null, 'ullCoreMessages') ?>
  <?php endif ?>
</h3>

<?php include_partial('ullTableTool/flash', array('name' => 'cleared')) ?>

<?php include_partial('ullTableTool/globalError', array('form' => $form)) ?>

<?php echo form_tag($form_uri, 'multipart=true id=edit_form'); ?>

<table class="edit_table">
  <tbody>
    <tr>
      <td class="label_column">
        <label><?php echo __('Photo', null, 'common') ?></label>
        <div><?php echo __('Click and drag on the image to select an area', null, 'ullCoreMessages') ?></div>
      </td>
      <td>
        <?php echo image_tag($photo, array('id' => 'ull_photo', 'height' => $display_height)) ?>      
      </td>
      <td></td>
    </tr>
    <?php echo $form ?>
  
  </tbody>  

</table>



<?php echo javascript_tag('
$(document).ready(function () 
{ 
  $("img#ull_photo").imgAreaSelect({ 
    handles: true,
    fadeSpeed: 200, 
    aspectRatio: "' . $cropAspectRatio . '",
    //minHeight: 200,
    //minWidth: 200,
    onSelectChange: preview
  }); 
}); 


function preview(img, selection) {
    if (!selection.width || !selection.height)
        return;
    
//    var scaleX = 100 / selection.width;
//    var scaleY = 100 / selection.height;
//
//    $("#preview img").css({
//        width: Math.round(scaleX * 300),
//        height: Math.round(scaleY * 300),
//        marginLeft: -Math.round(scaleX * selection.x1),
//        marginTop: -Math.round(scaleY * selection.y1)
//    });

    $("#fields_x1").val(selection.x1);
    $("#fields_y1").val(selection.y1);
//    $("#fields_x2").val(selection.x2);
//    $("#fields_y2").val(selection.y2);
    $("#fields_width").val(selection.width);
    $("#fields_height").val(selection.height);  

}
') ?>

<!--
<div id="preview" style="overflow: hidden; width: 100px; height: 100px;">
<?php echo image_tag($photo) ?>
</div>
-->

<div class='edit_action_buttons color_light_bg'>
  <h3><?php echo __('Actions', null, 'common')?></h3>
  
  <div class='edit_action_buttons_left'>
    <ul>
      <li>
        <?php 
          echo submit_tag(
            __('Rotate counterclockwise', null, 'ullCoreMessages'),
            array('name' => 'submit|action_slug=rotate_counterclockwise')            
          ) 
        ?>
        <?php 
          echo submit_tag(
            __('Flip', null, 'ullCoreMessages'),
            array('name' => 'submit|action_slug=flip')            
          ) 
        ?>  
        <?php 
          echo submit_tag(
            __('Rotate clockwise', null, 'ullCoreMessages'),
            array('name' => 'submit|action_slug=rotate_clockwise')            
          ) 
        ?>
      </li>
      <li>
        <?php 
          echo submit_tag(
            __('Crop', null, 'ullCoreMessages'),
            array('name' => 'submit|action_slug=crop')            
          ) 
        ?>
      </li>
      <li>
        <?php 
          echo submit_tag(
            __('Save photo', null, 'ullCoreMessages'),
            array('name' => 'submit|action_slug=save')            
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
            __('Revert changes', null, 'ullCoreMessages'), 
            array('name' => 'submit|action_slug=revert', 'form_id' => 'edit_form', 'display_as_link' => true)
          ); 
        ?>
      </li>
      <li>
        <?php 
          echo ull_submit_tag(
            __('Skip this photo', null, 'ullCoreMessages'), 
            array('name' => 'submit|action_slug=skip', 'form_id' => 'edit_form', 'display_as_link' => true)
          ); 
        ?>
      </li>
    </ul>
  </div>

  <div class="clear"></div>  
  
</div>

</div>

