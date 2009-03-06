<?php

class HTMLEncodeWikiDocsTask extends sfBaseTask
{

  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'encode-wiki-docs';
    $this->briefDescription = 'Encodes all wiki docs using HTML entity encoding';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] encodes all wiki docs using HTML entity encoding.
    The only intended usage is the one-time conversion from revisions prior to 638!

    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
EOF;

    $this->addArgument('dryrun', sfCommandArgument::OPTIONAL,
      'Set to \'dry\' to execute a dry run', 'no_dry');
    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
  }


  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection($this->name, 'Encoding all wiki docs...');

    $configuration = ProjectConfiguration::getApplicationConfiguration(
    $arguments['application'], $arguments['env'], true);

    $databaseManager = new sfDatabaseManager($configuration);

    $wcol = Doctrine::getTable('UllWiki')->findAll();
    foreach ($wcol as $wd)
    {
      if (strpos($wd->body, '<') !== FALSE)
      {
        $this->logSection($this->name, 'Encoding doc (id: ' . $wd->id . ')');
        $newbody = htmlentities($wd->body, ENT_QUOTES, "UTF-8");

        if ($arguments['dryrun'] != 'dry')
        {
          $q = new Doctrine_Query();
          $q->update('UllWiki d')
          ->set('d.body', '?', $newbody)
          ->where('d.id = ?', $wd->id);
          $q->execute();
        }
      }
      else
      {
        $this->logSection($this->name, 'Doc with id: ' . $wd->id . ' seems already encoded, skipping...');
      }
    }

    if ($arguments['dryrun'] == 'dry')
    {
      $this->logSection($this->name, 'Modifications were NOT saved to database (dryrun was specified).');
    }
    else
    {
      //$wcol->save();
      $this->logSection($this->name, 'Modifications were saved to database.');
    }
  }
}