<!-- Define statements to be added to the html head  -->
<?php slot('html_head') ?>

  <!-- Include rss feed (Uncomment to activate) -->
  <?php //include_component('ullNews', 'rssFeed') ?>

  <!-- Add a global custom stylesheet (Uncomment to activate) -->
  <!-- Put the file in web/css/ -->
  <?php //use_stylesheet('/css/my_stylesheet.css', 'last', array('media' => 'all')) ?>
  
  <!-- Add a global custom javascript (Uncomment to activate) -->
  <!-- Put the file in web/js/ -->
  <?php //use_javascript('/js/my_javascript.js') ?>
  
<?php end_slot() ?>


<!-- Actually include the html head --> 
<?php require(sfConfig::get('sf_plugins_dir') . 
  '/ullCoreTheme' .
  sfConfig::get('app_theme_package', 'NG') .
  'Plugin/templates/_head.php') ?>

<!--  Begin of html body including css selectors for the current page -->
<!--  Example: <body class="ullCms_show"> -->
<body class="<?php 
  echo sfInflector::underscore($sf_context->getModuleName()) . '_' . $sf_context->getActionName();
?>">

<!-- The normal html markup starts here. This can be completely customized --> 

<!-- Top-level box containing all subsequent elements -->
<div id="container">

  <!-- Sidebar box -->
  <div id="sidebar">
    
    <div id="sidebar_logo">
      <?php echo ull_link_to(
        image_tag(sfConfig::get('app_sidebar_logo',
          '/ullCoreThemeNGPlugin/images/logo_120'), 'alt="logo"')
          , '@homepage'
          , 'ull_js_observer_confirm=true'
        ) ?>
    </div>
   
    <?php include_partial('default/sidebar_inclusion') ?>
  
  <!-- End of sidebar --> 
  </div> 
  
  <!-- The canvas contains main navigation, content and footer -->
  <!-- So basically it's everything except the sidebar -->
  <div id="canvas">
  
    <!-- Navigation -->
    <div id="nav_top">
  
      <!-- Main navigation links (ullright module icons) -->
      <div id='nav_links'>             
        <?php include_partial('myModule/navTopLinks'); ?>
      </div> 
      
      <!-- A box on the right side containing e.g. the login link -->
      <div id="nav_syslinks_container">
      
        <!-- A box containing "My Account" link and language selection -->
        <div id="nav_syslinks">
          <ul class="nav_syslinks_list">
            <?php include_component('ullUser', 'headerSyslinkMyAccount'); ?>
            <?php include_component('ullUser', 'headerSyslinkLanguageSelectionGermanEnglish'); ?>
          </ul>
        </div>   
        
        <!-- A box containing the login link -->
        <div id="nav_loginbox">
          <ul class="nav_syslinks_list">
            <?php include_component('ullUser', 'headerLogin'); ?>
          </ul>
        </div>        
      
      <!-- End of nav_syslinks_container -->  
      </div> 
      
      
      <div id="nav_top_separator_line"></div>      
   
      <!-- Force the parent-box ("nav_top") to enclose the floating divs -->
      <div class='clear_right'></div>
      
      <!-- Example for an ullCms main menu (Uncomment to activate) -->
      <!-- 
      <div class='nav_main_menu'>
        <ul class="ull_menu_main_menu">
          <?php //include_component('ullCms', 'mainMenu', array('renderUlTag' => false))?>
        </ul>
      </div>
      -->
            
    <!-- End of navigation -->            
    </div> 
     
    
    <!-- Content box --> 
    <!-- The actual content of a page is rendered here. 
         Think of it like the picture in a picture frame -->
    <div id="content">
      <?php echo $sf_data->getRaw('sf_content') ?>
    </div> 
     
    <!-- Footer --> 
    <div id="footer">
    
      <div id="footer_copyright">
        © 2007-<?php echo date('Y')?> 
        by <?php echo ull_link_to('ull.at', 'http://www.ull.at', 'ull_js_observer_confirm=true link_external=true')?>
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
    
    <!-- End of footer -->  
    </div>  
  
  <!-- End of canavas -->  
  </div> 

<!-- End of container -->    
</div> 


<!-- Sidebar hide / unhide code -->
<?php if (sfConfig::get('app_sidebar_toggle', true) == true) : ?>
  <?php include_partial('default/sidebar_toggle') ?>
<?php endif; ?>

<!--  End of html body -->
</body>

</html>