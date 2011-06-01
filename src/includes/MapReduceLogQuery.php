<?php

class MapReduceLogQuery extends BaseFortissimoCommand {
  public function expects() {
    return $this
      ->description('Query the MongoDB log server using a map/reduce query.')
      ->andReturns('An array of matched entries.')
    ;
  }
  
  public function doCommand() {
    $db = $this->context->ds('db')->get();
    $collection = 'logs';
    
    $mapreduce = array(
      'mapreduce' => $collection,
      'map' => $this->map(),
      'reduce' => $this->reduce(),
      'query' => array()
    );
    
    return $db->command($mapreduce);
  }
  
  protected abstract function query();
  
  protected abstract function map();
  
  protected abstract function reduce();
}