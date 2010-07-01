<?php if ($generator->hasGeneratedVersions()): ?>
  <div id="edit_versions">
  <h2><?php echo __('Version history', null, 'common') ?></h2>
  
  <?php   
    $hg = $generator->getHistoryGenerators();
    $cnt = count($hg);
    for ($i = $cnt; $i > 0; $i--)
    { 
      echo '<div class="edit_container">';
      echo '<br /><h4>' . ull_format_datetime($hg[$i - 1]->getUpdatedAt()) . '</h4>' .
            __('Version ', null, 'common') . $i . ' - ';
      if ($hg[$i - 1]->wasScheduledUpdate())
      {
        echo __('Scheduled update', null, 'common') . ' ' . __('by', null, 'common') . ' ' . $hg[$i - 1]->getScheduledUpdator();
      }
      else
      {
        echo __('by', null, 'common') . ' ' . $hg[$i - 1]->getUpdator();
      }
      echo '<br />';
      echo '<table class="edit_table"><tbody>';
      echo ($hg[$i - 1]->hasColumns()) ? $hg[$i - 1]->getForm() : '<tr><td>' . __('No changes') . '</td></tr>';
      echo '</tbody></table>';
      echo '</div>';
    }
  ?>
  </div>
<?php endif ?>