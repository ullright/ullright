<?php foreach ($mails as $mail): ?>

  <hr /><h1><?php echo $mail['id']?></h1>
  
  <ul>
    <li>Sent: <?php echo $mail['is_sent']?></li>
    <li>Created at: <?php echo $mail['created_at']?></li>
    <li>Updated at: <?php echo $mail['updated_at']?></li>
  </ul>
  
  <?php $msg = unserialize($mail['message']) ?>
  
  <pre><?php echo print_r($msg->getHeaders()->toString(), true) ?></pre>

  <pre>Is html? <?php echo print_r($msg->getIsHtml(), true) ?></pre>  
  <pre>Is queued? <?php echo print_r($msg->getIsQueued(), true) ?></pre>
  <pre>RecipientUllUserId: <?php echo print_r($msg->getRecipientUllUserId(), true) ?></pre>
  <pre>NewsletterEditonId: <?php echo print_r($msg->getNewsletterEditionId(), true) ?></pre>
  
  <pre><?php echo print_r($msg->getSubject(), true) ?></pre>
  
  <pre><?php echo print_r($msg->getHtmlBody(), true) ?></pre>
  
  <pre><?php echo print_r($msg->getPlaintextBody(), true) ?></pre>
  
<?php endforeach; ?>
  
  
