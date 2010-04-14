<?php

/**
 * This widget renders a dynamic star-select ratings bar.
 * It submits changed values via ajax and updates another
 * static bar if available (usually the average rating).
 * 
 * The 'average_input_name' option specifies the name of
 * this control.
 * 
 * The 'url_to_call_on_select' option specifies the URL
 * which gets called on submit. The id of the object which
 * is voted on is added.
 * e.g. /ullClimbingRouteDB/rating/id/ would be valid, and
 * might be transformed to /ullClimbingRouteDB/rating/id/2/rating/4
 * 
 * JS is required, this widget does not degrade gracefully.
 * Id injection is required.
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
    
    //TODO: make this configureable, add translation
    $ratingTitles = array('Very poor', 'Poor', 'Average', 'Good', 'I like!');
    $starNumber = 'star' . $votedObjectId;
    $html = '';
    
    //TODO: add support for different star count
    for($i = 1; $i <= 5; $i++)
    {
      $inputTag = '<input class="star" type="radio" name="' . $starNumber .
        '" value="' . $i . '" title="' . $ratingTitles[$i - 1] . '" ';

      if ($i == $value)
      {
        $inputTag .= 'checked="checked" ';
      }

      $html .= $inputTag . '/>';
    }
    
    //This element's purpose is to display the rating titles
    $html .= '<span id="' . $starNumber . '-hover" style="margin: 0 0 0 20px;">&nbsp;</span>';

    $urlToCall = $this->getOption('url_to_call_on_select');
    $averageInputName = $this->getOption('average_input_name');
    
    //This block of javascript enables submitting of selected ratings
    //via ajax and the updating of a static bar (usually the average rating)
    //TODO: Make the updating of the static bar optional (if average_input_name is null?)
    $html .= <<<EOF
    <script type="text/javascript">

    var updateAvgStars_$votedObjectId = function(data)
    {
      //todo: add some integrity checks and see if we
      //can update the stars without this readOnly workaround
      var checkedStar = (data * 2);
      $("input[name='$averageInputName']").rating('readOnly', false);
      $("input[name='$averageInputName']").rating('select', checkedStar - 1);
      $("input[name='$averageInputName']").rating('readOnly', true);
    }

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
          var url = "$urlToCall$votedObjectId/";

          if (value != '') //remove the rating
          {
            url = url + "rating/" + value; 
          }

          $.ajax({
            url: url,
            cache: false,
            success: updateAvgStars_$votedObjectId
          });
        }
    });
  </script>
EOF;

    return $html;
  }
}
