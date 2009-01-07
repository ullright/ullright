<?php
  // get layout name
  $layout = sfConfig::get('app_theme', 'ullThemeDefault');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>

<link rel="shortcut icon" href="/favicon.ico" />

<?php
  $path =  '/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
  sfContext::getInstance()->getResponse()->addStylesheet($path, 'first', array('media' => 'all'));
?>
</head>
<body>
<div id="indicator" style="display: none"></div> <!-- Ajax indicatior -->
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
		
		</div>
	</div>
	
	<!-- Contains main navigation and content -->
	<div id="canvas">
	<div id="nav_top">

       <div id='nav_links'>             
          <ul class="nav_top_links">            
            
            <!--<li><?php //echo ull_link_to(__('Home', null, 'common'), 'homepage', 'ull_js_observer_confirm=true'); ?></li> -->
            
            <li id='nav_link_workflow'>
            <?php
              echo ull_navigation_link('/ullFlowThemeNGPlugin/images/ull_flow_32x32',
                'ullFlow/index', __('Workflows', null, 'common'));
            ?>
            </li>
            
            <li id='nav_link_tickets'>
            <?php
              echo ull_navigation_link('/ullFlowThemeNGPlugin/images/ull_flow_app_icons/trouble_ticket_32x32',
                'ullFlow/list?app=trouble_ticket', __('Active tickets', null, 'common'),
                array('ull_js_observer_confirm' => 'true'));
            ?>
            </li>
            
            <li id='nav_link_todo'>
            <?php
              echo ull_navigation_link('/ullFlowThemeNGPlugin/images/ull_flow_app_icons/todo_32x32',
                'ullFlow/list?query=to_me', __('My tasks', null, 'common'),
                array('ull_js_observer_confirm' => 'true'));
            ?>
            </li>
            
            <li id='nav_link_wiki'>
            <?php
            echo ull_navigation_link('/ullWikiThemeNGPlugin/images/ull_wiki_32x32',
                'ullWiki/list', __('Wiki', null, 'common'),
                array('ull_js_observer_confirm' => 'true'));
            ?>
            </li>
            <?php 
              if (UllUserTable::hasGroup('MasterAdmins')) {
                 echo "<li id=\"nav_link_admin\">" .
                    ull_navigation_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32',
                    'ullAdmin/index', __('Admin'),
                    array('ull_js_observer_confirm' => 'true')) .
                    '</li>';
              	
              	 //echo "<li id=\"nav_link_admin\">" .
                 //image_tag("/ullCoreThemeNGPlugin/images/ull_admin_32x32", 'alt="Admin"') . "<br />" .
                 //ull_link_to(__('Admin'), 'ullAdmin/index', 'ull_js_observer_confirm=true') . '</li>';
              }
            ?>
          </ul>

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
          <br />
          <div id="nav_searchbox">
            <?php include_component('ullWiki', 'headerSearch'); ?>
          </div>

          <br />
           <div id="nav_loginbox">
            <?php include_component('ullUser', 'headerLogin'); ?>
            </div>

         </div>
       </div>
      <div class='clear'></div> <!-- to force the parent-box to enclose the floating divs -->
     <div id="nav_top_separator_line"></div>     
     </div>
     
     
     <div id="content">
        <?php echo $sf_data->getRaw('sf_content') ?>
      </div> <!-- end of content_main -->
      <div id="footer">
      <div id="footer_copyright">© 2007-2009 by Klemens Ullmann</div>
      <div id="footer_links">powered by <?php echo ull_link_to('ull.at', 'http://www.ull.at', 'ull_js_observer_confirm=true'); ?></div>
      <div class='clear'></div> <!-- to force the parent-box to enclose the floating divs -->
    </div>  
  </div>
</div>
</body>
</html>