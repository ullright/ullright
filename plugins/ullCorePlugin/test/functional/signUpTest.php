<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$b
  ->diag('Load sign up page')
  ->get('/')
  ->isRedirected()
  ->followRedirect()
  ->click('Sign up')
  ->isStatusCode(200)   
  ->with('request')->begin()   
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'signUp')
  ->end()
;

$b
  ->diag('Fill in form and submit')
  ->setField('fields[first_name]', 'PJ')
  ->setField('fields[last_name]', 'Harvey')
  ->setField('fields[email]', 'pj@example.com')
  ->setField('fields[username]', 'pj')
  ->setField('fields[password]', 'man size')
  ->setField('fields[password_confirmation]', 'man size')
  ->click('Sign up', array(), array('position' => 2)) // The button, not the header link
;

$b
  ->diag('Check submit')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)   
  ->with('request')->begin()   
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'signedUp')
  ->end()
  ->with('response')->begin()
    ->matches('/Thank you for signing up/')
    ->checkElement('#nav_loginbox a', 'pj') // "@header: Logged in as pj"
  ->end() 
;

$b
  ->diag('My account - check data')
  ->click('My account')
  ->isStatusCode(200) 
  ->with('request')->begin()   
   ->isParameter('module', 'ullUser')
   ->isParameter('action', 'editAccount')
  ->end() 
  ->with('response')->begin()
    ->matches('/Edit your account data/')
    ->checkElement('input[id="fields_first_name"][value="PJ"]')
    ->checkElement('input[id="fields_last_name"][value="Harvey"]')
    ->checkElement('input[id="fields_email"][value="pj@example.com"]')
    ->checkElement('tr:nth-child(4) td:nth-child(2)', 'pj')
    ->checkElement('input[id="fields_password"][value="********"]')
    ->checkElement('input[id="fields_password_confirmation"][value="********"]')
  ->end()
;

$b
  ->diag('My account - edit data and check')
  ->setField('fields[email]', 'pj.harvey@example.com')
  ->click('Save')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200) 
  ->with('request')->begin()   
   ->isParameter('module', 'ullUser')
   ->isParameter('action', 'editAccount')
  ->end()
  ->with('response')->begin()
    ->matches('/Edit your account data/')
    ->checkElement('input[id="fields_email"][value="pj.harvey@example.com"]')
  ->end()
;
   
$b
  ->diag('Check if signed up user can log in')
  ->click('Log out')
  ->get('ullAdmin/index')
  ->loginAs('pj', 'man size')
    ->with('response')->begin()
    ->checkElement('#nav_loginbox a', 'pj') // "@header: Logged in as pj"
  ->end()
;

$b
  ->diag('Check if user can change his password')
  ->click('My account')
  ->setField('fields[password]', 'city')
  ->setField('fields[password_confirmation]', 'city')
  ->click('Save')
  ->isRedirected()
  ->followRedirect()  
  ->click('Log out')
  ->get('ullAdmin/index')
  ->loginAs('pj', 'city')
    ->with('response')->begin()
    ->checkElement('#nav_loginbox a', 'pj') // "@header: Logged in as pj"
  ->end()
; 
  
   
