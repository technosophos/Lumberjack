<?php

class CustomApacheAccessLogParser extends BaseLogParser {
  public function expects() {
    return $this
      ->description('Parses a custom Apache log file')
      ->usesParam('file', 'The file name to parse')
      ->usesParam('datasource', 'The name of the datasource to store data.')
      ->whichHasDefault(NULL)
      ->andReturns('Nothing.')
    ;
  }
  
  
}