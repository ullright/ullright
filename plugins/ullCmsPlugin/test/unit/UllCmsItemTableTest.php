<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(43, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('getMenuTree() for a main-menu item');

  $items = UllCmsItemTable::getMenuTree('main-menu', 'homepage')->getSubnodes();
  
  $t->is(count($items), 5, 'Returns the correct number of items');
  $t->is(reset($items)->getData()->slug, 'homepage', 'Returns the correct data for the first entry');
  $t->is(reset($items)->hasMeta('is_current'), true, 'The current page is marked');
  $t->is(reset($items)->hasMeta('is_ancestor'), false, 'The current page is not its ancestor');
  $t->is(next($items)->getData()->slug, 'about-us', 'Returns the correct data for the second entry');
  $t->is(next($items)->getData()->slug, 'courses', 'Returns the correct data for the third entry');
  $t->is(next($items)->getData()->slug, 'contact', 'Returns the correct data for the fourth entry');
  $t->is(end($items)->getData()->slug, 'wiki', 'Returns the correct data for the last entry');
  $t->is(end($items)->hasMeta('is_current'), false, 'An abritrary "non-current" page is not marked as current');
  $t->is(end($items)->hasMeta('is_ancestor'), false, 'An abritrary "non-current" page is not marked as ancestor');
  
  
$t->diag('getMenuTree() for a sub tree - level 1 (main menu items)');  
  
  $items = UllCmsItemTable::getMenuTree('main-menu', 'advanced-course-1')->getSubnodes();
  $t->is(count($items), 5, 'Returns the correct number of items');
  $t->is(reset($items)->getData()->slug, 'homepage', 'Returns the correct data for the first entry');
  $t->is(reset($items)->hasMeta('is_current'), false, 'An abritrary "non-current" page is not marked as current');
  $t->is(next($items)->getData()->slug, 'about-us', 'Returns the correct data for the second entry');
  
  $courses = next($items);
  $t->is($courses->getData()->slug, 'courses', 'Returns the correct data for the third entry');
  $t->is($courses->hasMeta('is_current'), false, 'Not marked as current, but as ancestor');
  $t->is($courses->hasMeta('is_ancestor'), true, 'Not marked as current, but as ancestor');

$t->diag('getMenuTree() for a sub tree - level 2 (course items)');
  $courseItems = $courses->getSubnodes();
  $t->is(count($courseItems), 1, 'Returns the correct number of items');
  $t->is(reset($courseItems)->getData()->slug, 'advanced-courses', 'Returns the correct data for the first entry');
  $t->is(reset($courseItems)->hasMeta('is_current'), false, 'Not marked as current, but as ancestor');
  $t->is(reset($courseItems)->hasMeta('is_ancestor'), true, 'Not marked as current, but as ancestor');
  
$t->diag('getMenuTree() for a sub tree - level 3 (advanced course items)');  
  $courseAdvancedItems = reset($courseItems)->getSubnodes();
  $t->is(count($courseAdvancedItems), 1, 'Returns the correct number of items');
  $t->is(reset($courseAdvancedItems)->getData()->slug, 'advanced-course-1', 'Returns the correct data for the first entry');
  $t->is(reset($courseAdvancedItems)->hasMeta('is_current'), true, 'The current item is marked');
  $t->is(reset($courseAdvancedItems)->hasMeta('is_ancestor'), false, 'The current item is not an ancestor');  
  
  $t->is(next($items)->getData()->slug, 'contact', 'Returns the correct data for the fourth entry');
  $t->is(end($items)->getData()->slug, 'wiki', 'Returns the correct data for the last entry');
  $t->is(end($items)->hasMeta('is_current'), false, 'An abritrary "non-current" page is not marked as current');  
  

$t->diag('getRootNodeSlugs()');

  $t->is(UllCmsItemTable::getRootNodeSlugs(), array('footer-menu', 'main-menu'), 'Returns the correct slugs');

  
$t->diag('getAncestorTree()');
  $tree = UllCmsItemTable::getAncestorTree('advanced-course-1');
  
  $t->is($tree->getData()->slug, 'main-menu', 'Returns the correct top node');
  $subs = $tree->getSubnodes();
  $sub = reset($subs);
  $t->is($sub->getData()->slug, 'courses', 'Returns the correct level 2 node');
  $subs = $sub->getSubnodes();
  $sub = reset($subs);
  $t->is($sub->getData()->slug, 'advanced-courses', 'Returns the correct level 3 node');
  $subs = $sub->getSubnodes();
  $sub = reset($subs);
  $t->is($sub->getData()->slug, 'advanced-course-1', 'Returns the correct level 4 node');


$t->diag('markParentsAsAncestors() for a top level item');
  
  $item = Doctrine::getTable('UllCmsItem')->findOneBySlug('main-menu');

  $tree = UllCmsItemTable::getSubTree($item, 'main-menu');
  $tree = UllCmsItemTable::markParentsAsAncestors($tree, 'main-menu');
  $t->is($tree->hasMeta('is_ancestor'), false, 'The top level item itself is not its ancestor');
  
  
$t->diag('markParentsAsAncestors() for a second level item');  
  $tree = UllCmsItemTable::getSubTree($item, 'about-us');
  $tree = UllCmsItemTable::markParentsAsAncestors($tree, 'about-us');
  $t->is($tree->hasMeta('is_ancestor'), true, 'The top level item is an ancestor of a second level item' );

  $subnodes = $tree->getSubnodes();
  // 0 = home
  $t->is($subnodes[0]->hasMeta('is_ancestor'), false, 'An arbitrary second level item is not an ancestor');
  // 1 = about-us
  $t->is($subnodes[1]->hasMeta('is_ancestor'), false, 'The given second level item is not its ancestor');
  
  
$t->diag('markParentsAsAncestors() for a third level item');
  
  $tree = UllCmsItemTable::getSubTree($item);
  $tree = UllCmsItemTable::markParentsAsAncestors($tree);
  $t->is($tree->getData()->slug, 'main-menu', 'Returns an unchanged tree for no given slug');


  $tree = UllCmsItemTable::getSubTree($item, 'location');
  $tree = UllCmsItemTable::markParentsAsAncestors($tree, 'location');
  $t->is($tree->hasMeta('is_ancestor'), true, 'The top level item is an ancestor of a second level item' );

  $subnodes = $tree->getSubnodes();
  // 0 = home
  $t->is($subnodes[0]->hasMeta('is_ancestor'), false, 'An arbitrary second level item is not an ancestor');
  // 1 = about-us
  $t->is($subnodes[1]->hasMeta('is_ancestor'), true, 'The second level parent of the given item is an ancestor');  

  $subsubnodes = $subnodes[1]->getSubnodes();
  // 0 = location
  $t->is($subsubnodes[0]->hasMeta('is_ancestor'), false, 'The given third level item is not its ancestor');
  // 1 = team  
  $t->is($subsubnodes[1]->hasMeta('is_ancestor'), false, 'An arbitrary third level item is not an ancestor');
  
  



