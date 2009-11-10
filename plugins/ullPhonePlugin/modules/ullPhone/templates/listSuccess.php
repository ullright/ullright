<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>
<?php $generator = $sf_data->getRaw('generator') ?>

<?php //echo '<h3>Phone book</h3>' ?>

<?php //echo $ull_filter->getHtml(ESC_RAW) ?>

<?php //echo ull_form_tag(array('page' => '', 'filter' => array('search' => ''))) ?>
<!--></form><-->
    
<?php // detect empty table_tool ?>
<?php if ($generator->getRow()->exists()): ?>
  <table class='list_table'>
  
  <?php include_partial('ullTableTool/ullResultListHeader', array(
      'generator'   => $generator,
      'order'       => $order,
      'order_dir'   => $order_dir,
      'add_icon_th' => false,
  )); ?>
  
  <!-- data -->
  
  <tbody>
  <?php
    $colspanNumber = count($generator->getActiveAutoRenderedColumns());
  
    //if a single location is set, display the header immediately
    if (isset($location))
    {
      include_partial('locationHeader',
        array('location' => $location, 'colspanNumber' => $colspanNumber));
    }
  
    $previousLocationId = '';
    $odd = false;
    
    foreach($generator->getForms() as $row => $form)
    {
      //if this record has a different id than the previous,
      //display a location header
      //we only do this if there are multiple locations
      //and if location view is enabled
      if ($isLocationView && empty($location))
      {
        $currentLocationId = $form['ull_location_id']->getValue();
        if ($previousLocationId !== $currentLocationId)
        {
          $currentLocationArray = empty($currentLocationId) ?
            null : Doctrine::getTable('UllLocation')
                    ->findOneById($currentLocationId,  Doctrine::HYDRATE_ARRAY);
            
          include_partial('locationHeader',
            array('location' => $currentLocationArray, 'colspanNumber' => $colspanNumber));
         
          $previousLocationId = $currentLocationId;
          $odd = true;
        }
      }
      
      ($odd) ? $odd = '' : $odd = 'class="odd"';
      echo '<tr ' . $odd . '>';
      
      $renderColumnsKeys = array_keys($generator->getActiveAutoRenderedColumns());
      foreach ($form as $widgetKey => $widget)
      {
        if (in_array($widgetKey, $renderColumnsKeys))
        {
          echo $widget->renderRow();
        }
      }
      
      echo '</tr>';
    }
  ?>
  
  </tbody>
  </table>
<?php endif ?>

<?php include_partial('ullTableTool/ullPagerBottom',
        array('pager' => $pager)
      ); ?>