<?php $buttons = $sf_data->getRaw('buttons') ?>

<?php if (isset($buttons) && count($buttons)): ?>
  <?php foreach ($buttons as $button): ?>
        <li>
          <?php echo $button->render() ?>
        </li>
  <?php endforeach ?>
<?php endif?>