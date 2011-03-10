<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('Url');

$t = new myTestCase(3, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('render()');

  $menu = UllCmsItemTable::getMenuTree('main-menu', 'location');

  $reference = '<li class="ull_menu_item_homepage"><a href="/ullCms/show/homepage" class="ull_menu_entry_homepage">Home</a></li>
<li class="ull_menu_item_about_us ull_menu_is_ancestor"><a href="/ullCms/show/about-us" class="ull_menu_entry_about_us">About us</a>
<ul class="ull_menu_about_us">
<li class="ull_menu_item_location ull_menu_is_current"><a href="/ullCms/show/location" class="ull_menu_entry_location">Location</a></li>
<li class="ull_menu_item_team"><a href="/ullCms/show/team" class="ull_menu_entry_team">Team</a></li>
</ul>

</li>
<li class="ull_menu_item_courses"><a href="/ullCms/show/courses" class="ull_menu_entry_courses">Courses</a>
<ul class="ull_menu_courses">
<li class="ull_menu_item_advanced_courses"><a href="#" class="ull_menu_non_clickable" onclick="return false;">Advanced courses</a>
<ul class="ull_menu_advanced_courses">
<li class="ull_menu_item_advanced_course_1"><a href="/ullCms/show/advanced-course-1" class="ull_menu_entry_advanced_course_1">Advanced course 1</a></li>
</ul>

</li>
</ul>

</li>
<li class="ull_menu_item_contact"><a href="/ullCms/show/contact" class="ull_menu_entry_contact">Contact</a></li>
<li class="ull_menu_item_wiki"><a href="/ullWiki/list" class="ull_menu_entry_wiki">Wiki</a></li>
';

  $html = (string) new ullTreeMenuRenderer($menu, false);
  
  $t->is($html, $reference, 'Renderes the tree correctly without enclosing ul tags');
  
    $reference = '<ul class="ull_menu_main_menu">
' . $reference . '</ul>
';
  
  $html = (string) new ullTreeMenuRenderer($menu);
  $t->is($html, $reference, 'Renderes the tree correctly with enclosing ul tags');
  
    $menu = UllCmsItemTable::getMenuTree('main-menu', 'location');

  $reference = '<td class="ull_menu_item_homepage"><a href="/ullCms/show/homepage" class="ull_menu_entry_homepage">Home</a></td>
<td class="ull_menu_item_about_us ull_menu_is_ancestor"><a href="/ullCms/show/about-us" class="ull_menu_entry_about_us">About us</a>
<ul class="ull_menu_about_us">
<li class="ull_menu_item_location ull_menu_is_current"><a href="/ullCms/show/location" class="ull_menu_entry_location">Location</a></li>
<li class="ull_menu_item_team"><a href="/ullCms/show/team" class="ull_menu_entry_team">Team</a></li>
</ul>

</td>
<td class="ull_menu_item_courses"><a href="/ullCms/show/courses" class="ull_menu_entry_courses">Courses</a>
<ul class="ull_menu_courses">
<li class="ull_menu_item_advanced_courses"><a href="#" class="ull_menu_non_clickable" onclick="return false;">Advanced courses</a>
<ul class="ull_menu_advanced_courses">
<li class="ull_menu_item_advanced_course_1"><a href="/ullCms/show/advanced-course-1" class="ull_menu_entry_advanced_course_1">Advanced course 1</a></li>
</ul>

</li>
</ul>

</td>
<td class="ull_menu_item_contact"><a href="/ullCms/show/contact" class="ull_menu_entry_contact">Contact</a></td>
<td class="ull_menu_item_wiki"><a href="/ullWiki/list" class="ull_menu_entry_wiki">Wiki</a></td>
';

  $html = (string) new ullTreeMenuRenderer($menu, false, 'td');
  
  $t->is($html, $reference, 'Renderes the tree correctly without enclosing ul tags');
  



  
