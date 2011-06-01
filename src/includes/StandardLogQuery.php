<?php

abstract class StandardLogQuery extends BaseFortissimoCommand {
  public function expects() {
    return $this
      ->description('Query the MongoDB log server using a map/reduce query.')
      ->andReturns('An array of matched entries.')
    ;
  }
  
  public function doCommand() {
    $db = $this->context->ds('db')->get();
    $collection = $db->selectCollection($this->collectionName());
    
    return $this->query($collection);
  }
  
  public function collectionName() {
    return 'logs';
  }
  
  /**
   * Execute a MongoDB query.
   */
  abstract function query(MongoCollection $collection);

}