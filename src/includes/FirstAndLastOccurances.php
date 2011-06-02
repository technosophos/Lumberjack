<?php
/** @file
 *
 * FirstAndLastOccurances is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-06-01.
 */

/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class FirstAndLastOccurances extends MapReduceLogQuery {

  public function expects() {
    return $this
      ->description('Find the first and last occurrences of a given filter.')
      ->usesParam('filter', 'The filter to search for.')
      ->usesParam('destination', 'The name of the collection')
      ->andReturns('The name of the holding collection.')
    ;
  }

  protected function query() {
    return $this->param('filter', array());
  }
  
  /**
   * MongoCode object to execute for mapping.
   */
  protected abstract function map() {
    return MongoCode('function () {
      emit(this._id, {"date": this.date, "msg": this.message})
    }');
  }
  
  /**
   * MongoCode object to execute for reducing.
   */
  protected abstract function reduce() {
    return MongoCode('function (key, values) {
      
    }');
  }
  /**
   * The collection specifier for the collection the results should be written to.
   */
  protected abstract function out();
}

