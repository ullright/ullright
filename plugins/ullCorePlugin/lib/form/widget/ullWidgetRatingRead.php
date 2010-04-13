<?php
class ullWidgetRatingRead extends ullWidget
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (empty($value))
    {
      return __('Not yet rated', null, 'ullCoreMessages');
    }
    
    $html = '';
    $roundedAvg = round($value, 1);
    $checkedStar = $roundedAvg / 0.5;
    
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    
    $randId = uniqid();
    
    for($i = 1; $i <= 10; $i++)
    {
      $html .= '<input class="star {split:2}" type="radio" name="avg_rating_' . $randId . '" disabled="disabled" ';
      if ($i == $checkedStar)
      {
        $html .= 'checked="checked" ';
      }
      $html .= '/>';
    }

    return $html;
  }
}