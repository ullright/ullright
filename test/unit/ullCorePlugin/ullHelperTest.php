<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';


sfContext::createInstance($configuration);
$request = sfContext::getInstance()->getRequest();
sfLoader::loadHelpers('ull');

$t = new lime_test(6, new lime_output_color);

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
  
  
// TODO: check where the 'symfony/symfony' comes from
// TODO: the test passes when run serparatly, but not with test:all ?!?   
//$t->diag('ull_form_tag');
//
//  $request->setParameter('module', 'ullWiki');
//  $request->setParameter('action', 'index');
//  $request->setParameter('foo', 'bar');
//  
//  $t->is(ull_form_tag(array('action' => 'list')), '<form method="post" action="symfony/symfony/ullWiki/list/foo/bar">', 'returns the correct tag for a reqpass array');

$t->diag('ull_submit_tag');

  $t->is(ull_submit_tag('my_value', array('name' => 'my_name')), 
      '<input type="submit" name="my_name" value="my_value" />',
      'returns the correct result for default submit_tag() params');
  
  try
  {
    ull_submit_tag('my_value', array('display_as_link' => true));
    $t->fail('ull_submit_tag doesn\'t throw an exception if the option "display as link" is given without the option "name"');
  }
  catch (Exception $e)
  {
    $t->pass('ull_submit_tag throws an exception if the option "display as link" is given without the option "name"');
  }
  
  $t->is(ull_submit_tag('my_value', array('name' => 'my_name', 'display_as_link' => true)), 
      '<input type="submit" name="my_name" value="my_value" />',
      'returns the correct result when enabling "display_as_link" option without javascript');

  sfContext::getInstance()->getUser()->setAttribute('has_javascript', true);
  $reference = '<input type="hidden" name="submit_my_name" id="submit_my_name" value="" />
<script type="text/javascript">
//<![CDATA[
function submit_my_name() 
{
  document.getElementById("submit_my_name").value = 1;
  document.form1.submit();
}
//]]>
</script>
<a href="#" onclick="submit_my_name(); return false;">my_value</a>
';  
  $t->is(ull_submit_tag('my_value', array('name' => 'my_name', 'display_as_link' => true)), 
      $reference,
      'returns the correct result when enabling "display_as_link" with javascript');