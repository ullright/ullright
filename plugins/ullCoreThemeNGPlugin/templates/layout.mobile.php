<?php use_javascript('/ullCorePlugin/js/jq/jquery-min.js') ?>

<?php require('_head.mobile.php') ?>

<body class="<?php 
  echo sfInflector::underscore($sf_context->getModuleName()) . '_' . $sf_context->getActionName();
?>">

<div id="container">

  
  <!-- Contains main navigation, content and footer -->
  <div id="canvas">
  
    <div id="nav_top">
    
      <span id="nav_logo">
        <?php //echo link_to(image_tag('/ullCoreThemeNGPlugin/images/logo_75'), '@homepage') ?>
          <?php echo ull_link_to(
        image_tag(sfConfig::get('app_sidebar_logo',
          '/ullCoreThemeNGPlugin/images/logo_120'), 'alt="logo"')
          , '@homepage'
          , 'ull_js_observer_confirm=true'
        ); ?>
      </span>
  
<!--      <div id="nav_syslinks_container">-->
      
        <ul class="nav_syslinks_list">
          <?php include_component('ullUser', 'headerLogin') ?>
          
          <?php include_component('ullUser', 'headerSyslinkMyAccount') ?>
              
          <?php include_component('ullUser', 'headerSyslinkLanguageSelectionGermanEnglish') ?>        
        </ul>
        
<!--      </div>  end of nav_syslinks_container -->
      
      
      <div id='nav_links'>             
        <?php include_partial('myModule/navTopLinks'); ?>
      </div>
      <div class='nav_main_menu'>
        <ul class="ull_menu_main_menu">
          <?php include_component('ullCms', 'mainMenu', array('renderUlTag' => false))?>
        </ul>
      </div>
      
    </div> <!-- end of nav_top -->
     
     
    <div id="content">
      <?php echo $sf_data->getRaw('sf_content') ?>
    </div> <!-- end of content -->
     
    <div id="sidebar">
      <?php include_partial('default/sidebar_inclusion') ?>
    </div> 
     
    <div id="footer">
      <div id="footer_copyright">
        © 2007-<?php echo date('Y')?> 
        by <a href="mailto:klemens.ullmann-marx@ull.at">Klemens Ullmann-Marx</a>
        / <?php echo ull_link_to('ull.at', 'http://www.ull.at', 'ull_js_observer_confirm=true link_external=true')?>
      </div>
      <div id="footer_links">
        Powered by the enterprise 2.0 platform 
        <?php echo ull_link_to(
          'ullright', 
          'http://www.ullright.org', 
          'ull_js_observer_confirm=true link_external=true'
        ) ?>
        |
        <?php echo ull_link_to(
          __('About', null, 'ullCoreMessages'), 
          'ullAdmin/about', 
          'ull_js_observer_confirm=true'
        ) ?>
      </div>
      <!-- <div class='clear'></div> --> 
    </div>  <!-- end of footer -->
    
  </div> <!-- end of canavas -->
    
</div> <!--  end of container -->

</body>

</html>