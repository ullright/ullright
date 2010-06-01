<div class="sidebar_section" id="sidebar_ticket">
  <h3><?php echo __('Custom', null, 'common') ?></h3>
  <ul class="sidebar_list">
    <?php echo ull_link_to( __('Active tickets', null, 'common'), 
      'ullFlow/list?app=trouble_ticket', 'ull_js_observer_confirm=true') ?>
  </ul>
</div>
