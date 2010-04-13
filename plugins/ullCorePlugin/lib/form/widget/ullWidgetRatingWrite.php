<?php
class ullWidgetRatingWrite extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('average_input_name', 'avg_rating');
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (is_array($value))
    {
      $routeId = $value['id'];
      $value = $value['value'];
    }
    else
    {
      throw new InvalidArgumentException('$value must be an array, use id injection');
    }
    
    $ratingTitles = array('Very poor', 'Poor', 'Average', 'Good', 'I like!');
    $starNumber = 'star' . $routeId;
    $html = '';
    
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
    
    
    $html .= '<span id="' . $starNumber . '-hover" style="margin: 0 0 0 20px;">&nbsp;</span>';

    $averageInputName = $this->getOption('average_input_name');
    
    $html .= <<<EOF
    <script type="text/javascript">

    var updateAvgStars_$routeId = function(data)
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
          var url = "/ullClimbingRouteDB/rating/id/$routeId/";

          if (value != '') //remove the rating
          {
            url = url + "rating/" + value; 
          }

          $.ajax({
            url: url,
            cache: false,
            success: updateAvgStars_$routeId
          });
        }
    });
  </script>
EOF;
    
    return $html;
  }
}