<?php

/*
 * This file is part of the Lime test framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Bernhard Schussek <bernhard.schussek@symfony-project.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

function diff($old, $new){
foreach($old as $oindex => $ovalue){
$nkeys = array_keys($new, $ovalue);
foreach($nkeys as $nindex){
$matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
$matrix[$oindex - 1][$nindex - 1] + 1 : 1;
if($matrix[$oindex][$nindex] > $maxlen){
$maxlen = $matrix[$oindex][$nindex];
$omax = $oindex + 1 - $maxlen;
$nmax = $nindex + 1 - $maxlen;
}
}	
}
if($maxlen == 0) return array(array('d'=>$old, 'i'=>$new));
return array_merge(
diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
array_slice($new, $nmax, $maxlen),
diff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
}

require(sfConfig::get('sf_plugins_dir') . '/ullCorePlugin/lib/vendor/finediff/finediff.php');

/**
 * Tests that a value equals another.
 *
 * @package    Lime
 * @author     Bernhard Schussek <bernhard.schussek@symfony-project.com>
 * @version    SVN: $Id: LimeConstraintIs.php 23701 2009-11-08 21:23:40Z bschussek $
 */
class LimeConstraintIs extends LimeConstraint
{
  /**
   * (non-PHPdoc)
   * @see constraint/LimeConstraintInterface#evaluate($value)
   */
  public function evaluate($value)
  {
    try
    {
      LimeTester::create($value)->is(LimeTester::create($this->expected));
    }
    catch (LimeAssertionFailedException $e)
    {

      if (strpos($e->getActual(), "\n"))
      {
        echo $this->colordiff($e);
      }
      
      $text = sprintf(
        "     got: %s\nexpected: %s", 
        $e->getActual(10), 
        $e->getExpected(10)
      );
      
      throw new LimeConstraintException($text);
    }
  }
  
  /**
   * Additional output for multi line strings 
   * 
   * @param unknown_type $e
   */
  protected function colordiff($e)
  {
    $file1 = sfConfig::get('sf_cache_dir') . '/lime_diff1.txt';
    $file2 = sfConfig::get('sf_cache_dir') . '/lime_diff2.txt';
    
    $from = substr($e->getActual(), 1, -1);
    $to = substr($e->getExpected(), 1, -1);
    
    file_put_contents($file1, $from);
    file_put_contents($file2, $to);
    
    $diff = shell_exec('colordiff ' . $file1 . ' ' . $file2);
    
    unlink($file1);
    unlink($file2);

    return "Colordiff output for the following assertion:\n" . $diff;    
  }
}