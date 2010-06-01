<?php require('_head.php') ?>

<body>

<div id="container">

  <div id="sidebar">
    
    <div id="sidebar_logo">
    <?php echo ull_link_to(
          image_tag(sfConfig::get('app_sidebar_logo',
            '/ullCoreThemeNGPlugin/images/logo_120'), 'alt="logo"')
            , '@homepage'
            , 'ull_js_observer_confirm=true'
          ); ?>
    </div>
   
    <?php include_partial('default/sidebar_inclusion') ?>
   
  </div> <!-- end of sidebar -->
  
  <!-- Contains main navigation, content and footer -->
  <div id="canvas">
  
    <div id="nav_top">
  
      <div id='nav_links'>             
        <?php include_partial('myModule/navTopLinks'); ?>
      </div> 
      
      <div id="nav_syslinks_container">
      
        <div id="nav_syslinks">
          <ul class="nav_syslinks_list">
          
            <?php include_component('ullUser', 'headerSyslinkMyAccount'); ?>
            
            <?php include_component('ullUser', 'headerSyslinkLanguageSelectionGermanEnglish'); ?>
            
          </ul>
        </div>   
        
        <!-- 
        <div id="nav_searchbox">
          <?php //include_component('ullWiki', 'headerSearch'); ?>
        </div> 
        -->
          
        <div id="nav_loginbox">
          <ul class="nav_syslinks_list">
            <?php include_component('ullUser', 'headerLogin'); ?>
          </ul>
        </div>        
        
      </div> <!-- end of nav_syslinks_container -->
      
      <div id="nav_top_separator_line"></div>      
   
      <div class='clear_right'></div><!-- to force the parent-box to enclose the floating divs -->
            
    </div> <!-- end of nav_top -->
     
     
    <div id="content">
      <?php echo $sf_data->getRaw('sf_content') ?>
    </div> <!-- end of content -->
     
     
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

<?php if (sfConfig::get('app_sidebar_toggle', false) == true) : ?>
  <?php include_partial('default/sidebar_toggle') ?>
<?php endif; ?>

</body>

</html>