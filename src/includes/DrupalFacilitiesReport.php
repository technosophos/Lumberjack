<?php
/** @file
 *
 * DrupalFacilitiesReport is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-06-01.
 */

/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class DrupalFacilitiesReport extends MapReduceLogQuery {

  public function expects() {
    return $this
      ->description('Reports on number of errors per facility')
      ->usesParam('since', 'A timestamp indicating what the oldest logs should be.')
        ->whichHasDefault(0)
      ->usesParam('until', 'A timestamp indicating what the newest logs should be.')
        ->whichHasDefault(0)
      //->usesParam('name', 'desc')
      //->withFilter('string')
      //->whichIsRequired()
      //->whichHasDefault('some value')
      ->andReturns('An array of facility data.')
    ;
  }
  
  protected function query() {
    
    $db = $this->context->ds('db')->get();
    $collection = $db->selectCollection('logs');
    $collection->ensureIndex(array('type', 'date'));
    
    $query = array('type' => 'drupal');
    
    $since = strtotime($this->param('since', 0));
    $until = strtotime($this->param('until', 0));
    if ($since > 0) {
      $query['date']['$gte'] = $since;
    }
    if ($until > 0) {
      $query['date']['$lte'] = $until;
    }
    
    return $query;
  }
  
  protected function map() {
    $js = new MongoCode('function () {
      emit(this.facility, 1);
    }');
    
    return $js;
  }
  
  protected function reduce() {
    $js = new MongoCode('function (key, values) {
      var counter = 0;
      values.forEach(function(value) {
        counter += value;
      });
      
      
      
      return counter;
    }');
    
    return $js;
  }


  protected function out() {
    
    return array('replace' => $this->name . '-' . __CLASS__);
  }
}

