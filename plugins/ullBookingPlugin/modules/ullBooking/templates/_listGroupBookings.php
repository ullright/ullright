<?php $twoColumns = (count($bookings) > 20) ? true : false; $newLine = true; ?>
  
<?php foreach ($bookings as $booking): ?>
  <?php $label = $booking->formatDateRange(true); ?>
  <?php echo (($booking->id == $id) ? '<em>' . $label . '</em>' : $label); ?>
  
  <?php if ($twoColumns) : ?>
    <?php echo ($newLine = !$newLine) ? '<br />' : '&nbsp;&nbsp;'; ?>
  <?php else : ?>
    <br />
  <?php endif; ?>
<?php endforeach; ?>

