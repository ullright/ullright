<?php $buttons = $sf_data->getRaw('buttons') ?>

<?php if (isset($buttons) && count($buttons)): ?>
  <?php foreach ($buttons as $button): ?>
    <?php if (!$button->isPrimary()):?>      
        <li>
          <?php echo $button->render() ?>
        </li>
    <?php endif ?>
  <?php endforeach ?>
<?php endif ?>