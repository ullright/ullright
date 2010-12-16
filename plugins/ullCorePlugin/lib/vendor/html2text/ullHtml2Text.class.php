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
     *  List of pattern replacements corresponding to patterns searched.
     *  
     *  ull modification: 
     *  * Don't indent <p> tags
     *  * Put asterix around headings  
     *
     *  @var array $replace
     *  @access public
     *  @see $search
     */
    var $replace = array(
        '',                                     // Non-legal carriage return
        ' ',                                    // Newlines and tabs
        ' ',                                    // Runs of spaces, pre-handling
        '',                                     // <script>s -- which strip_tags supposedly has problems with
        '',                                     // <style>s -- which strip_tags supposedly has problems with
        //'',                                     // Comments -- which strip_tags might have problem a with
        "strtoupper(\"\n\n*** \\1 ***\n\n\")",          // H1 - H3
        "ucwords(\"\n\n\\1\n\n\")",             // H4 - H6
        "\n\n",                                 // <P>
        "\n",                                   // <br>
        'strtoupper("\\1")',                    // <b>
        'strtoupper("\\1")',                    // <strong>
        '_\\1_',                                // <i>
        '_\\1_',                                // <em>
        "\n\n",                                 // <ul> and </ul>
        "\n\n",                                 // <ol> and </ol>
        "\t* \\1\n",                            // <li> and </li>
        "\n\t* ",                               // <li>
        '$this->_build_link_list("\\1", "\\2")',
                                                // <a href="">
        "\n-------------------------\n",        // <hr>
        "\n\n",                                 // <table> and </table>
        "\n",                                   // <tr> and </tr>
        "\t\t\\1\n",                            // <td> and </td>
        "strtoupper(\"\t\t\\1\n\")",            // <th> and </th>
        ' ',                                    // Non-breaking space
        '"',                                    // Double quotes
        "'",                                    // Single quotes
        '>',
        '<',
        '&',
        '(c)',
        '(tm)',
        '(R)',
        '--',
        '-',
        '*',
        '�',
        'EUR',                                  // Euro sign. � ?
        '',                                     // Unknown/unhandled entities
        ' '                                     // Runs of spaces, post-handling
    );  
  
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