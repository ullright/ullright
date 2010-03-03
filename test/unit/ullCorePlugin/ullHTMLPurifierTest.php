<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new lime_test(6, new lime_output_color);

$t->diag('init test');

  //purifyForSecurity() should remove or escape any stray angle bracket
  //and remove all tags.
  //purifyForWiki() should remove malicious/unknown tags but allow the
  //'id' attribute and frame targets.

  $values = array(
      '>fish ver<t<ebra>te < jellyfish > <script>hive</script> cetacean<',
      '<div id="jellyfish">eaten by cetacean?</div>',
      '<a href="http://www.jelly.fish" target="_blank">click here for fish</a>');
  
  $expectedSecure = array(
      '&gt;fish verte &lt; jellyfish &gt;  cetacean',
      'eaten by cetacean?',
      'click here for fish');
  
  $expectedWiki = array(
      '&gt;fish verte &lt; jellyfish &gt;  cetacean',
      '<div id="jellyfish">eaten by cetacean?</div>',
      '<a href="http://www.jelly.fish" target="_blank">click here for fish</a>');

$t->diag('purifyForSecurity() and purifyForWiki()');

  for($i = 0; $i < count($values); $i++)
  {
    $t->is(ullHTMLPurifier::purifyForSecurity($values[$i]), $expectedSecure[$i]);
    $t->is(ullHTMLPurifier::purifyForWiki($values[$i]), $expectedWiki[$i]);
  }
