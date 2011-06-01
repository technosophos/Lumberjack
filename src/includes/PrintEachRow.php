<?php
/** @file
 *
 * PrintEachRow is a BaseFortissimoCommand class.
 *
 * Created by Matt Butcher on 2011-06-01.
 */

/**
 * A Fortissimo command.
 *
 * @author Matt Butcher
 */
class PrintEachRow extends BaseFortissimoCommand {

  public function expects() {
    return $this
      ->description('Iterate a collection, format and print each row.')
      ->usesParam('iterable', 'The Iterable object or array')
      ->whichIsRequired()
      ->usesParam('format', 'The format string to run. See the sprintf() manual page.')
      ->whichIsRequired()
      ->usesParam('fields', 'The fields in each record that should be printed. These can be referenced by index (starting at 1) or placed in order. It is assumed that each item in the iterator will have the fields listed here.')
      ->whichHasDefault(array())
      //->usesParam('separator', 'Record separator between rows. Default is a newline.')
      //->whichHasDefault(PHP_EOL)
      //->withFilter('string')
      //->whichIsRequired()
      //->whichHasDefault('some value')
      ->andReturns('Nothing. Results are printed as iterated.')
    ;
  }

  public function doCommand() {
    
    $rows = $this->param('iterable');
    $format = $this->param('format');
    $this->format_fields = $this->param('fields');
    //$sep = $this->param('separator', PHP_EOL);
    
    $buffer = array();
    foreach ($rows as $row) {
      $values = $this->prepareFields($row);
      vprintf($format, $values);
    }

  }
  
  protected function prepareFields($data) {
    $values = array();
    
    foreach ($this->format_fields as $fname) {
      $values[] = isset($data[$fname]) ? $fname : '';
    }
    
    return $values;
  }
}

