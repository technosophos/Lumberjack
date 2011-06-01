<?php
/** @file
 *
 * ParseJSON is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-06-01.
 */

/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class ParseJSON extends BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Parses JSON data')
      ->usesParam('json', 'The JSON data')
      ->whichIsRequired()
      //->usesParam('name', 'desc')
      //->withFilter('string')
      //->whichIsRequired()
      //->whichHasDefault('some value')
      ->andReturns('An associative array.')
    ;
  }

  public function doCommand() {
    return json_decode($this->param('json'), TRUE);
  }
}

