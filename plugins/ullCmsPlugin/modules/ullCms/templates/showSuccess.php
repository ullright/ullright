<?php slot('sidebar') ?>

<div id="sidebar_ull_cms">
  <div class="sidebar_section">
    <ul class="sidebar_list">
      <li>Über uns</li>
      <li>
        <ul class="sidebar_list">
          <li>Team</li>
        </ul>
      </li>
    </ul>
    
    <?php echo $sidebar_navigation ?>
    
  </div>
</div>

<?php end_slot() ?>

<?php echo $main_navigation ?>

<h3><?php echo $doc->title ?></h3>

<?php echo $doc->getBody(ESC_RAW) ?> 