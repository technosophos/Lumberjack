<?php
/** @file
 *
 * DoLogQuery is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-06-01.
 */

/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class SimpleLogQuery extends StandardLogQuery {

  public function expects() {
    return $this
      ->description('Executes a query with the given filter')
      ->usesParam('filter', 'The MongoDB filter array')
      ->whichIsRequired()
      
      ->usesParam('fields', 'The list of fields to return')
      ->usesParam('sort', 'The sort fields as an associative array')
      
      ->usesParam('limit', 'Integer indicating the max number of items to return')
      ->withFilter('number_int')
      ->whichHasDefault(0)
      
      ->usesParam('skip', 'Integer indicating the offset')
      ->withFilter('number_int')
      ->whichHasDefault(0)
      
      ->usesParam('collection', 'The name of the collection, if this differs from the default.')
      ->withFilter('string')
      
      
      //->usesParam('name', 'desc')
      //->withFilter('string')
      //->whichIsRequired()
      //->whichHasDefault('some value')
      ->andReturns('A MongoCursor (Iterable) with the data.')
    ;
  }

  public function query(MongoCollection $collection) {
    $query = $this->param('filter', array());
    $fields = $this->param('fields', NULL);
    
    // MongoCollection won't accept a NULL for the second param, and array() has special meaning.
    if (empty($fields)) {
      $results = $collection->find($query);
    }
    else {
      $results = $collection->find($query, $fields);
    }
    
    
    $sort = $this->param('sort', NULL);
    $limit = $this->param('limit', NULL);
    $skip = $this->param('skip', NULL);
    
    if (!empty($sort)) {
      $results->sort($sort);
    }
    
    if (!empty($skip)) {
      $results->skip($skip);
    }
    
    if (!empty($limit)) {
      $results->limit($limit);
    }
    
    return $results;
  }
  
  public function collectionName() {
    $collectionName = $this->param('collection', NULL);
    if (empty($collectionName)) {
      $collectionName = parent::collectionName();
    }
    
    return $collectionName;
  }
}

