<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

sfContext::createInstance($configuration);
$request = sfContext::getInstance()->getRequest();
sfLoader::loadHelpers('ull');
sfLoader::loadHelpers('I18N');

$t = new lime_test(19, new lime_output_color);

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
    ),
    'baz'       => 'schmatz',
  );
  
  $result = 'ullFlow/list?foo=bar&filter[search]=bla&baz=schmatz';
  
  $t->is(_ull_reqpass_build_url($test), $result, 'returns the correct array');
  
$t->diag('ull_form_tag');

  $request->setParameter('module', 'ullWiki');
  $request->setParameter('action', 'index');
  $request->setParameter('foo', 'bar');
  
  $t->is(ull_form_tag(array('action' => 'list')), '<form method="post" action="/ullWiki/list/foo/bar">', 'returns the correct tag for a reqpass array');

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
function submit_my_name() 
{
  document.getElementById("submit_my_name").value = 1;
  document.getElementById("my_form_id").submit();
}
//]]>
</script>
<a href="#" onclick="submit_my_name(); return false;">my_value</a>
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
  $t->is(ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32',
                          'ullTableTool/list?table=UllUser', __('Manage users')),
      '<a href="/ullTableTool/list/table/UllUser">' .
      '<img alt="Manage users" src="/ullCoreThemeNGPlugin/images/ull_admin_32x32.png" /></a>' .
      '<a href="/ullTableTool/list/table/UllUser">Manage users</a>',
    'returns the correct result');
  
  $t->is(ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32',
                          'ullTableTool/list?table=UllUser', __('Manage users'), array('alt' => 'User admin')),
      '<a href="/ullTableTool/list/table/UllUser">' .
      '<img alt="User admin" src="/ullCoreThemeNGPlugin/images/ull_admin_32x32.png" /></a>' .
      '<a href="/ullTableTool/list/table/UllUser">Manage users</a>',
    'returns the correct result when specifying an alt-tag');

  clean_request_parameters();
  sfContext::getInstance()->getRequest()->setParameter('module', 'ullFlow');
  sfContext::getInstance()->getRequest()->setParameter('app', 'trouble_ticket');
  sfContext::getInstance()->getRequest()->setParameter('action', 'index');
  
  $t->is(ull_tc_task_link('/ullFlowThemeNGPlugin/images/ull_flow_32x32',
            array('action' => 'create'), __('Create')),
      '<a href="/ullFlow/create/app/trouble_ticket">' .
      '<img alt="Create" src="/ullFlowThemeNGPlugin/images/ull_flow_32x32.png" /></a>' .
      '<a href="/ullFlow/create/app/trouble_ticket">Create</a>',
    'returns the correct result when using reqpas');
   
function clean_request_parameters()
{
	foreach (sfContext::getInstance()->getRequest()->getParameterHolder()->getAll() as $key => $value)
  {
  	sfContext::getInstance()->getRequest()->getParameterHolder()->remove($key);
  }
}
