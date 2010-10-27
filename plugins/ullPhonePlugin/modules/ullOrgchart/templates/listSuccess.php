<?php echo $breadcrumb_tree ?>

    <ul class='list_action_buttons color_light_bg'>
        <li><?php echo ull_link_to(__('Reset', null, 'common'), 'ullOrgchart/list') ?></li>
        
        <li>
          <?php echo __('Number of displayed levels', null, 'ullOrgchartMessages') ?>:
          <?php echo ull_link_to('2', array('depth' => 2)) ?>  
          <?php echo ull_link_to('3', array('depth' => 3)) ?>
          <?php echo ull_link_to('4', array('depth' => 4)) ?>
          <?php echo ull_link_to(__('All', null, 'common'), array('depth' => 9999)) ?>
        </li>
        <p style="margin:0">Zoom:</p>
        <div id='ull_orgchart_zoom_slider'></div>
        
        
        <!--
        <li>
         <?php /* echo $filter_form['search']->renderLabel() ?>    
         <?php echo $filter_form['search']->render() ?>
         <?php echo submit_image_tag(ull_image_path('search'),
                  array('alt' => 'search_list', 'class' => 'image_align_middle_no_border')) */?>     
        </li>
         -->
    </ul>

    


<?php echo $tree ?>

<?php
      use_stylesheet('/ullCorePlugin/css/jqui/jquery-ui.css', 'last');
      
      echo javascript_tag('
      
      $("#ull_orgchart_zoom_slider").slider({
        slide: function(event, ui) { }
      });

      $("#ull_orgchart_zoom_slider").bind( "slide", function(event, ui) {
          $("div.ull_orgchart").css("font-size", ui.value + "em");
      });

      

      $(function() {
        $("#ull_orgchart_zoom_slider").slider({min: 0.2, max: 1.5, step: 0.05, value: 1});
      });
      
      
    ');
    ?>