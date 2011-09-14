<?php $buttons = $sf_data->getRaw('buttons') ?>

<?php if (isset($buttons) && count($buttons)): ?>
  <?php foreach ($buttons as $button): ?>
    <?php if (!$button->isPrimary()):?>      
        <li class="<?php echo sfInflector::underscore(get_class($button)) ?>">
          <?php echo $button->render() ?>
        </li>
    <?php endif ?>
  <?php endforeach ?>
<?php endif ?>