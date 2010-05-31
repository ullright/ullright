<?php

/**
 * Check on each request if the current user has been deactivated
 * 
 * @author klemens.ullmann-marx@ull.at
 */
class inactiveUserFilter extends sfFilter
{
  public function execute($filterChain)
  {
    if ($this->isFirstCall()) 
    {
      $sfUser = $this->getContext()->getUser();
      $userId = $sfUser->getAttribute('user_id');
      
      if ($userId && !UllUserTable::isActiveByUserId($userId))
      {
        $sfUser->setAttribute('user_id', 0);
        $sfUser->setAttribute('has_javascript', false);
        
        return $this->getContext()->getController()->redirect('ullUser/login');
      }
    }
    
    $filterChain->execute();
  }
}