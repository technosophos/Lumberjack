<?php
/** @file
 *
 * DumpMongoCollection is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-06-01.
 */

/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class DumpMongoCollection extends BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Dump the contents of an entire MongoDB collection')
      ->usesParam('collection', 'The name of the collection to dump.')
      ->whichIsRequired()
      //->withFilter('string')
      //->whichIsRequired()
      //->whichHasDefault('some value')
      ->andReturns('A string')
    ;
  }

  public function doCommand() {
    $db = $this->context->ds('db')->get();
    $collection = $db->selectCollection($this->param('collection'));
    
    $results = $collection->find();
    print_r(iterator_to_array($results));
    
  }
}

