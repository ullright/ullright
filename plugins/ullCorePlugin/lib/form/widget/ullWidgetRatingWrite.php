<?php

/**
 * This widget renders a dynamic star-select ratings bar.
 * It submits changed values via ajax and updates another
 * static bar if available (usually the average rating).
 * 
 * The 'average_input_name' option specifies the name of
 * this control.
 * 
 * Note: This functionality is disabled for now, since
 * we changed the read widget to just display images and not
 * use javascript, so this code here would need some changes.
 * Also, when rated objects have a larger amount of given
 * ratings a single new vote does not change the average considerably.
 * 
 * The 'url_to_call_on_select' option specifies the URL
 * which gets called on submit. The id of the object which
 * is voted on is added.
 * e.g. /ullClimbingRouteDB/rating/id/ would be valid, and
 * might be transformed to /ullClimbingRouteDB/rating/id/2/rating/4
 * 
 * This widget degrades gracefully. By default, a simple list
 * with ratings options is displayed, which is replaced via JS
 * with the real rating bar. This requires the handling function
 * to handle both XML requests and normal ones.
 * 
 * Id injection is required.
 * TODO: add support for different star count
 * TODO: make the rating options configureable
 */
class ullWidgetRatingWrite extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addRequiredOption('url_to_call_on_select');
    $this->addOption('average_input_name', 'avg_rating');
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    //We absolutely need the injected identifier
    if (is_array($value))
    {
      $votedObjectId = $value['id'];
      $value = $value['value'];
    }
    else
    {
      throw new InvalidArgumentException('$value must be an array, use id injection');
    }
    
    //handle options and create some names
    $averageInputName = $this->getOption('average_input_name');
    $urlToCall = url_for($this->getOption('url_to_call_on_select'));
    $starNumber = 'star' . $votedObjectId;
    $ratingURL = $urlToCall . '/id/' . $votedObjectId;
    $ratingTitles = array('Not recommendable', 'Needs to be improved', 'Moderate', 'Good', 'Very good');
    
    //create non-javascript 'el cheapo' rating list
    $html = '<div id="' . $starNumber . '">';
    $html .=  ($value) ? __($ratingTitles[$value - 1], null, 'ullCoreMessages') .
      ' (<a href="' . $ratingURL . '">Entfernen</a>)' : 'Noch nicht bewertet';
    $html .= '<ul>';
    for($i = 5; $i >= 1; $i--)
    {
      $html .= '<li><a href="' . $ratingURL . '/rating/' . $i . '">' .
        __($ratingTitles[$i - 1], null, 'ullCoreMessages') . '</a></li>';
    }
    $html .= '</ul></div>';
    
    //now create the code for the 'real' JS bar
    $ratingBarHTML = '';
    for($i = 1; $i <= 5; $i++)
    {
      $inputTag = '<input class="star {cancelValue:0, cancel:"' . __('Remove rating', null, 'ullCoreMessages') .
        '"}" type="radio" name="' . $starNumber .
        '" value="' . $i . '" title="' . __($ratingTitles[$i - 1], null, 'ullCoreMessages') . '" ';

      if ($i == $value)
      {
        $inputTag .= 'checked="checked" ';
      }

      $ratingBarHTML .= $inputTag . '/>';
    }
    
    //This element's purpose is to display the rating titles
    $ratingBarHTML .= '<span id="' . $starNumber . '-hover" style="margin: 0 0 0 20px;">&nbsp;</span>';
  
    //Now build the JS necessary for replacing the static list
    //with the real bar and add its AJAX functionality
    $html .= <<<EOF
    <script type="text/javascript">
    //<![CDATA[

    $("#$starNumber").replaceWith('$ratingBarHTML');
    
    $("input[name='$starNumber']").rating(
      {
        focus: function(value, link)
        {
          var tip = $('#$starNumber-hover');
          tip[0].data = tip[0].data || tip.html();
          tip.html(link.title || 'value: '+value);
        },
        
        blur: function(value, link)
        {
          var tip = $('#$starNumber-hover');
          $('#$starNumber-hover').html(tip[0].data || '');
        },
        
        callback: function(value, link)
        {
          var url = "$urlToCall/id/$votedObjectId";

          if (value != '') //remove the rating
          {
            url = url + "/rating/" + value; 
          }

          $.ajax({
            url: url,
            cache: false,
          });
        }
    });
  //]]>
  </script>
EOF;

    return $html;
  }
  
  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    return array(
      '/ullCorePlugin/js/jq/jquery-min.js', 
      '/ullCorePlugin/js/jq/jquery.rating.pack.js',
      '/ullCorePlugin/js/jq/jquery.MetaData.js',
    );   
  }
  
  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * The array keys are files and values are the media names (separated by a ,):
   *
   *   array('/path/to/file.css' => 'all', '/another/file.css' => 'screen,print')
   *
   * @return array An array of stylesheet paths
   */  
  public function getStylesheets()
  {
    return array(
      '/ullCorePlugin/css/star-rating/star-rating.css' => 'all'
    );  
  }  
}
