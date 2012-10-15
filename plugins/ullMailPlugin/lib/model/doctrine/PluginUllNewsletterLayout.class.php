<?php

/**
 * PluginUllNewsletterLayout
 */
abstract class PluginUllNewsletterLayout extends BaseUllNewsletterLayout
{
  /**
   * This makes the is_default flag unique. So there ist max one entry where the
   * is_default flag is set.
   * 
   * @param unknown_type $event
   */
  public function preSave($event)
  {
    if ($this->is_default)
    {
      $q = new Doctrine_Query;
      $q
        ->update('UllNewsLetterLayout l')
        ->set('l.is_default', '?', false)
        ->where('l.is_default = ?', true)
        ->execute();
      ;
    }
  }
  
  
  /**
   * (non-PHPdoc)
   * @see lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine/Doctrine_Record::postSave()
   */
  public function postSave($event)
  {
    $this->writeCssFile();
  }  
  
  
  /**
   * Write styles from html_head into css file for usage in wysiwyg editor
   * 
   * File is located in web/css/ull_newsletter_layout_SLUG.css where SLUG is
   * the current layout slug
   */
  public function writeCssFile()
  {
    if ($this->html_head)
    {
      $dom = new DomDocument('1.0', 'utf-8');
      $dom->loadHTML($this->html_head);
  
      $c = new sfDomCssSelector($dom);
      $nodes = $c->matchAll('style');
      if ($nodes)
      {
        $styles = $nodes->getValue();
      
        $dir = sfConfig::get('sf_web_dir'). '/css';
        
        if (!file_exists($dir))
        {
          mkdir($dir, '0777', true);
        }
        
        $path = $dir . '/ull_newsletter_layout_' . $this->slug . '.css';
    
        file_put_contents($path, $styles);

        // Generating css file with layout specific prefixes
        // Used e.g. by ullContentElements
        // @see  http://www.ullright.org/ullWiki/show/content-elements
        $css = new HTML_CSS();
        $css->parseString($styles);        
        $cssArray = $css->toArray();
        
        $outputArray = $cssArray;
        
        $outputArray = array();
        
        foreach ($cssArray as $baseSelector => $data)
        {
          $prefix = '.newsletter_' . $this->slug;
          
          // remove body tag
          if ('body' === $baseSelector)
          {
            $outputArray[$prefix] = $data;
          }
          // handle comma separated lists
          elseif (strpos($baseSelector, ','))
          {
            $parts = explode(',', $baseSelector);
            foreach ($parts as &$part)
            {
              $part = trim($part);
              $part = $prefix . ' ' . $part;
            }
            
            $selector = implode(', ', $parts);
            
            $outputArray[$selector] = $data;
          }
          // "normal" prefixing
          else
          {  
            $outputArray[$prefix . ' ' . $baseSelector] = $data;
          }
        }
        
        $css->fromArray($outputArray);
        
        $prefixedStyles = $css->toString();
        
        $path = $dir . '/ull_newsletter_layout_' . $this->slug . '_prefixed.css';
    
        file_put_contents($path, $prefixedStyles);        
        
      }
    }
  }

}