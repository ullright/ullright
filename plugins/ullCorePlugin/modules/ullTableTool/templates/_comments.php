<?php foreach($comments as $comment): ?>

  <div class="comment">
  
    <div class="comment_photo">
      <?php if ($comment['deleted_status'] == 'no') : ?>
        <?php echo $photo_widget->render('photo', $comment['Commenter']['photo'], ESC_RAW) ?>
      <?php endif ?>    
    </div>
  
    <div class="comment_content">
      <?php if ($comment['deleted_status'] == 'no') : ?>
        <div class="comment_author">
          <?php if ($comment['Commenter']['id'] == $sf_user->getAttribute('user_id') || $has_revoke_permission): 
          ?>
            <?php echo link_to(ull_image_tag('delete'), 'ullClimbingRouteDB/revokeComment?id=' . $comment['id']) ?>
          <?php endif; ?>
          
          <?php echo $comment['Commenter'] ?>
        </div>
        <div class="comment_date">        
          <?php echo ull_format_datetime($comment['posted_date']) ?> 
        </div>
        <?php if ($subject = $sf_data->getRaw('subject')): ?>
          <div class="comment_date">
            <?php echo ($subject instanceof sfCallable) ? 
              $subject->call($comment) :
              $subject ?>        
          </div>
        <?php endif ?>
      <?php endif; ?>
      <div class="comment_body">
        <?php echo nl2br($comment['comment']) ?>
      </div>
    </div>
    
  </div>

<?php endforeach ?>