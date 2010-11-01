<?php

class VarnishNCSALogParser extends BaseLogParser {
  
  //203.115.201.25 - - [26/Oct/2010:22:00:00 +0000] "GET http://www.consumersearch.com/sites/all/themes/cs6/public/images/buttons/button-more-sources.gif HTTP/1.1" 200 1414 "-" "Mozilla/4.0 (compatible;)"
  const VARNISH_REGEX = '/^([0-9\.]+) ([a-zA-Z0-9\-\_])+ ([a-zA-Z0-9\-\_])+ \[([a-zA-Z0-9:\/\+ ]*)\] "([A-Z]+) ([^"]*) (HTTP\/[0-9\.]+)" ([0-9]+) ([0-9]+) "([^"]+)" (.*)$/';
  
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
    /**
     * Fields:
     * - 0: Full line
     * - 1: Origin (Client IP)
     * - 2: ???
     * - 3: ???
     * - 4: timestamp
     *  -5: Method
     * - 6: URL
     * - 7: HTTP version
     * - 8: HTTP code
     * - 9: Content-length
     * - 10: referer
     * - 11: User-agent
     */
    $data = array(
      'raw' => $buffer[0],
      'date' => strtotime($buffer[4]),
      'client' => $buffer[1],
      'message' => $buffer[7] . ' ' . $buffer[5] . ': ' . $buffer[8] . ', ' . $buffer[9] . ' bytes',
      'referer' => $buffer[10] == '-' ? '' : $buffer[10],
      'url' => $buffer[6],
      'level' => (int)$buffer[8] < 400 ? BaseLogParser::LEVEL_INFO : BaseLogParser::LEVEL_ERROR,
      'agent' => $buffer[11],
    );
    return $data;
  }
}