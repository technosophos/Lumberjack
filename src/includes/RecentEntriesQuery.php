<?php

class RecentEntriesQuery extends StandardLogQuery {
  
  public function expects() {
    return $this
      ->description('Query for recent records (last 24 hours)')
      ->usesParam('filter', 'A Mongo query array. Time information will be automatically injected.')
      ->andReturns('A MongoCursor with the results.')
    ;
  }
  
  public function query(MongoCollection $collection) {
    
    $collection->ensureIndex(array('date' => 1));
    
    $query = $this->param('filter', array());
    
    $now = $_SERVER['REQUEST_TIME'];
    
    $yesterday = $now - (24 * 60 * 60);
    
    //$collection->find($query)->min(array('date' => $yesterday));
    $query['date'] = array('$gte' => $yesterday);
    
    print_r($query);
    return $collection->find($query)->sort(array('date' => 1));
  }
}