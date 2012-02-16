<?php

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';

//sfContext::createInstance($configuration);
//sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');
//$request = sfContext::getInstance()->getRequest();

$t = new lime_test(12, new lime_output_color);

/*
 * Test class
 */
class ullSmsTransportTest extends ullSmsTransport
{
  protected function doSend(ullSms $sms)
  {
    $return = '';
    $return .= $sms->getFrom() . "\n";
    $return .= $sms->getTo() . "\n";
    $return .= $sms->getText();
    
    $GLOBALS['smsLog'][] = $return;
  }
}


/*
 * Test message
 */
function getSms() 
{
  $sms = new ullSms;
  $sms->setFrom('00431234567890');
  $sms->setTo('00430987654321');
  $sms->setText('Hello. Hope you are ullright');
  
  return $sms;
}


/*
 * Actual tests
 */

$t->diag('send() with smsing disabled');

  // Configure
  sfConfig::set('app_sms_enable', false);
  
  $GLOBALS['smsLog'] = array();
  $sms = getSms(); 
  
  $transport = new ullSmsTransportTest;
  $transport->send($sms);
  
  $t->is(count($GLOBALS['smsLog']), 0, 'No sms sent');
  
  
$t->diag('send() in production environment');

  // Configure
  sfConfig::set('app_sms_enable', true);
  sfConfig::set('app_sms_reroute', false);
  sfConfig::set('app_sms_send_debug_bcc', false);
  sfConfig::set('app_sms_debug_mobile_number', '0066999');
  
  $GLOBALS['smsLog'] = array();
  $sms = getSms(); 
  
  $transport = new ullSmsTransportTest;
  $transport->send($sms);
  
  $t->is(count($GLOBALS['smsLog']), 1, 'One sms sent');
  $reference = '00431234567890
00430987654321
Hello. Hope you are ullright';
  $t->is($GLOBALS['smsLog'][0], $reference, 'Sent correct data');

 
$t->diag('send() in production environment with bcc');

  // Configure
  sfConfig::set('app_sms_enable', true);
  sfConfig::set('app_sms_reroute', false);
  sfConfig::set('app_sms_send_debug_bcc', true);
  sfConfig::set('app_sms_debug_mobile_number', '+66 777 888 999');
  
  $GLOBALS['smsLog'] = array();
  $sms = getSms(); 
  
  $transport = new ullSmsTransportTest;
  $transport->send($sms);
  
  $t->is(count($GLOBALS['smsLog']), 2, 'Two sms sent, one real, one bcc');
  
  $reference = '00431234567890
00430987654321
Hello. Hope you are ullright';
  $t->is($GLOBALS['smsLog'][0], $reference, 'Sent correct data');

  $reference = '00431234567890
0066777888999
Hello. Hope you are ullright';
  $t->is($GLOBALS['smsLog'][1], $reference, 'Sent correct debug sms with normalized number');
  
  
$t->diag('send() with reroute option (dev environment)');

  // Configure
  sfConfig::set('app_sms_enable', true);
  sfConfig::set('app_sms_reroute', true);
  sfConfig::set('app_sms_send_debug_bcc', true);
  sfConfig::set('app_sms_debug_mobile_number', '+66 777 888 999');
  
  $GLOBALS['smsLog'] = array();
  $sms = getSms(); 
  
  $transport = new ullSmsTransportTest;
  $transport->send($sms);
  
  $t->is(count($GLOBALS['smsLog']), 1, 'One sms sent');
  
  $reference = '00431234567890
0066777888999
Hello. Hope you are ullright';
  $t->is($GLOBALS['smsLog'][0], $reference, 'Sent correct debug sms with normalized number');  


$t->diag('ullSms::send() shortcut function without configured tranport');

  try
  {
    $sms->send();
    $t->fail('Does not throw an exception');
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception');
  } 
  
  
$t->diag('ullSms::send() shortcut function (production env)');

  // Configure
  sfConfig::set('app_sms_transport', 'ullSmsTransportTest');
  sfConfig::set('app_sms_enable', true);
  sfConfig::set('app_sms_reroute', false);
  sfConfig::set('app_sms_send_debug_bcc', true);
  sfConfig::set('app_sms_debug_mobile_number', '+66 777 888 999');

  $GLOBALS['smsLog'] = array();
  $sms = getSms(); 
  
  $sms->send();
  
  $t->is(count($GLOBALS['smsLog']), 2, 'Two sms sent, one real, one bcc');
  
  $reference = '00431234567890
00430987654321
Hello. Hope you are ullright';
  $t->is($GLOBALS['smsLog'][0], $reference, 'Sent correct data');

  $reference = '00431234567890
0066777888999
Hello. Hope you are ullright';
  $t->is($GLOBALS['smsLog'][1], $reference, 'Sent correct debug sms with normalized number');  

