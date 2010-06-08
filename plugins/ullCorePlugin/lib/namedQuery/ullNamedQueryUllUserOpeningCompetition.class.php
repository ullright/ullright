<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllUserOpeningCompetition extends ullNamedQuery
{
  public function configure()
  {
    $this->name       = 'Teilnehmer am Eröffnungsbewerb';
    $this->identifier = 'opening_competition';
  }
  
  public function modifyQuery($q)
  {
    $q->addWhere('x.has_registered_for_opening_competition = ?', true);
  }
}