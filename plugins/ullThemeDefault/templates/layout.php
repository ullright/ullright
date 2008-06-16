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
    
    <div id="content">
        
      <div id="nav_top">
      
        <div id="nav_logo">
          <?php echo ull_link_to(
              image_tag('/' . $layout . '/images/logo', 'alt="logo"')
            , '/'
            , 'ull_js_observer_confirm=true'
            ); ?> 
        </div>

        <div id="nav_syslinks_container">

          <div id="nav_syslinks">
            <?php include_partial('ullUser/header_login'); ?>
            
             &nbsp; | &nbsp;
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

          <div id="nav_searchbox">                   
            <?php include_partial('ullWiki/header_search'); ?>
          </div>

         </div>



        <div id='nav_links'>             
          <ul class="nav_top_links">            
            <li><?php echo ull_link_to(__('Home', null, 'common'),           '/',                                                             'ull_js_observer_confirm=true'); ?></li>
            <li><?php echo ull_link_to(__('Workflows', null, 'common'),      'ullFlow',                                                       'ull_js_observer_confirm=true'); ?></li>
            <li><?php echo ull_link_to(__('Active tickets', null, 'common'), 'ullFlow/tabular?app=helpdesk_tool&order=priority&order_dir=asc','ull_js_observer_confirm=true'); ?></li>
            <li><?php echo ull_link_to(__('My todo list', null, 'common'),   'ullFlow/tabular?query=to_me&order=priority&order_dir=asc',      'ull_js_observer_confirm=true'); ?></li>
            <li><?php echo ull_link_to(__('Wiki', null, 'common'),           'ullWiki/list',                                                  'ull_js_observer_confirm=true'); ?></li>
            <?php 
              if (UllUserPeer::userHasGroup(1)) {
                echo '<li>' . ull_link_to(__('Admin'), 'ullAdmin', 'ull_js_observer_confirm=true') . '</li>';
              } 
            ?>

          </ul>
        </div>

        <?php
//        ullCoreTools::printR($sf_request->getLanguages());
//        echo 'Current User Culture: ', $sf_user->getCulture();
          //$context = sfContext::getInstance();
          
//          ullCoreTools::printR($sf_user->getAttributeHolder());
          
          //ullCoreTools::printR($sf_request);
        ?>
        
        <div class='clear'></div> <!-- to force the parent-box to enclose the floating divs -->
           
      </div> <!-- end of nav_top -->
      
      <div id='content_main'>
        <?php echo $sf_data->getRaw('sf_content') ?>
      </div> <!-- end of content_main -->
              
    </div> <!-- end of content -->
  
    <div id="footer">
      <div id="footer_copyright">© 2007-2008 by Klemens Ullmann</div>
      <div id="footer_links">powered by <?php echo ull_link_to('ull.at', 'http://www.ull.at', 'ull_js_observer_confirm=true'); ?></div>
      <div class='clear'></div> <!-- to force the parent-box to enclose the floating divs -->
    </div>  
    
  </div> <!-- end of container -->
   
</body>
</html>
