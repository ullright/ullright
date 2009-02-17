<?php

class inactiveUserFilter extends sfFilter
{
  public function execute($filterChain)
  {
    if ($this->isFirstCall()) {
      $context = $this->getContext();
      $sfuser = $context->getUser();
      $userId = $sfuser->getAttribute('user_id');

      if ($userId)
      {
        $user = Doctrine::getTable('UllUser')->findOneById($userId);
        if (!($user->UllUserStatus->getIsActive()))
        {
          //can't use ullUser/logout since that would create
          //an infinite loop, we have to do it manually
          $sfuser->setAttribute('user_id', 0);
          $sfuser->setAttribute('has_javascript', false);
          return $context->getController()->redirect('ullUser/login');
        }
      }
    }
    $filterChain->execute();
  }
}