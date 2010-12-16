<?php

/**
 * Configures the ullMail plugin. Handles global enabling/disable,
 * debug rerouting, bcc-to-every-mail and auditing/log functionality
 * in ullright.
 */
class ullMailPluginConfiguration extends sfPluginConfiguration
{
  /*
   * Note:
   * This class is instantiated multiple times for different
   * environments, however, its sfEventDispatcher object is always
   * the same, but not always in a clean state (why?). This can
   * result in multiple registered mailer.configure handlers pointing
   * to different plugin configuration instances, however, they in
   * turn use the given ullMailer object, which should always be the
   * same instance! Since the actual firing of the mailer.configure
   * event happens only just before the first mail is actually sent,
   * and that is usually the case in situations where the above scenario
   * does not occur, this behavior is not a problem at the moment,
   * but it is something that might need to be considered in the future.
   */
  public function initialize()
  {
    //mailer.configure is not fired during application/plugin initialization,
    //but only just before the first mail actually gets sent.
    //this behavior is beneficial to us: we modify the mailing configuration
    //(sfConfig variables) during testing. these changes apply after this
    //initialize() gets called, but before configureMailingSystem().
    $this->dispatcher->connect('mailer.configure', array($this, 'configureMailingSystem'));
  }

  
  /**
   * Method to be invoked by mailer.configure event
   * 
   * @param sfEvent $event
   */
  public function configureMailingSystem(sfEvent $event)
  {
    $mailer = $event->getSubject();
    
    //regardless of the transport configuration in factories.yml,
    //we override it with "old-style" values read from app.yml
    $this->overrideMailerTransport($mailer);

    $this->enableMailPersonalization($mailer);
    
    $this->enableMailHtml2TextTransformation($mailer);
    
    $this->enableMailAuditing($mailer);

    //is mailing completely disabled?
    if (!sfConfig::get('app_mailing_enable', false))
    {
      $this->disableMailDelivery($mailer);
    }

    //is 'add debug address as bcc to every mail'  enabled?
    if (sfConfig::get('app_mailing_send_debug_cc', false))
    {
      $this->enableAddDebugToBcc($mailer);
    }
    
    //is debug routing enabled?
    if (sfConfig::get('app_mailing_reroute', true))
    {
      $this->enableMailRerouting($mailer);
    }
  }

  
  /**
   * This function reconfigures the realtime transport of the
   * mailer based on "old-style" configuration values in app.yml,
   * i.e. the ones symfony used before adopting Swift Mailer.
   *
   * @param sfEvent $event
   * @throws UnexpectedValueException if app_mailing_mailer is not 'sendmail', 'mail' or 'smtp'
   */
  public function overrideMailerTransport(sfMailer $mailer)
  {
    $mailerType = sfConfig::get('app_mailing_mailer', 'sendmail');
    switch ($mailerType)
    {
      case 'smtp':
        $hostname = sfConfig::get('app_mailing_smtp_hostname', 'localhost');
        $port = sfConfig::get('app_mailing_smtp_port', 25);
        $username = sfConfig::get('app_mailing_smtp_username');
        $password = sfConfig::get('app_mailing_smtp_password');

        $transport = new Swift_SmtpTransport($hostname, $port);
        $extensionHandlers = $transport->getExtensionHandlers();
        foreach ($extensionHandlers as $extensionHandler)
        {
          if ($extensionHandler instanceof Swift_Transport_Esmtp_AuthHandler)
          {
            $extensionHandler->setUsername($username);
            $extensionHandler->setPassword($password);
          }
        }
        break;

      case 'sendmail':
        $transport = new Swift_SendmailTransport();
        break;

      case 'mail':
        $transport = new Swift_MailTransport();
        break;

      default:
        throw new UnexpectedValueException("app_mailing_mailer must be 'smtp', 'sendmail' or 'mail'");
    }

    $mailer->setRealtimeTransport($transport);
  }

  /**
   * Enable personalization
   * 
   * @param sfMailer $mailer
   */
  public function enableMailPersonalization(sfMailer $mailer)
  {
    $plugin = new Swift_Plugins_ullPersonalizePlugin();
    $plugin->setPriority(5);

    $mailer->getRealtimeTransport()->registerPlugin($plugin);
  }
  
  
  /**
   * Enable automatichtml to text transformation for html emails
   * 
   * @param sfMailer $mailer
   */
  public function enableMailHtml2TextTransformation(sfMailer $mailer)
  {
    $plugin = new Swift_Plugins_ullHtml2TextPlugin();
    $plugin->setPriority(6);

    $mailer->getRealtimeTransport()->registerPlugin($plugin);
  }
  
  /**
   * Enables mail auditing (= logging of sent mails to the database).
   * Uses the Swift_Plugins_ullAuditPlugin class.
   * 
   * @param sfMailer $mailer
   */
  public function enableMailAuditing(sfMailer $mailer)
  {
    $auditingPlugin = new Swift_Plugins_ullAuditPlugin();
    $auditingPlugin->setPriority(9);

    $mailer->getRealtimeTransport()->registerPlugin($auditingPlugin);
  }

  
  /**
   * Disables the delivery of mails by registering a
   * Swift_Plugins_ullDisableMailingPlugin at the realtime
   * transport.
   *
   * @param sfMailer $mailer
   */
  public function disableMailDelivery(sfMailer $mailer)
  {
    //create a mail-swallowing, transport-start-preventing disabling plugin
    $disablingPlugin = new Swift_Plugins_ullDisableMailingPlugin();

    //this plugin should be the last in the chain => high priority
    $disablingPlugin->setPriority(10);

    //and tell the realtime transport to use it
    //$mailer->getTransport()->registerPlugin($disablingPlugin);
    $mailer->getRealtimeTransport()->registerPlugin($disablingPlugin);
  }

  
  /**
   * Enables mail rerouting to a single debug address. Uses the
   * Swift_Plugins_RedirectingPlugin.
   *
   * The debug address is read from app.yml (app_mailing_debug_address).
   *
   * @param sfMailer $mailer
   * @throws UnexpectedValueException in case of an invalid debug address.
   */
  public function enableMailRerouting(sfMailer $mailer)
  {
    //the Swift_Plugins_RedirectingPlugin does not support priority
    $this->enableDebugAddressPlugin($mailer, null, 'Swift_Plugins_RedirectingPlugin');
  }

  
  /**
   * Registeres a plugin which includes a single debug address in
   * the BCC list of every mail sent. Uses the Swift_Plugins_ullAddToBccPlugin.
   *
   * The debug address is read from app.yml (app_mailing_debug_address).
   * If a mail has a slug which is in the slug exclusion list
   * (app_mailing_debug_cc_exclude_list, also in app.yml), it will not be
   * modified.
   *
   * @param sfMailer $mailer
   * @throws UnexpectedValueException in case of an invalid debug address.
   */
  public function enableAddDebugToBcc(sfMailer $mailer)
  {
    $slugExcludeList = sfConfig::get('app_mailing_debug_cc_exclude_list', array());

    $this->enableDebugAddressPlugin($mailer, -5, 'Swift_Plugins_ullAddToBccPlugin', $slugExcludeList);
  }

  
  /**
   * Helper function (used by enableMailRerouting and enableAddDebugToBcc)
   * to create and register a plugin (class name is given) to the
   * mailer's realtime transport.
   *
   * The plugin constructor will receive the configured debug address
   * and an additional parameter, if given.
   *
   * @param sfMailer $mailer
   * @param string $pluginClassName the class name of a Swift Mailer plugin
   * @param mixed $additionalParam an additional parameter which gets passed to the plugin
   * @throws UnexpectedValueException in case of an invalid debug address.
   */
  protected function enableDebugAddressPlugin(sfMailer $mailer, $priority, $pluginClassName, $additionalParam = null)
  {
    //read mailing debug address from config and validate it
    $mailValidator = new sfValidatorEmail();
    try
    {
      $debugAddress = $mailValidator->clean(sfConfig::get('app_mailing_debug_address'));
    }
    catch (sfValidatorError $e)
    {
      throw new UnexpectedValueException('The configured debug address for mailing is invalid.');
    }

    //create the plugin for the set debug address
    $plugin = ($additionalParam !== null) ?
    new $pluginClassName($debugAddress, $additionalParam) :
    new $pluginClassName($debugAddress);

    //can we set the priority?
    if ($plugin instanceof Swift_Plugins_ullPrioritized)
    {
      $plugin->setPriority($priority);
    }
    
    //and tell the realtime transport to use it
    $mailer->getRealtimeTransport()->registerPlugin($plugin);
  }
}
