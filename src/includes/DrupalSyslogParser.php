<?php
class DrupalSyslogParser extends BaseLogParser {
  
  // Oct 26 00:00:13 nycsweb1 drupal: http://www.consumersearch.com|1288051213|cs_fullreport|10.12.21.68|http://www.consumersearch.com/auto-gps/garmin-nuvi-205w|http://www.consumersearch.com/auto-gps|0||no ASIN B001ELJERE in function theme_product_info_bubble_aflyout()
  const PARSER_REGEX = '/^([a-zA-Z]{3} [0-9]{2} [0-9:]*) ([a-zA-Z0-9]*) drupal:(.*)$/';
  
  public function expects() {
    return $this
      ->description('Parses a Drupal sylog log file')
      ->usesParam('file', 'The file name to parse')
      ->usesParam('datasource', 'The name of the datasource to store data.')
      ->whichHasDefault(NULL)
      ->andReturns('Nothing.')
    ;
  }
  
  public function parseLine($line) {
    $buffer = array();
    $res = preg_match(self::PARSER_REGEX, $line, $buffer);
    
    if (empty($buffer[0])) {
      return array(
        'type' => 'drupal',
        'raw' => $line,
      );
    }
    
    /*
     * Drupal log has a pipe-delimited list with these eight fields:
     * 0: site
     * 1: date
     * 2: facility
     * 3: Origin (client IP)
     * 4: URL
     * 5: Referer URL
     * 6: Log level, I think (0 = error, 1 = warning, 2 = debug...)
     * 7: Link
     * 8: Error message
     */    
    $drupal = explode('|', $buffer[3], 9);
    $foo = count($drupal);
    if ($foo < 9) {
      return array(
        'type' => 'drupal',
        'raw' => $buffer[0],
        'date' => strtotime($buffer[1]),
        'server' => $buffer[2],
        'message' => $buffer[3],
        'level' => BaseLogParser::LEVEL_ERROR,
      );
    }
    //print_r($drupal);
    
    $data = array(
      'type' => 'drupal',
      'raw' => $buffer[0],
      //'date' => strtotime($buffer[1]),
      'date' => $drupal[1],
      'server' => $buffer[2],
      'client' => $drupal[3],
      //'message' => $buffer[3],
      'message' => $drupal[8],
      'referer' => $drupal[5],
      'url' => $drupal[4],
      'facility' => $drupal[2],
      'site' => $drupal[0],
      'link' => $drupal[7],
      'level' => empty($drupal[6]) ? BaseLogParser::LEVEL_ERROR : BaseLogParser::LEVEL_WARNING,
    );
    return $data;
  }
}