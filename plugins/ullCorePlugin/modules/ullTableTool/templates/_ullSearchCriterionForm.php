<form id="searchForm" action="<?php echo url_for($formUrl) ?>" method="post">
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
      echo  ull_link_to(ull_image_tag('delete'), $moduleName . '/removeSearchCriteria?criteriaName=' . $formField->getName());
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

    if (strncmp($formField->getName(), 'rangeFrom', strlen('rangeFrom')) == 0)
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

<br />

<div class='edit_action_buttons color_light_bg'>
<h3><?php echo __('Actions', null, 'common')?></h3>

<div class='edit_action_buttons_left'><?php

?>
<ul>
  <li><?php echo submit_tag(__('Search', null, 'common')) ?></li>
  <?php

  ?>
</ul>
</div>

<div class='edit_action_buttons_right'>
<ul>
  <li><?php echo ull_link_to(__('Reset search', null, 'common'), $moduleName. '/resetSearchCriteria') ?>
  </li>
</ul>
</div>

<div class="clear"></div>

</div>
</div>
</form>

<br />
<br />
<?php include_partial('ullTableTool/ullSearchAddCriteriaForm', array('addCriteriaForm' => $addCriteriaForm, 'moduleName' => $moduleName)) ?>
