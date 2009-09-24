<?php require_once(dirname(__FILE__) . '/_head.php') ?>

<body>

<div id="container">

	<div id="sidebar">

    <div id="sidebar_logo">
      <?php echo ull_link_to(
                  image_tag('/ullCoreThemeNGPlugin/images/logo_120', 'alt="logo"')
                , '@homepage'
                , 'ull_js_observer_confirm=true'
                ); ?> 
    </div>   
        
		<div id="sidebar_content">
		<?php //the sidebar content can be overridden
        if (has_slot('sidebar'))
        { 
          include_slot('sidebar');
        }
        else
		    {
		      include_component('default', 'sidebar');
		      $partialPath = sfConfig::get('sf_app_dir') . '/modules/myModule/templates/_custom_sidebar.php';
          if (file_exists($partialPath))
          {
		        include_partial('myModule/custom_sidebar');
          }
        }
     ?>
		</div>
		
	</div> <!-- end of sidebar -->
	
	
	<!-- Contains main navigation, content and footer -->
	<div id="canvas">
	
    <div id="nav_top">

      <div id='nav_links'>             
        <?php include_partial('myModule/navTopLinks'); ?>
      </div> 
      
      <div id="nav_syslinks_container">
      
        <div id="nav_langlinks">      
          <?php
            $culture = $sf_user->getCulture();
            $language = substr($culture,0,2);
            if ($language <> 'en') 
            {
              echo ull_link_to('English', 'ullUser/changeCulture?culture=en', 'ull_js_observer_confirm=true');
            }
            if ($language <> 'de') 
            {
              echo ull_link_to('Deutsch', 'ullUser/changeCulture?culture=de', 'ull_js_observer_confirm=true');
            }
          ?>
        </div>   
        
        <!-- 
        <div id="nav_searchbox">
          <?php //include_component('ullWiki', 'headerSearch'); ?>
        </div> 
        -->
          
        <div id="nav_loginbox">
          <?php include_component('ullUser', 'headerLogin'); ?>
        </div>        
        
      </div> <!-- end of nav_syslinks_container -->
      
      <div id="nav_top_separator_line"></div>      
   
      <div class='clear_right'></div><!-- to force the parent-box to enclose the floating divs -->
            
    </div> <!-- end of nav_top -->
     
     
    <div id="content">
      <div id="ajax_indicator" style="display:none;">
        <?php echo image_tag('/ullCoreThemeNGPlugin/images/indicator.gif') ?>
      </div>
    
      <?php //var_dump(sfContext::getInstance()->getUser()->getAttributeHolder()->getAll()) ?>
      <?php //@var_dump($_SERVER['HTTP_REFERER']) ?>
      
      <?php echo $sf_data->getRaw('sf_content') ?>
      <?php //phpinfo() ?>
    </div> <!-- end of content -->
     
     
    <div id="footer">
      <div id="footer_copyright">© 2007-2009 by Klemens Ullmann</div>
      <div id="footer_links">powered by <?php echo ull_link_to('ull.at', 'http://www.ull.at', 'ull_js_observer_confirm=true'); ?></div>
      <!-- <div class='clear'></div> --> 
    </div>  <!-- end of footer -->
    
    
  </div> <!-- end of canavas -->
  
  
</div> <!--  end of container -->

</body>

</html>