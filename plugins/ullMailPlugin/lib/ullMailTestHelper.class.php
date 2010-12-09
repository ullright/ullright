<?php
class ullMailTestHelper
{
  public static function createMail()
  {
    $message = new ullsfMail();

    $message->setFrom('user@example.com', 'Example user');
    $message->addAddress('test.user@example.com', 'Test user');
    $message->addCc('ccuser@example.com', 'CC test user');
    $message->addCc('ccuser2@example.com');
    $message->addBcc('bccuser@example.com', 'BCC test user');
    $message->setSubject('Example subject');
    $message->setBodies('I have an absolutely <em>amazing</em> body!', 'I am a boring plaintext body.');

    return $message;
  }
}