<?php require_once sfConfig::get('sf_plugins_dir') . 
  '/ullCorePlugin/modules/ullTableTool/templates/editSuccess.php' ?>


<?php if (! $generator->getRow()->exists()) :?>
  <?php echo javascript_tag('
  
    /**
     * Ajax check to avoid duplicate user entries
     */
  
    $(document).ready(function()
    {
      $("#fields_last_name").blur(function () {
        
        $.ajax({
          url: "' . url_for('ullUser/checkUserExists') . '",
          type: "POST",
          data: { "first_name" : $("#fields_first_name").val(), "last_name" : $("#fields_last_name").val() },
          cache: false,
          success: function(data, textStatus, XMLHttpRequest)
          {
            var selector = "#fields_last_name";
            $(selector).after(data);
          },
          error: function(XMLHttpRequest, textStatus, errorThrown)
          {
            $("div.pager_top").html("Error: Ajax check user exists failed...");
          }
        }); //end of ajax        
        
      });
      
      
    
    });
  ') ?>
<?php endif ?>
