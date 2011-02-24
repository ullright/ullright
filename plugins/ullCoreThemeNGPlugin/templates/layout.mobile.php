<?php /* Statements to be added to the html head */ ?>
<?php slot('html_head') ?>

  <?php /* Include rss feed (Uncomment to activate */ ?>
  <?php //include_component('ullNews', 'rssFeed') ?>
  
  <?php /* Add custom stylesheet */ ?>
  <?php /* Located in web/css/ */ ?>
  <?php use_stylesheet('custom.mobile.css', 'last', array('media' => 'all')) ?>  
  
  <?php /* Add classic ullright layout */ ?>
  <?php use_stylesheet('/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') . 
    'Plugin/css/layout_ullright.mobile.css', 'last', array('media' => 'all')) ?>
  
  <?php /* Add custom javascripts (Uncomment to activate) */?>
  <?php /* Put files in web/js/ */?>
  <?php //use_javascript('/js/my_javascript.js') ?>
  
<?php end_slot() ?>


<?php /* Actually include the html head */ ?> 
<?php /* The default head is located in plugins/ullCoreThemeNGPlugin/templates/_head.php */ ?>
<?php require(sfConfig::get('sf_plugins_dir') . 
  '/ullCoreTheme' .
  sfConfig::get('app_theme_package', 'NG') .
  'Plugin/templates/_head.php') ?>



<!--  Begin of html body -->
<?php /*  Adds a css selector for the current module + action 
  Example: <body class="ullCms_show"> */ ?>
<body class="<?php 
  echo sfInflector::underscore($sf_context->getModuleName()) . '_' . $sf_context->getActionName();
?>">

<!-- Top-level box containing all subsequent elements -->
<div id="container">
  
  <!-- Navigation -->  
  <div id="nav_top">
    
    <!-- Logo -->
    <span id="nav_logo">
        <?php echo ull_link_to(
      image_tag(sfConfig::get('app_sidebar_logo',
        '/ullCoreThemeNGPlugin/images/logo_120'), 'alt="logo"')
        , '@homepage'
        , 'ull_js_observer_confirm=true'
      ); ?>
    </span>

    <!-- Login, "My Account", langugage selection -->
    <ul class="nav_syslinks_list">
      <?php include_component('ullUser', 'headerLogin') ?>
      
      <?php include_component('ullUser', 'headerSyslinkMyAccount') ?>
          
      <?php include_component('ullUser', 'headerSyslinkLanguageSelectionGermanEnglish') ?>        
    </ul>
    
    <!-- Main navigation links (ullright modules) -->
    <div id='nav_links'>             
      <?php include_partial('myModule/navTopLinks'); ?>
    </div>
    
    <!-- Example for an ullCms main menu (Uncomment to activate) -->
    <?php /* Uncomment to activate */ ?>
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
  <div id="content">
    <?php echo $sf_data->getRaw('sf_content') ?>
  </div> <!-- end of content -->
  
   
  <!-- Sidebar box -->     
  <div id="sidebar">
    <?php include_partial('default/sidebar_inclusion') ?>
  </div> 
  
  
  <!-- Footer -->
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
    
  <!-- End of footer -->  
  </div>

<!-- End of container -->    
</div> 

<!--  End of html body -->
</body>

</html>