<li>
  <?php if ($language != 'en'): ?> 
    <?php echo ull_link_to('English', 'ullUser/changeCulture?culture=en', 'ull_js_observer_confirm=true') ?>
  <?php else: ?>
    <?php echo ull_link_to('Deutsch', 'ullUser/changeCulture?culture=de', 'ull_js_observer_confirm=true') ?>
  <?php endif ?>
</li>