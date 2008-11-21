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
  // add the Theme's main css at last position to allow overriding plugins' settings   
  sfContext::getInstance()->getResponse()->addStyleSheet(
    '/' . $layout . '/css/main.css'
    , 'last'
    , array('media' => 'all')
  );

?>
</head>
<body>
<div id="indicator" style="display: none"></div> <!-- Ajax indicatior -->
<div id="container">

	<div id="sidebar">
		<div id="nav_logo">
		  <?php echo ull_link_to(
		              image_tag('/' . $layout . '/images/logo_120', 'alt="logo"')
		            , '@homepage'
		            , 'ull_js_observer_confirm=true'
		            ); ?> 
		        </div>
		<div id="sidebar_content">
		Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam convallis dapibus risus. Donec ligula nisi, eleifend vitae, sollicitudin eget, cursus a, turpis. Aliquam interdum massa. Duis nec ligula eu arcu rutrum molestie. In arcu. Class aptent taciti sociosqu ad litora torquent per conubia nostra...
		</div>
	</div>
	
	<div id="content">
	<div id="nav_top">

       <div id='nav_links'>             
          <ul class="nav_top_links">            
            
            <!--<li><?php //echo ull_link_to(__('Home', null, 'common'), 'homepage', 'ull_js_observer_confirm=true'); ?></li> -->
            
            <li id='nav_link_workflow'>
            <?php
                echo image_tag("/ullFlowThemeNGPlugin/images/ull_flow_32x32", 'alt="Workflow"') . "<br />";  
                echo ull_link_to(__('Workflows', null, 'common'), 'ullFlow/index', 'ull_js_observer_confirm=true');
              ?>
            </li>
            
            <li id='nav_link_tickets'>
            <?php
              echo image_tag("/ullFlowThemeNGPlugin/images/ull_flow_app_icons/bug_tracking_32x32", 'alt="Tickets"') . "<br />";
              echo ull_link_to(__('Active tickets', null, 'common'), 'ullFlow/tabular?app=helpdesk_tool&order=priority&order_dir=asc','ull_js_observer_confirm=true');
            ?>
            </li>
            
            <li id='nav_link_todo'>
            <?php
              echo image_tag("/ullFlowThemeNGPlugin/images/ull_flow_app_icons/todo_32x32", 'alt="Todo"') . "<br />";
              echo ull_link_to(__('My todo list', null, 'common'),'ullFlow/tabular?query=to_me&order=priority&order_dir=asc', 'ull_js_observer_confirm=true');
            ?>
            </li>
            
            <li id='nav_link_wiki'>
            <?php
              echo image_tag("/ullWikiThemeNGPlugin/images/ull_wiki_32x32", 'alt="Wiki"') . "<br />"; 
              echo ull_link_to(__('Wiki', null, 'common'), 'ullWiki/list', 'ull_js_observer_confirm=true');
            ?>
            </li>
            <?php 
              if (UllUserTable::hasGroup('MasterAdmins')) {
                echo "<li id=\"nav_link_admin\">" .
                 image_tag("/ullCoreThemeNGPlugin/images/ull_admin_32x32", 'alt="Admin"') . "<br />" .
                 ull_link_to(__('Admin'), 'ullAdmin/index', 'ull_js_observer_confirm=true') . '</li>';
              }
            ?>
          </ul>

         <div id="nav_syslinks_container">

          <div id="nav_langlinks">      
            <?php
              $culture = $sf_user->getCulture();
              $language = substr($culture,0,2);
              // fallback
              if (!$language) {
                $language = 'en';
              }
              if ($language <> 'en') {
                echo ull_link_to('English', 'ullText/change_culture?culture=en', 'ull_js_observer_confirm=true');
              }
              if ($language <> 'de') {
                echo ull_link_to('Deutsch', 'ullText/change_culture?culture=de', 'ull_js_observer_confirm=true');
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
     </div>
     
     <div id='content_main'>
        <?php echo $sf_data->getRaw('sf_content') ?>
      </div> <!-- end of content_main -->
      <div id="footer">
      <div id="footer_copyright">© 2007-2008 by Klemens Ullmann</div>
      <div id="footer_links">powered by <?php echo ull_link_to('ull.at', 'http://www.ull.at', 'ull_js_observer_confirm=true'); ?></div>
      <div class='clear'></div> <!-- to force the parent-box to enclose the floating divs -->
    </div>  
  </div>
</div>
</body>
</html>