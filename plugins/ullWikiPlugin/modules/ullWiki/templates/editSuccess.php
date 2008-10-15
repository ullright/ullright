<script language="JavaScript">
    function setSaveMode(mode) {
      document.form1.save_mode.value = mode;
      document.form1.submit();
    }

    function saveshow() {
      setSaveMode('saveshow');
    }
</script>

<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>


<form name="form1" id="ull_wiki_form" method="post" action="<?=url_for('ullWiki/update')?>">

<?php echo $form['id']->render() ?>
<?php echo $form['docid']->render() ?>
<?php echo $form['edit_counter']->render() ?>
<?php echo $form['creator_user_id']->render() ?>
<?php echo $form['created_at']->render() ?>

<?php echo input_hidden_tag('save_mode', 'saveonly'); // saveonly, saveshow, ... ?>
<?php //echo input_hidden_tag('return_url', $return_url); ?>
<?php echo input_hidden_tag('return_var', $return_var); ?>

<table class='ull_wiki_edit'>
<tbody>

<!-- 
<tr>
  <td><b><?php //echo $form['cultures']->renderLabel() ?>:</b></td>
  <td>
    <?php 
      //weflowTools::printR($cultures); 
/*
      deprecated! do not use object_select_tag anymore
      echo object_select_tag($sf_data->getRaw('cultures'), 'getUllCultureId');
      //                                                       \________/
      //                                                           v 
      //                                                 =Model peer class name
*/
    ?>
  </td>
</tr>
 -->

<tr class='odd'>
  <td><b><?php echo $form['subject']->renderLabel(); ?>:</b></td>
  <td><?php echo $form['subject']->render(); ?></td>
</tr>


<tr>
  <td><b><?php echo $form['body']->renderLabel(); ?>:</b></td>
  <td><?php //echo $form['body']->render();
        echo object_textarea_tag($sf_data->getRaw('ullwiki'), 'getBody', array (
              'rich'   => 'fck',
              'size'   => '80x40',
              'config' => '../ullWikiPlugin/js/FCKeditor_config.js'));
    ?></td>
</tr>


<tr class='odd'>
  <td><b><?php echo $form['changelog_comment']->renderLabel(); ?>:</b></td>
  <td><?php echo $form['changelog_comment']->render(); ?></td>
</tr>

<tr>
  <td><b><?php #echo $form['tags']->renderLabel(); ?>:</b></td>
  <td>
    <?php
/*
      $tags_out = sfContext::getInstance()->getRequest()->getParameter('tags');
      if (!$tags_out) {
        //$tags = $sf_data->getRaw('ullwiki')->getTags(); //TODO
        $tags = Array();
        $tags_out = implode(', ', array_keys($tags));
      }
//        ullCoreTools::printR($tags);

      //ToDo: Values for tags fields
      echo $form['tags']->render(Array('value' => ''));
*/
      //$tags_pop = TagPeer::getPopulars(); //TODO
      $tags_pop = Array();
//        ullCoreTools::printR($tags);
      sfLoader::loadHelpers(array('Tags'));
      echo '<br />' . __('Popular tags') . ':';
      echo tag_cloud($tags_pop, 'addTag("%s")', array('link_function' => 'link_to_function'));
//        echo '<a href="#" onclick="addTag(\'dumm\')">add</a>';
      echo ull_js_add_tag();
    ?>
  </td>
</tr>  

</tbody>
</table>
<br />


<div class='action_buttons_edit'>
<fieldset>
  <legend><?php echo __('Actions', null, 'common') ?></legend>

  <div class='action_buttons_edit_left'>

    <ul>

		  <li>
	      <?php echo submit_tag(__('Save and show', null, 'common'),
	             array('name' => 'submit_saveshow')) ?>

        <script language="JavaScript">
          //document.getElementById('save').onclick = saveshow;
        </script>
	    </li>
      <li>
          <?php echo submit_tag(__('Save and close', null, 'common'),
               array('name' => 'submit_saveclose')) ?>
      </li>

    </ul>

  </div>    

  <div class='action_buttons_edit_right'>

    <ul>

      <li>
        <?php 
          echo ull_link_to_function(
            __('Save only', null, 'common')
            , 'setSaveMode("saveonly");'
          );
        ?>
        <?php echo submit_tag(__('Test Submit Button', null, 'common'),
                    Array('class' => 'button-as-link',
                          'name'  => 'submit_saveonly')) ?>
      </li>

      <li>
		    <?php
          echo ull_link_to(
            __('Cancel', null, 'common') 
            , $refererHandler->getReferer('edit')
            , 'ull_js_observer_confirm=true'
          );
		    ?>
      </li>
      <li>
		    <?php if ($ullwiki->getId()): ?>    
		      <?php 
		        echo link_to(
		          __('Delete', null, 'common'), 
		          'ullWiki/delete?docid='.$ullwiki->getDocid(), 
		          'confirm='.__('Are you sure?', null, 'common')
		          ); 
		      ?>
		    <?php endif; ?>
      </li>

    </ul>

  </div>

  <div class="clear"></div>  

</fieldset>

</div>


</form>

<?php
  echo ull_js_observer("ull_wiki_form");
//  ullCoreTools::printR($ull_form);
?>
