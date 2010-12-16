<?php

/**
 * Wrapper for html2text from http://www.chuggnutt.com/html2text.php
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullHtml2Text extends html2text
{
  
  /** 
   * Static function for direct use
   * 
   * @param string $html
   */
  public static function transform($html)
  {
    $html2text = new ullHtml2Text($html);
    
    return $html2text->get_text();
  }
  
}