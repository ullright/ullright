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

  $menu = UllCmsItemTable::getMenuTree('main-menu', 'about-us');

  $reference = '<li ><a href="/ullCms/show/homepage">Homepage</a></li>
<li class="ull_menu_is_current"><a href="/ullCms/show/about-us">About us</a>
<li ><a href="/ullCms/show/location">Location</a></li>
<li ><a href="/ullCms/show/team">Team</a></li>

</li>
<li ><a href="/ullCms/show/courses">Courses</a>
<li ><a href="/?">Advanced courses</a>
<li ><a href="/ullCms/show/advanced-course-1">Advanced course 1</a></li>

</li>

</li>
<li ><a href="/ullCms/show/contact">Contact</a></li>
<li ><a href="/ullWiki/list">Wiki</a></li>
';
  $html = (string) new ullTreeMenuRenderer($menu);
  
//  var_dump($html);
  
  $t->is($html, $reference, 'Renderes the tree correctly');

  
