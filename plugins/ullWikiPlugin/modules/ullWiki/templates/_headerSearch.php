<form class="inline" name="ull_header_wiki_search_form" method="post" action="<?=url_for('ullWiki/list')?>">
  <?php echo $form['search']->render() ?>
  <?php echo $form['fulltext']->render() ?>
  <?php //echo ull_button_to_function(__('Search', null, 'common'), 'document.ull_header_wiki_search_form.submit();', 'ull_js_observer_confirm=true style=margin:0;') ?>
  <?php echo ull_link_to_function(ull_image_tag('search', null, null, null, 'ullWiki'), 'document.ull_header_wiki_search_form.submit();', 'ull_js_observer_confirm=true style=margin:0;') ?>
  <?php //echo ull_button_to_function('&gt;&gt;', 'document.ull_header_wiki_search_form.submit();', 'ull_js_observer_confirm=true style=margin:0;') ?>
</form>