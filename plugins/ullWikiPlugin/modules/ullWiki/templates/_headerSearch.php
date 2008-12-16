<form class="inline" name="ull_wiki_filter_form_header" method="post" action="<?=url_for('ullWiki/list')?>">
  <?php echo $form['search']->render() ?>
  
  <?php if ($sf_user->getAttribute('has_javascript')): ?>
    <?php echo ull_link_to_function(ull_image_tag('search', array('alt' => 'search_header', 'class' => 'image_align_middle'), null, null, 'ullWiki'), 'document.ull_wiki_filter_form_header.submit();', 'ull_js_observer_confirm=true style=margin:0;') ?>
  <?php else: ?>
    <?php echo submit_image_tag(ull_image_path('search', null, null, 'ullWiki'),
              array('alt' => 'search_header')) ?>
  <?php endif; ?>
</form>