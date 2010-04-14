<?php require_once(dirname(__FILE__) . '/_head.php') ?>

<body>

<?php $hideSidebar = $sf_user->getAttribute('sidebar_hidden', false); ?>


<div id="container">

  <div id="sidebar" <?php echo $hideSidebar ? 'class="invisible"' : '' ?>>
    
    <div id="sidebar_logo">
    <?php echo ull_link_to(
          image_tag(sfConfig::get('app_sidebar_logo',
            '/ullCoreThemeNGPlugin/images/logo_120'), 'alt="logo"')
            , '@homepage'
            , 'ull_js_observer_confirm=true'
          ); ?>
    </div>
   
    <div id="sidebar_content">
   
    <?php

          //the sidebar content can be overridden
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
              echo '<div id="sidebar_custom">';
              include_partial('myModule/custom_sidebar');
              echo '</div>';
            }
          }
     ?>
    </div>
   
   
  </div> <!-- end of sidebar -->

  <?php
    //Use JS to render the sidebar tab
    //This needs to be done here, because for the .before()
    //to work the #sidebar-div has to be closed already.
  ?>
  <script type="text/javascript">
    //<![CDATA[
    $("#sidebar").before(
      '<div id="sidebar_tab">' +
      '<a href="" id="tab_button_in" class="ui-state-default ui-corner-all no_underline <?php echo $hideSidebar ? '' : 'invisible' ?>"><big>&rarr;</big></a>' +
      '<a href="" id="tab_button_out" class="ui-state-default ui-corner-all no_underline <?php echo $hideSidebar ? 'invisible' : '' ?>"><big>&larr;</big></a>' +
      '</div>'
    );
    //]]>
  </script>
  
  <!-- Contains main navigation, content and footer -->
  <div id="canvas" <?php echo $hideSidebar ? 'style="margin-left: 1em;"' : '' ?>>
  
    <div id="nav_top">
  
      <div id='nav_links'>             
        <?php include_partial('myModule/navTopLinks'); ?>
      </div> 
      
      <div id="nav_syslinks_container">
      
        <div id="nav_langlinks">      
          <?php
            if (count(sfConfig::get('app_i18n_supported_languages')) > 1)
            {
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
            }
          ?>
          &nbsp;
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

</body>

</html>