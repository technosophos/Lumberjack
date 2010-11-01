<?php

class VarnishNCSALogParser extends BaseLogParser {
  
  //203.115.201.25 - - [26/Oct/2010:22:00:00 +0000] "GET http://www.consumersearch.com/sites/all/themes/cs6/public/images/buttons/button-more-sources.gif HTTP/1.1" 200 1414 "-" "Mozilla/4.0 (compatible;)"
  //const VARNISH_REGEX = '/^(((unknown, )*|([0-9\.]+, )*)*[0-9\.]+) ([a-zA-Z0-9\-\_])+ ([a-zA-Z0-9\-\_])* \[([a-zA-Z0-9:\/\+ ]*)\] "([A-Z]+) ([^"]*) (HTTP\/[0-9\.]+)" ([0-9]+) ([0-9]+) "([^"]*)" (.*)$/';
  const VARNISH_REGEX = '/^((([0-9\.]+|unknown+)[ ]*,[ ]+)*[0-9\.]+) ([a-zA-Z0-9\-\_])+ ([a-zA-Z0-9\-\_])* \[([a-zA-Z0-9:\/\+ ]*)\] "([A-Z]+) ([^"]*) (HTTP\/[0-9\.]+)" ([0-9]+) ([0-9]+) "([^"]*)" (.*)$/';
  
  public function expects() {
    return $this
      ->description('Parses a Varnish NCSA log file')
      ->usesParam('file', 'The file name to parse')
      ->usesParam('datasource', 'The name of the datasource to store data.')
      ->whichHasDefault(NULL)
      ->andReturns('Nothing.')
    ;
  }
  
  public function parseLine($line) {
    $buffer = array();
    $res = preg_match(self::VARNISH_REGEX, $line, $buffer);
    
    if (empty($buffer[0])) {
      //print 'Failed to parse:' . PHP_EOL . $line;
      return array(
        'type' => 'varnish',
        'raw' => $line,
      );
    }
        
    /**
     * Fields:
     * - 0: Full line
     * - 1: Origin (Client IP)
     * - 2: Partial IPs (used for backref)
     * - 3: Partial IPs (used for regex)
     * - 4: Unknown
     * - 5: Unknown
     * - 6: timestamp
     *  -7: Method
     * - 8: URL
     * - 9: HTTP version
     * - 10: HTTP code
     * - 11: Content-length
     * - 12: referer
     * - 13: User-agent
     */
    $data = array(
      'type' => 'varnish',
      'raw' => $buffer[0],
      'date' => strtotime($buffer[6]),
      'client' => $buffer[1],
      'message' => $buffer[9] . ' ' . $buffer[7] . ': ' . $buffer[10] . ', ' . $buffer[11] . ' bytes',
      'referer' => $buffer[12] == '-' ? '' : $buffer[12],
      'url' => $buffer[8],
      'level' => (int)$buffer[10] < 400 ? BaseLogParser::LEVEL_INFO : BaseLogParser::LEVEL_ERROR,
      'agent' => $buffer[13],
    );
    return $data;
  }
}