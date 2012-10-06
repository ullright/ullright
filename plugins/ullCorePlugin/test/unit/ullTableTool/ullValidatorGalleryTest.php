<?php

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';

sfContext::createInstance($configuration);

$t = new lime_test(1, new lime_output_color);

$v = new ullValidatorGallery();

$data = " 
/ullCoreThemeNGPlugin/images/nobody.png

  /ullCoreThemeNGPlugin/images/indicator.gif
/xxx/invalid.jpg

";

$reference = "/ullCoreThemeNGPlugin/images/nobody.png
/ullCoreThemeNGPlugin/images/indicator.gif";

$t->diag('doClean()');
  $t->is($v->clean($data), $reference, 'correctly cleans the asset paths');

