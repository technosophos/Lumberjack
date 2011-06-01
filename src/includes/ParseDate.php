<?php
/** @file
 *
 * ParseDate is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-06-01.
 */

/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class ParseDate extends BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Parse a date string into a timestamp')
      ->usesParam('date', 'The date string')
      //->withFilter('string')
      //->whichIsRequired()
      //->whichHasDefault('some value')
      ->andReturns('The time stamp')
    ;
  }

  public function doCommand() {
    print $this->param('date', 'now') . PHP_EOL;
    return strtotime($this->param('date', 'now'));
  }
}

