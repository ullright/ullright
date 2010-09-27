<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$dgsListEdit = $b->getDgsUllTimeEditList();
$dgsListSum = $b->getDgsUllTimeListTableSum();

//from: next friday (testing workday-only recurrence)
$testDate = date('Y-m-d', strtotime('next friday'));
//to: tuesday after that friday
$testDateRecurring = date('Y-m-d', strtotime('next tuesday', strtotime('next friday')));
//these dates could be in the future, in that case, we need a time period
if (UllTimePeriodTable::findSlugByDate($testDate) === null)
{
  $period = new UllTimePeriod();
  $period->name = 'next_month';
  $period->from_date = $testDate;
  $period->to_date = $testDateRecurring;
  $period->save();
}

$testUserId = Doctrine::getTable('UllUser')->findOneByUserName('test_user')->id;
$projectId = Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright')->id;

$newTimeReport = new UllTimeReporting();
$newTimeReport->ull_user_id = $testUserId; 
$newTimeReport->date = $testDate;
$newTimeReport->begin_work_at = '09:00:00';
$newTimeReport->end_work_at = '17:00:00';
$newTimeReport->save();

$newProjectReport = new UllProjectReporting();
$newProjectReport->ull_user_id = $testUserId;
$newProjectReport->ull_project_id = $projectId;
$newProjectReport->date = $testDate;
$newProjectReport->duration_seconds = 17100; //04:45
$newProjectReport->save();

//promote test_user to TimeAdmin (future entry right for recurring efforts)
$groupMembership = new UllEntityGroup();
$groupMembership->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
$groupMembership->UllGroup = Doctrine::getTable('UllGroup')->findOneByDisplayName('TimeAdmins');
$groupMembership->save();

$b
  ->diag('go to Project timereporting for ' . $testDate)
  ->get('ullTime/createProject/date/' . $testDate)
  ->loginAs()
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'createProject')
  ->end()
;
 
$b
  ->diag('create: create recurring project effort')
  ->setField('fields[ull_project_id]', $projectId)
  ->setField('fields[duration_seconds]', '1:15')
  ->setField('fields[recurring_until]', $testDateRecurring)
  ->click('Save and create another entry')
  ->isRedirected()
  ->followRedirect()
  //let's see if the new entry was created correctly for this day ...
  ->with('response')->begin()
    ->checkElement($dgsListEdit->getFullRowSelector(), 3)
    ->checkElement($dgsListEdit->get(1, 'duration'), '/4:45/')
    ->checkElement($dgsListEdit->get(2, 'duration'), '/1:15/')
    ->checkElement('p.ull_time_working_delta_time > span', '8:00')
    ->checkElement('p.ull_time_working_delta_time > span + span', '2:00') // 8:00 - 4:45 - 1:15
  ->end()
;

// ... and for the two following ones
$b
  ->diag('list: check times')
  ->click('Cancel and return to list')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->checkElement('tr.list_table_total_sum > td:nth-child('
      . $dgsListSum->getColumnAlias('time_total') . ')', '8:00')
    // project total time should be: 4:45 + 1:15 * 3 = 8:30
    //(only 3 times instead of 5 because only workdays count)
    ->checkElement('tr.list_table_total_sum > td:nth-child('
      . $dgsListSum->getColumnAlias('project_total') . ')', '8:30')
    ->checkElement('tr.list_table_total_sum > td:nth-child('
      . $dgsListSum->getColumnAlias('delta') . ')', '- 0:30')
  ->end()
;
