$(document).ready(function()
{
	//filter the item-model select box by the given item-manufacturer  
	$("#fields_ull_ventory_item_manufacturer_id").bind(
	  "change", 
	  function(e)
	  {
			$("#ull_ventory_item_model_id_ajax_indicator").show();
			
	    $.getJSON(
	      "/ullVentory/itemModelsByManufacturer", 
		    {
		      ull_ventory_item_manufacturer_id: $("#fields_ull_ventory_item_manufacturer_id").attr("value"),
		      ull_ventory_item_type_id: $("#fields_ull_ventory_item_type_id").attr("value")
		    },
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
	    
	    $("#ull_ventory_item_model_id_ajax_indicator").hide();
	  }
  );  
	
	
	// Load attribute presets upon model select
	$("#load_presets").hide();
	
	$("#fields_ull_ventory_item_model_id").bind("change", function(e)
	  {
	    $("#ull_ventory_form").append("<input type=\"hidden\" name=\"submit|action_slug=load_presets\" value=\"1\" />\n");
	    $("#ull_ventory_form").submit();
	  }
	);
	
	
	// Hide "Add software" button and add auto submit
	$("#add_software").hide();
	
	$("#fields_add_software").bind("change", function(e)
	  {
	    $("#submit_action_slug_save_only").attr("value", 1);
	    $("#ull_ventory_form").submit();
	  }
	);
	
});