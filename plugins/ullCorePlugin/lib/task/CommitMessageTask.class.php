<?php

/**
 * This task handles external commit messages and
 * parses them for information about ullFlow documents.
 * e.g. 'fixed #451 by burning...' might result in the
 * closing of ticket 451.
 */
class CommitMessageTask extends ullBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'commit-message';
    $this->briefDescription = 'Handles external commit messages';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO]  handles external commit messages
    and parses them for information about ullFlow documents.
    
    See online doc for all available tokens and commands.
    
    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
EOF;

    $this->addArgument('revId', sfCommandArgument::REQUIRED, 'The revision identifier');
    $this->addArgument('message', sfCommandArgument::REQUIRED, 'The commit message');
    $this->addArgument('author', sfCommandArgument::OPTIONAL, 'The author');

    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
  }


  protected function execute($arguments = array(), $options = array())
  {
    $this->initializeDatabaseConnection($arguments, $options);

    $message = $arguments['message'];
    $revisionId = $arguments['revId'];
    $author = $arguments['author'];
    
    //parse document numbers from the commit message
    $this->logSection($this->name, 'Parsing external commit message ...');
    
    $docMatches = array();
    preg_match_all('/#(\d+)/', $message, $docMatches);
    //0 are all matches, 1 is the number without the marker
    $docNumbers = $docMatches[1];

    if (empty($docNumbers))
    {
      $this->logBlock('Found no document markers (e.g. #451) in commit message', ERROR);
      return;
    }
    else
    {
      $this->logSection($this->name, 'Found markers for documents: ' . implode($docNumbers, ', '));
    }

    //ullright.org workflow configuration
    //$todoApp = Doctrine::getTable('UllFlowApp')->findOneBySlug('todo');
    //$bugApp = Doctrine::getTable('UllFlowApp')->findOneBySlug('trouble_ticket');

    //if (!$todoApp || !$bugApp)
    //{
    //  $this->logBlock('Could not retrieve workflow configuration', ERROR);
    //  return;
    //}

    $saveAction = Doctrine::getTable('UllFlowAction')->findOneBySlug('save_only');
    
    foreach($docNumbers as $docNumber)
    {
      $this->logSection($this->name, 'Now handling doc: ' . $docNumber);

      $flowDoc = Doctrine::getTable('UllFlowDoc')->findOneById($docNumber);
      if ($flowDoc)
      {
        $flowDoc->setMemoryAction($saveAction);
        $memoryText = "Revision $revisionId ";
        $memoryText .= !empty($author) ? "by $author " : '';
        $memoryText .= 'refers this document'; 
        $flowDoc->setMemoryComment($memoryText);
        $flowDoc->save();

        //        switch ($flowDoc->UllFlowApp)
        //        {
        //          case $todoApp:
        //            break;
        //          case $bugApp:
        //            $flowDoc->my_information_update = 'revision' . $flowDoc->my_information_update;
        //            $flowDoc->save();
        //            break;
        //
        //          default: $this->logBlock('Handling for document application not configured', ERROR);
        //        }
      }
      else
      {
        $this->logBlock('Document ' . $docNumber . ' does not exist', ERROR);
      }
    }

    $this->logSection($this->name, 'Finished');
  }
}