<?php

/**
 * This class is a plugin for Swift Mailer which swallows mail (like
 * the Swift_Plugins_BlackholePlugin) but also cancels transport
 * start/stop events. This is necessary e.g. to prevent connections to
 * the MTA during testing.
 */
class Swift_Plugins_ullDisableMailingPlugin extends Swift_Plugins_ullPrioritizedPlugin
  implements Swift_Events_SendListener,
             Swift_Events_TransportChangeListener
{
  /**
   * Invoked just before a Transport is started.
   * @param Swift_Events_TransportChangeEvent $evt
   */
  public function beforeTransportStarted(Swift_Events_TransportChangeEvent $evt)
  {
    $evt->cancelBubble();
  }

  /**
   * Invoked just before a Transport is stopped.
   * @param Swift_Events_TransportChangeEvent $evt
   */
  public function beforeTransportStopped(Swift_Events_TransportChangeEvent $evt)
  {
    //does this even happen if we always cancel the starting of the transport?
    $evt->cancelBubble();
  }

  /**
   * Invoked immediately before the Message is sent.
   * @param Swift_Events_SendEvent $evt
   */
  public function beforeSendPerformed(Swift_Events_SendEvent $evt)
  {
    $evt->cancelBubble();
  }

  //The three events below are there to fulfill the interface contracts
  
  public function sendPerformed(Swift_Events_SendEvent $evt)
  {
    throw new RuntimeException('Sending should never have been performed');
  }

  public function transportStarted(Swift_Events_TransportChangeEvent $evt)
  {
    throw new RuntimeException('Transport should never have started');
  }

  public function transportStopped(Swift_Events_TransportChangeEvent $evt)
  {
    throw new RuntimeException('Transport should never have stopped');
  }
}

