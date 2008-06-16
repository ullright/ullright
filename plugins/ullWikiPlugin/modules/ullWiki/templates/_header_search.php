<?php echo form_tag('ullwiki/list', 'class=inline name=ull_header_wiki_search_form'); ?>
  <?php echo input_tag('search', '', 'size=8'); ?>
  <?php //echo ull_button_to(__('Search', null, 'common'), 'document.ull_header_wiki_search_form.submit();', 'ull_js_observer_confirm=true ull_js_observer_function=true style=margin:0;') ?>
  <?php echo ull_button_to_function(__('Search', null, 'common'), 'document.ull_header_wiki_search_form.submit();', 'ull_js_observer_confirm=true style=margin:0;') ?>
  <?php echo input_hidden_tag('fulltext',1);?> 
  <?php //echo __('Full text'); ?>         
  <?php //echo javascript_tag('document.getElementById("search").focus();'); ?>   
</form>