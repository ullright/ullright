<html>

<head>
  <link rel="stylesheet" type="text/css" media="all" href="/ullCoreThemeNGPlugin/css/main.css" />
</head>

<body>

<br /><br />
  <h3>
    <?php echo __('Sorry, but a technical difficulty occured.', null, 'common') ?>
  </h3>
  <br />
  <?php echo __('Please try again in a few minutes.', null, 'common') ?>
  
  <br />
  <?php
    if (sfConfig::has('app_error_500_text'))
    {
      $errorPagesText = __(sfConfig::get('app_error_500_text')); 
      sfLoader::loadHelpers(array('Text'));
      echo auto_link_text($errorPagesText);
    }
    else
    {
      echo auto_link_text('If the problem persists, please do not hesitate to contact the IT department.');
    }
  ?>
  
  </body>
</html>