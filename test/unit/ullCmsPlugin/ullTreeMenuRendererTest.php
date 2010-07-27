<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfLoader::loadHelpers('Url');

$t = new myTestCase(2, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('render()');

  $menu = UllCmsItemTable::getMenuTree('main-menu', 'about-us');

  $reference = '<li><a href="/ullCms/show/homepage">Homepage</a></li>
<li class="ull_menu_is_current"><a href="/ullCms/show/about-us">About us</a>
<ul class="ull_menu_about_us">
<li><a href="/ullCms/show/location">Location</a></li>
<li><a href="/ullCms/show/team">Team</a></li>
</ul>

</li>
<li><a href="/ullCms/show/courses">Courses</a>
<ul class="ull_menu_courses">
<li>Advanced courses
<ul class="ull_menu_advanced_courses">
<li><a href="/ullCms/show/advanced-course-1">Advanced course 1</a></li>
</ul>

</li>
</ul>

</li>
<li><a href="/ullCms/show/contact">Contact</a></li>
<li><a href="/ullWiki/list">Wiki</a></li>
';

  $html = (string) new ullTreeMenuRenderer($menu, false);
  $t->is($html, $reference, 'Renderes the tree correctly without enclosing ul tags');
  
  $reference = '<ul class="ull_menu_main_menu">
' . $reference . '</ul>
';
  
  $html = (string) new ullTreeMenuRenderer($menu);
  $t->is($html, $reference, 'Renderes the tree correctly with enclosing ul tags');


  
