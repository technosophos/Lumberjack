<?php

abstract class MapReduceLogQuery extends BaseFortissimoCommand {
  public function expects() {
    return $this
      ->description('Query the MongoDB log server using a map/reduce query.')
      ->andReturns('The name of the collection in which the results are stored.')
    ;
  }
  
  public function doCommand() {
    $db = $this->context->ds('db')->get();
    $collection = 'logs';
    
    $out = $this->out();
    
    $mapreduce = array(
      'mapreduce' => $collection,
      'map' => $this->map(),
      'reduce' => $this->reduce(),
      'query' => $this->query(),
      'out' => $out,
    );
    
    $db->command($mapreduce);
    
    return is_array($out) ? current($out) : $out;
  }
  
  /**
   * Query array.
   */
  protected abstract function query();
  
  /**
   * MongoCode object to execute for mapping.
   */
  protected abstract function map();
  
  /**
   * MongoCode object to execute for reducing.
   */
  protected abstract function reduce();
  
  /**
   * The collection specifier for the collection the results should be written to.
   */
  protected abstract function out();
}