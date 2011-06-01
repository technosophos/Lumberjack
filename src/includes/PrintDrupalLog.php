<?php

class PrintDrupalLog extends BaseFortissimoCommand {
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
    
    // date, server, message, url
    $format = '[%s] [%s] %s %s' . PHP_EOL;
    
    foreach ($cursor as $item) {
      
      if(isset($item['message'])) {
        $date = date('r', $item['date']);
        printf($format, $date, $item['server'], $item['message'], $item['url']);
      }
      else {
        print $item['raw'];
      }
    }
  }
}