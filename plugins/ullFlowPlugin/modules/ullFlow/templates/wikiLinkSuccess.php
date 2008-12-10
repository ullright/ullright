<?php

  echo '<h1>' . __('Manage Wiki Links', null, 'common') . '</h1><br />';

//  echo ull_reqpass_form_tag(array('action' => 'wikiLink'), 'id=ull_flow_wiki_link_form');
//  
//  echo input_hidden_tag('external_field', $external_field);
//  echo input_hidden_tag($external_field, $value);
//  echo input_hidden_tag('app', $app);
//  echo input_hidden_tag('doc', $doc);
//  echo input_hidden_tag('ull_flow_action', $ull_flow_action);
//  echo input_hidden_tag('delete');

  if ($value) 
  {
    echo ullWidgetWikiLink::renderWikiLinkList($value, true);
  } 
?>

<br />
<h3><?php echo __('Create Link', null, 'common'); ?>:</h3>
  
<ul>
  <li>
    <?php
      echo ull_button_to(__('Link to new wiki document', null, 'common'), 
        'ullWiki/create?return_var=ull_wiki_doc_id');
    ?>
  </li> 
  
  <li>
    <?php 
      echo  ull_button_to(__('Link to existing wiki document', null, 'common'),
        'ullWiki/index?return_var=ull_wiki_doc_id');
    ?>
  </li>
</ul>
  
  <br /><br />
  
  <div class='action_buttons'>
    
    <div class='action_buttons_left'>
      <?php echo ull_button_to(__('Save and close'), 'ullFlow/edit?doc=' . $doc->id) ?>
      <?php //echo button_to_function(__('Save and close', null, 'common'), 'return_to()'); ?>
    </div>
    
    <div class="clear"></div>
  </div>
  
  </form>
  
<?php
  /*
  echo javascript_tag('
    function return_to() {
      document.getElementById("ull_flow_wiki_link_form").action = "' . url_for('ullFlow/update') . '"
      document.getElementById("external_field").value = "";
      document.getElementById("ull_flow_wiki_link_form").submit();
    }
  ');
  
  echo javascript_tag('
    function delete_line(line_num) {
      if (confirm("' . __('Are you sure?', null, 'common') . '")) { 
        document.getElementById("delete").value = line_num;
        document.getElementById("ull_flow_wiki_link_form").submit();
      }
    }
  ')
    */  
?>