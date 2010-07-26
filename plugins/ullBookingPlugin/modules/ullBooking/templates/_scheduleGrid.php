<div id="booking_schedule_cells">  

  <!-- header row with characters for resources -->
  <div class="booking_schedule_row">
    <span class="booking_schedule_time_cell booking_schedule_cell"></span>
    <?php for ($i = 0; $i < count($cell_status); $i++) : ?>
      <span class="booking_schedule_header_cell booking_schedule_cell">
        <?php echo chr($i + ord('A')); ?>
      </span>
    <?php endfor; ?>
  </div>
  
  <!-- data rows -->
  <?php $intervals = ullCoreTools::getTimeIntervalList($start_hour, $end_hour, 15); ?>
  <?php $indexOffset = $start_hour * (60 / 15); ?>
  
  <?php
    //last time interval is rendered later on
    for ($i = 0; $i < (count($intervals) - 1); $i++) : ?>
      <div class="booking_schedule_row">
      
        <?php //render time cell ?>
        <span class="booking_schedule_time_cell booking_schedule_cell"><?php echo $intervals[$i]; ?></span>
        
        <?php //render data cells (free/occupied) ?>
        <?php for ($j = 0; $j < count($cell_status); $j++) : ?>
          <?php
            $occupied = (isset($cell_status[$j][$i + $indexOffset])) ? true : false;
            $cell_classes  = 'booking_schedule_cell booking_schedule_cell_';
            $cell_classes .= ($occupied) ? 'occupied booking_schedule_cell_' .
              $cell_status[$j][$i + $indexOffset]['cellType'] : 'free';
          ?>
          <span class="<?php echo $cell_classes; ?>"<?php
            echo ($occupied) ? ' title="' . $cell_status[$j][$i + $indexOffset]['bookingName'] . '"' : ''; ?>></span> 
        <?php endfor; ?>
      </div>
    <?php endfor; ?>
  
  <?php //render one additional row with only a single time cell ?>
  <div class="booking_schedule_row">
    <span class="booking_schedule_time_cell booking_schedule_cell"><?php echo $intervals[$i]; ?></span>
  </div>
  
</div>
