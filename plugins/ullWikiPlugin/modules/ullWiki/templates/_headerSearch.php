<?php echo form_tag('ullWiki/list', 'class=inline name=ull_header_wiki_search_form'); ?>
  <?php echo $form ?>
  <?php echo ull_button_to_function(__('Search', null, 'common'), 'document.ull_header_wiki_search_form.submit();', 'ull_js_observer_confirm=true style=margin:0;') ?>
</form>