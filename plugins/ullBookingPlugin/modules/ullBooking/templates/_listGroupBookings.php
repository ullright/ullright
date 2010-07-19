<?php foreach ($bookings as $booking): ?>
  <?php $label = $booking->formatDateRange(true); ?>
  <?php echo (($booking->id == $id) ? '<em>' . $label . '</em>' : $label); ?>
  <br />
<?php endforeach; ?>
