<?php

class PrintRawLog extends BaseFortissimoCommand {
  public function expects() {
    return $this
      ->description('Prints the raw log entry for any items in the result set.')
      ->usesParam('cursor', 'A MongoCursor object')
      ->whichIsRequired()
      ->andReturns('Nothing. Data is printed to std out.')
    ;
  }
  
  public function doCommand() {
    $cursor = $this->param('cursor');
    foreach ($cursor as $item) {
      print $item['raw'] . PHP_EOL;
    }
  }
}