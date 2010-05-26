<?php

/**
 * This widget renders a static star-select ratings bar.
 *
 * The 'add_random_identifier' option specifies whether
 * a randomly generated string should be added to the
 * name attribute (necessary when displaying multiple
 * bars on the same page)
 */
class ullWidgetRatingRead extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    parent::__construct($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (empty($value))
    {
      return '<small>' . __('No ratings yet', null, 'ullCoreMessages') . '</small>';
    }

    //html code for full and half star
    //the a-element is mandatory for correct rendering!
    $codeForFullStar = '<div class="star-rating star-rating-readonly star-rating-on" style="width: 16px; "><a></a></div>';
    $codeForHalfStar = '<div class="star-rating star-rating-readonly star-rating-on" style="width: 8px; "><a style="margin-right: -8px; "></a></div>';

    $html = '<div class="no_wrap">';

    //round the ratings value to 0.5
    //3.23 => 3 stars
    //3.39 => 3.5 stars
    //3.71 => 3.5 stars
    //3.79 => 4 stars
    $starCount = round($value / 0.5) * 0.5;

    //add full stars
    for(; $starCount >= 1; $starCount--)
    {
      $html .= $codeForFullStar;
    }

    //add another half star if necessary
    if ($starCount > 0)
    {
      $html .= $codeForHalfStar;
    }

    $html .= "</div>";

    return $html;
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