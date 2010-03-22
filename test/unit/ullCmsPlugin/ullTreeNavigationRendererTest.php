<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfLoader::loadHelpers('Url');

$t = new myTestCase(1, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('render()');

  $navigation = UllCmsItemTable::getNavigationTree('main-menu', 'about-us');

  $reference = '<ul class="ull_navigation_main_menu">
<li class="ull_navigation_is_current"><a href="/ullCms/show/about-us">About us</a>
<ul class="ull_navigation_about_us">
<li ><a href="/ullCms/show/location">Location</a></li>
<li ><a href="/ullCms/show/team">Team</a></li>
</ul>

</li>
<li ><a href="/ullCms/show/contact">Contact</a></li>
<li ><a href="/ullCms/show/courses">Courses</a>
<ul class="ull_navigation_courses">
<li ><a href="/ullCms/show/advanced-courses">Advanced courses</a>
<ul class="ull_navigation_advanced_courses">
<li ><a href="/ullCms/show/advanced-course-1">Advanced course 1</a></li>
</ul>

</li>
</ul>

</li>
<li ><a href="/ullCms/show/homepage">Homepage</a></li>
<li ><a href="/ullCms/show/wiki">Wiki</a></li>
</ul>
';
  $html = (string) new ullTreeNavigationRenderer($navigation);
  
//  var_dump($html);
  
  $t->is($html, $reference, 'Renderes the tree correctly');

  
