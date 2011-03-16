<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

sfContext::createInstance($configuration);
$request = sfContext::getInstance()->getRequest();
sfContext::getInstance()->getConfiguration()->loadHelpers('ull');
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new lime_test(26, new lime_output_color);

$t->diag('_ull_reqpass_array_clean');

  $test = array(
    'module'  => 'ullFlow',
    'action'  => 'list',
    'commit'  => 'Search',
    'sf_culture' => 'de_AT',
    'ull_reqpass' => array('blabla'),
    'foo'     => '',
    'filter'  => array(
      'search'  => 'bla',
      'bar'     => '',
      'empty'   => array(
        'lonely'   => '',
      ),
    ),
  );
  
  $result = array(
    'module'  => 'ullFlow',
    'action'  => 'list',
    'filter'  => array(
      'search'  => 'bla',
    )
  ); 

  $t->is_deeply(_ull_reqpass_clean_array($test), $result, 'returns the correct array');
  //TODO: find usecase for rawurlencode option and write a test for it

  
$t->diag('_ull_reqpass_build_url');

  $test = array(
    'module'  => 'ullFlow',
    'action'  => 'list',
    'foo'     => 'bar',
    'filter'  => array(
      'search'  => 'bla',
      'status'  => 'rejected'
    ),
    'baz'       => 'schmatz',
  );
  
  $result = 'ullFlow/list?foo=bar&filter[search]=bla&filter[status]=rejected&baz=schmatz';
  
  $t->is(_ull_reqpass_build_url($test), $result, 'returns the correct array');
  
$t->diag('ull_form_tag');

  $request->setParameter('module', 'ullWiki');
  $request->setParameter('action', 'index');
  $request->setParameter('foo', 'bar');
  
  $t->is(ull_form_tag(array('action' => 'list')), '<form method="post" action="/ullWiki/list/foo/bar">', 'returns the correct tag for a reqpass array');
  
$t->diag('ull_url_for');

  $t->is(ull_url_for('ullMaki/eat?foo=bar'), '/ullMaki/eat/foo/bar', 'returns the correct url for a string');

  clean_request_parameters();
  $request->setParameter('module', 'ullWiki');
  $request->setParameter('action', 'index');
  $request->setParameter('foo', 'bar');
  
  $t->is(ull_url_for(array('action' => 'list')), '/ullWiki/list/foo/bar', 'returns the correct url for a reqpass array');  
  $t->is(ull_url_for(array('action' => 'list', 'filter[search]' => '%s')), '/ullWiki/list/foo/bar/filter[search]/%25s',
        'returns the correct escaped url');
   $t->is(ull_url_for(array('action' => 'list', 'filter' => array('search' => '%s'))), '/ullWiki/list/foo/bar/filter[search]/%25s',
        'returns the correct escaped url');
  
$t->diag('ull_submit_tag()');

  $t->is(ull_submit_tag('my_value', array('name' => 'my_name')), 
      '<input type="submit" name="my_name" value="my_value" />',
      'returns the correct result for default submit_tag() params');
  
  try
  {
    ull_submit_tag('my_value', array('display_as_link' => true));
    $t->fail('ull_submit_tag doesn\'t throw an exception if the option "display as link" is given without required options "name" and "form_id"');
  }
  catch (Exception $e)
  {
    $t->pass('ull_submit_tag throws an exception if the option "display as link" is given without required options "name" and "form_id"');
  }
  
  try
  {
    ull_submit_tag('my_value', array('display_as_link' => true, 'name' => 'my_name'));
    $t->fail('ull_submit_tag doesn\'t throw an exception if the option "display as link" is given without required option "form_id"');
  }
  catch (Exception $e)
  {
    $t->pass('ull_submit_tag throws an exception if the option "display as link" is given without required option "form_id"');
  }

  try
  {
    ull_submit_tag('my_value', array('display_as_link' => true, 'form_id' => 'my_form_id'));
    $t->fail('ull_submit_tag doesn\'t throw an exception if the option "display as link" is given without required option "name"');
  }
  catch (Exception $e)
  {
    $t->pass('ull_submit_tag throws an exception if the option "display as link" is given without required option "name"');
  }  
  
  $t->is(ull_submit_tag('my_value', array('name' => 'submit_my_name', 'form_id' => 'my_form_id', 'display_as_link' => true)), 
      '<input type="submit" name="submit_my_name" value="my_value" />',
      'returns the correct result when enabling "display_as_link" option without javascript');

  sfContext::getInstance()->getUser()->setAttribute('has_javascript', true);
  $reference = '<input type="hidden" name="submit_my_name" id="submit_my_name" value="" />
<script type="text/javascript">
//<![CDATA[
function submit_my_name_cheersIE8() 
{
  document.getElementById("submit_my_name").value = 1;
  $("#my_form_id").submit();
}
//]]>
</script>
<a href="#" onclick="submit_my_name_cheersIE8(); return false;">my_value</a>
';  
  $t->is(ull_submit_tag('my_value', array('name' => 'submit_my_name', 'form_id' => 'my_form_id', 'display_as_link' => true)), 
      $reference,
      'returns the correct result when enabling "display_as_link" with javascript');
  sfContext::getInstance()->getUser()->setAttribute('has_javascript', false);      

$t->diag('ull_submit_tag_parse()');

  $test = array(
    'module'            => 'ullWiki',
    'action'            => 'edit',
    'fields'            => array('my_field' => 'my_value'),
    'submit_save_only'  => 'Save only',
    'submit_reject'     => '',
  );
  $t->is(ull_submit_tag_parse($test), 'save_only', 'returns the correct value');
  
$t->diag('ull_link_to()');

  $t->is(ull_link_to('my_label', 'http://www.ull.at', array('title' => 'my_title')), 
      '<a title="my_title" href="http://www.ull.at">my_label</a>',
      'returns the correct result for default link_to() params');      

  //TODO: more tests  
  
$t->diag('ull_button_to()');  
  $t->is(ull_button_to('my_label', 'http://www.ull.at', array('title' => 'my_title')), 
      '<a title="my_title" href="http://www.ull.at">my_label</a>',
      'returns the correct result for default button_to() params');

  sfContext::getInstance()->getUser()->setAttribute('has_javascript', true);
  $t->is(ull_button_to('my_label', 'http://www.ull.at', array('title' => 'my_title')), 
      '<input title="my_title" value="my_label" type="button" onclick="document.location.href=\'http://www.ull.at\';" />',
      'returns the correct result for default button_to() params');
  sfContext::getInstance()->getUser()->setAttribute('has_javascript', false); 

  
$t->diag('ull_image_path()');
  $t->is(ull_image_path('search', null, null, 'ullWiki'),
    '/ullWikiThemeNGPlugin/images/action_icons/search_16x16',
    'returns the correct result for default ull_image_tag() params');
  
//TODO: re-enable  
$t->diag('ull_image_tag()');
  $t->is(ull_image_tag('search', array(), null, null, 'ullWiki'),
    '<img alt="Search" title="Search" src="/ullWikiThemeNGPlugin/images/action_icons/search_16x16.png" />',
    'returns the correct result for default ull_image_tag() params');
  
  clean_request_parameters();
  sfContext::getInstance()->getRequest()->setParameter('module', 'ullWiki');
  
  $t->is(ull_image_tag('search'),
    '<img alt="Search" title="Search" src="/ullWikiThemeNGPlugin/images/action_icons/search_16x16.png" />',
    'returns the correct result for default ull_image_tag() params');
  
$t->diag('ull_tc_task_link()');
  $reference = '<div class="float_left"><a href="/ullTableTool/list/table/UllUser"><img alt="Manage users" title="Manage users" src="/ullCoreThemeNGPlugin/images/ull_admin_32x32.png" height="24" width="24" /></a></div><div><a title="Manage users" href="/ullTableTool/list/table/UllUser">Manage users</a></div><div class="clear_left"></div>';
  $t->is(ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32',
                          'ullTableTool/list?table=UllUser', __('Manage users')),
    $reference,
    'returns the correct result');
  
  $reference = '<div class="float_left"><a href="/ullTableTool/list/table/UllUser"><img alt="User admin" title="Manage users" src="/ullCoreThemeNGPlugin/images/ull_admin_32x32.png" height="24" width="24" /></a></div><div><a title="Manage users" href="/ullTableTool/list/table/UllUser">Manage users</a></div><div class="clear_left"></div>';
  $t->is(ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32',
                          'ullTableTool/list?table=UllUser', __('Manage users'), array('alt' => 'User admin')),
    $reference,
    'returns the correct result when specifying an alt-tag');
    
  $reference = '<div class="float_left"><a href="/ullTableTool/list/table/UllUser"><img alt="User admin" title="Here you can play god" src="/ullCoreThemeNGPlugin/images/ull_admin_32x32.png" height="24" width="24" /></a></div><div><a title="Here you can play god" href="/ullTableTool/list/table/UllUser">Manage users</a></div><div class="clear_left"></div>';
  $t->is(ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32',
                          'ullTableTool/list?table=UllUser', __('Manage users'), array('alt' => 'User admin', 'title' => 'Here you can play god')),
    $reference,
    'returns the correct result when specifying an alt-tag and title-tag');    

  clean_request_parameters();
  sfContext::getInstance()->getRequest()->setParameter('module', 'ullFlow');
  sfContext::getInstance()->getRequest()->setParameter('app', 'trouble_ticket');
  sfContext::getInstance()->getRequest()->setParameter('action', 'index');
  
  $reference = '<div class="float_left"><a href="/ullFlow/create/app/trouble_ticket"><img alt="Create" title="Create" src="/ullFlowThemeNGPlugin/images/ull_flow_32x32.png" height="24" width="24" /></a></div><div><a title="Create" href="/ullFlow/create/app/trouble_ticket">Create</a></div><div class="clear_left"></div>';
  $t->is(ull_tc_task_link('/ullFlowThemeNGPlugin/images/ull_flow_32x32',
            array('action' => 'create'), __('Create')),
    $reference,
    'returns the correct result when using reqpas');

$t->diag('ull_navigation_link()');
  $t->is(ull_navigation_link('/ullFlowThemeNGPlugin/images/ull_flow_32x32',
    'ullFlow/index', __('Workflows', null, 'common')),
    '<a href="/ullFlow"><img alt="Workflows" src="/ullFlowThemeNGPlugin/images/ull_flow_32x32.png" /></a>' .
    '<br /><a href="/ullFlow">Workflows</a>', 'returns the correct result');
  
   $t->is(ull_navigation_link('/ullFlowThemeNGPlugin/images/ull_flow_32x32',
    'ullFlow/index', __('Workflows', null, 'common'), array('alt' => 'Workflow application')),
    '<a href="/ullFlow"><img alt="Workflow application" src="/ullFlowThemeNGPlugin/images/ull_flow_32x32.png" /></a>' .
    '<br /><a href="/ullFlow">Workflows</a>', 'returns the correct result when specifying an alt-tag');

   
function clean_request_parameters()
{
  sfContext::getInstance()->getRequest()->getParameterHolder()->clear();
}

function request_parameters_from_array($array)
{
  $holder = sfContext::getInstance()->getRequest()->getParameterHolder();
  foreach ($array as $key => $value)
  {
    $holder->set($key, $value);
  }
}