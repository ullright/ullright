<?php include_partial('ullTableTool/jQueryRequirements')?>

<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<?php if ($generator->getForm()->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  <?php echo $generator->getForm()->renderGlobalErrors() ?>
  </div>  
  <br /><br />
<?php endif; ?>


<?php 
  echo form_tag('ullVentory/edit?id=' . $doc->id, 
    array('id' => 'ull_ventory_form', 'name' => 'edit_form')) 
?>

<div class="edit_container">

<table class='edit_table'>
<tbody>

<?php foreach ($generator->getForm()->getWidgetSchema()->getPositions() as $column_name): ?>
    <?php if (in_array($column_name, array('ull_ventory_item_manufacturer_id', 'ull_ventory_item_model_id'))): ?>
      <tr>
        <td><?php echo $generator->getForm()->offsetGet($column_name)->renderLabel() ?></td>
        <td>
          <?php echo $generator->getForm()->offsetGet($column_name)->render() ?>
          <?php echo __('or create', null, 'common') ?>:
          <?php echo $generator->getForm()->offsetGet($column_name . '_create')->render() ?>
        </td>
        <td class="form_error"><?php echo $generator->getForm()->offsetGet($column_name)->renderError() ?></td>
      </tr>
    <?php elseif (in_array($column_name, array('ull_ventory_item_manufacturer_id_create', 'ull_ventory_item_model_id_create'))): ?>
      <?php continue ?>
    <?php else: ?>      
      <?php echo $generator->getForm()->offsetGet($column_name)->renderRow() ?>
    <?php endif ?>
<?php endforeach ?>

</tbody>
</table>


<br />

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
                __('Save and close', null, 'common'),
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
            array('name' => 'submit|action_slug=save_only', 'form_id' => 'ull_ventory_form', 'display_as_link' => true)
          ); 
        ?>
      </li>
      <li>
		    <?php if ($doc->id): ?>    
		      <?php 
		        echo link_to(
		          __('Delete', null, 'common'), 
		          'ullVentory/delete?id=' . $doc->id, 
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
  echo ull_js_observer("ull_ventory_form");
  use_javascript('/sfFormExtraPlugin/js/jquery.autocompleter.js');
  use_stylesheet('/sfFormExtraPlugin/css/jquery.autocompleter.css');

  echo javascript_tag('
// filter the item-model select box by the given item-manufacturer  
$("#fields_ull_ventory_item_manufacturer_id").bind("change", function(e)
  {
    $.getJSON("/ullVentory/itemModelsByManufacturer", 
      {ull_ventory_item_manufacturer_id: $("#fields_ull_ventory_item_manufacturer_id").attr("value")},
      function(data)
      {
        $("#fields_ull_ventory_item_model_id").empty();
        $("#fields_ull_ventory_item_model_id").append("<option></option");
        for (var i = 0; i < data.length; i++) 
        {
          $("#fields_ull_ventory_item_model_id").append("<option value=" + data[i].id + ">" + data[i].name + "</option");
        }
      }
    );

//    // ajax indicator
//    $.ajax({
//      beforeSend: function(){
//        $("#ajax_indicator").show();
//      },
//      complete: function(){
//        $("#ajax_indicator").hide();
//      }
//    });
  }
);  
  ');
?>
