          <?php echo form_tag('ullwiki/list', 'class=inline'); ?>
            <?php echo input_tag('search', '', 'size=8'); ?>
            <?php echo submit_tag(__('Search', null, 'common'), 'style=margin: 0;') ?>
            <?php echo input_hidden_tag('fulltext',1); ?> 
            <?php //echo __('Full text'); ?>         
            <?php //echo javascript_tag('document.getElementById("search").focus();'); ?>   
          </form>  