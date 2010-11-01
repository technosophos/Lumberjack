<?php

class ApacheErrorLogParser extends BaseLogParser {
  
  // [Tue Oct 26 23:59:59 2010] [error] [client 10.12.21.30] ALERT - script tried to increase memory_limit to 205520896 bytes which is above the allowed value (attacker '10.12.21.30', file '/mnt/cs/www/www.consumersearch.com/sites/www.consumersearch.com/settings.php', line 319)
  const APACHE_REGEX = '/^\[(.*)\] \[[a-z]+\] (?(?=\[client)\[client ([0-9\.]*)\] )(?(?=.*, referer: )(.*), referer: (.*)|(.*))$/';
  
  public function expects() {
    return $this
      ->description('Parses an Apache log file')
      ->usesParam('file', 'The file name to parse')
      //->withFilter('callback', array($this, 'fileExists'))
      ->usesParam('datasource', 'The name of the datasource to store data.')
      ->whichHasDefault(NULL)
      ->andReturns('Nothing.')
    ;
  }
  
  public function fileExists($filename) {
    return is_file($filename) && is_readable($filename);
  }
  
  public function parseLine($line) {
    $buffer = array();
    $res = preg_match(self::APACHE_REGEX, $line, $buffer);
    
    if (empty($buffer[0])) {
      return array(
        'type' => 'apache error',
        'raw' => $line,
      );
    }
    
    //return $buffer;
    $data = array(
      'type' => 'apache error',
      'raw' => $buffer[0],
      'date' => strtotime($buffer[1]),
      'client' => $buffer[2],
      'message' => empty($buffer[3]) ? $buffer[5]: $buffer[3],
      'referer' => isset($buffer[4]) ? $buffer[4] : '',
      'level' => BaseLogParser::LEVEL_ERROR,
    );
    return $data;
  }
}
