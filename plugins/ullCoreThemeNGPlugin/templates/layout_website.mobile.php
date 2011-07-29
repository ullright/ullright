<?php include_partial('global/html_head') ?>
<?php /* The statement above loads the html head */ ?>
<?php /* The file is located in apps/frontend/templates/_html_head.mobile.php */ ?>
  
  
<!--  Begin of html body -->
<?php /*  Adds a css selector for the current request (module + action name) 
  Example: <body class="ullCms_show"> */ ?>
<body class="<?php 
  echo sfInflector::underscore($sf_context->getModuleName()) . '_' . $sf_context->getActionName();
?>">

<!-- Top-level box containing all subsequent elements -->
<div id="container">

  <!-- Header -->
  <div id="header">
  
    <?php // Logo with link to homepage ?>
    <?php echo link_to('
      <img src="/ullCoreThemeNGPlugin/images/logo_120.png" alt="logo" align="left" id="logo"/>', 
      '@homepage'
    ) ?>
    
  
    <!-- A box on the right side containing e.g. the login link -->
    <div id="secondary_menu">
    
      <!-- A box containing "My Account" link and language selection -->
      <ul>
        <?php // Login link / login information ?>
        <?php include_component('ullUser', 'headerLogin'); ?>
        <?php // "my account" link ?>
        <?php include_component('ullUser', 'headerSyslinkMyAccount'); ?>
        <?php // Language selection ?>
        <?php include_component('ullUser', 'headerSyslinkLanguageSelectionGermanEnglish'); ?>
      </ul>
      
    <!-- end of secondary_menu -->  
    </div> 
    
    
    
    <!--  main menu -->
    <div id='main_menu'>
      <ul>
        <?php // Main menu ?>
        <?php include_component('ullCms', 'mainMenu', array('renderUlTag' => false))?>
      </ul>
    </div>
    
  <!-- End of header -->            
  </div> 
  
  
  <!-- Content box --> 
  <!-- The actual content of a page is rendered here. 
       Think of it like the picture in a picture frame -->
  <div id="content">
    <?php echo $sf_data->getRaw('sf_content') ?>
  </div>  
  

  <!-- Sidebar -->
  <div id="sidebar">
      
    <?php // Admin menu - only shown with the right permission ?>
    <?php if (UllUserTable::hasPermission('show_admin_menu')): ?>
      <?php include_component('ullCms', 'renderSidebarMenu', array('slug' => 'admin-menu'))?>
    <?php endif ?>

    <?php // Submenu of the main menu - only shown when we have subpages ?>          
    <?php if (strstr(get_slot('sidebar'), '<li')): // We expect the side menu here. If we have no <li> entries we consider it empty ?>
      <div class="sidebar_block">
        <h1><?php echo __('Menu', null, 'common') ?></h1>
        <?php include_slot('sidebar') ?>
      </div>
    <?php endif ?>
    
    <?php // Sidebar blocks ?>  
    <?php include_component('ullCms', 'renderSidebarBlocks', array('slug' => 'sidebar-blocks'))?>
      
  <!-- End of sidebar -->      
  </div> 
   
  
  <!-- Footer --> 
  <div id="footer">

    <div id="footer_left">
      <ul>
        <?php include_component('ullCms', 'footerMenu', array('renderUlTag' => false))?>
      </ul>
    </div>
  
    <div id="footer_right">
      <ul>
        <li>
          © 2007-<?php echo date('Y')?> 
          by <?php echo ull_link_to('ull.at', 'http://www.ull.at', 'ull_js_observer_confirm=true link_external=true')?>
        </li>
        
        <li>
          Powered by <?php echo link_to('ullright', 'http://www.ullright.org', 'target=_blank class=link_external') ?>
        </li>
      </ul>
    </div>
  
  <!-- End of footer -->  
  </div>  

<!-- End of container -->    
</div> 


<!--  End of html body -->
</body>

</html>