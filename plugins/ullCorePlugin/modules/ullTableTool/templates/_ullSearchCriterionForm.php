<form id="searchForm" action="<?php echo url_for($formUrl) ?>" method="post">
<?php
  //This input tag is manually hidden and should not be visible.
  //It's there because most modern browsers activate the first
  //submit button of a form when the user hits enter, and
  //without this button here, that would be a delete-criterion
  //submit button!
  
  echo submit_tag(__('Search', null, 'common'), array('name' => 'searchSubmit', 'style' => 'display:none'));
?>
<br />
<div class="edit_container">
<table class="edit_table">
  <thead>
    <tr>
      <th class="color_dark_bg">&nbsp;</th>
      <th class="color_dark_bg"><?php echo __('Criterion', null, 'common')?></th>
      <th class="color_dark_bg"><?php echo __('Not', null, 'common')?></th>
      <th class="color_dark_bg"><?php echo __('Value', null, 'common')?></th>
    </tr>
  </thead>
  <tbody>
  <?php

  $suppressNewRow = false;
  $errorSave = null;
  
  foreach ($form->getWidgetSchema()->getPositions() as $position)
  {
    $formField = $form->offsetGet($position);

    if (!$suppressNewRow)
    {
      echo '<tr>';
      echo '<td>';
      echo submit_image_tag(ull_image_path('delete'), array('name' => 'removeSubmit_' . $formField->getName())); 
      echo '</td>';
      echo '<td>';
      echo $formField->renderLabel();
      echo '</td>';

      echo '<td>';
      echo checkbox_tag('fields[not_' . $formField->getName() . ']', null, false);
      echo '</td>';

      echo '<td>';
      echo $formField->render();
    }
    else
    {
      echo '&nbsp;' . '&nbsp;' . $formField->renderLabel() . '&nbsp;';
      echo $formField->render();
    }

    if (strpos($formField->getName(), 'rangeFrom') === 0
        || strpos($formField->getName(), 'rangeDateFrom') === 0)
    {
      $suppressNewRow = true;
    }
    else
    {
      echo '</td></tr>';
      $suppressNewRow = false;
    }

    if ($errorSave != null)
    {
      echo '<td>';
      echo $errorSave;
      echo '</td>';
      $errorSave = null;
    }
    
    if ($formField->hasError() || $errorSave != null)
    {
      if ($suppressNewRow)
      {
        $errorSave = $formField->renderError();
      }
      else
      {
        echo '<td>';
        echo $formField->renderError();
        echo '</td>';
      }
    }
  }

  ?>
  </tbody>
</table>

<?php include_partial('ullTableTool/ullSearchAddCriteriaForm', array('addCriteriaForm' => $addCriteriaForm)) ?>

<div class='edit_action_buttons color_light_bg'>
<h3><?php echo __('Actions', null, 'common')?></h3>

  <div class='edit_action_buttons_left'>
    <ul>
      <li>
      <?php echo submit_tag(__('Search', null, 'common'), array('name' => 'searchSubmit')) ?>
      </li>
    </ul>
  </div>

  <div class='edit_action_buttons_right'>
    <ul>
      <li>
      <?php echo ull_link_to(__('Reset search', null, 'common'), $moduleName. '/resetSearchCriteria') ?>
      </li>
    </ul>
  </div>

  <div class="clear"></div>

</div>

</div> <!-- end of edit_container -->

</form>

<?php use_javascripts_for_form($form) ?>
<?php use_stylesheets_for_form($form) ?>