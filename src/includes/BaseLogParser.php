<?php
abstract class BaseLogParser extends BaseFortissimoCommand {
 
 const LEVEL_ERROR = 0;
 const LEVEL_WARNING = 1;
 const LEVEL_INFO = 2;
 const LEVEL_DEBUG = 3;
 
 protected $ds = NULL;
 
 public function expects() {
   return $this
     ->description('Parses a log file and imports it into a MongDB')
     ->usesParam('file', 'The file name to parse')
     ->usesParam('datasource', 'The name of the datasource to store data.')
     ->whichHasDefault(NULL)
     ->andReturns('Nothing.')
   ;
 }
 
 public function setDatasourceName($name = NULL) {
   $this->ds = $this->context->ds($name);
 } 
 
 public function doCommand() {
   $this->ds = $this->context->ds($this->param('datasource'));
   //$this->collection = 'logs'
   $logfile = $this->param('file');
   
   printf('Reading logfile %s.' . PHP_EOL, $logfile);
      
   $file = fopen($logfile, 'r');
   $i = 0;
   while ($line = fgets($file)) {
     ++$i;
     //print $i++ . ' ' . $line;
     $data = $this->parseLine($line);
     
     // Add this to make it easier to identify the lines later.
     $data['filename'] = $logfile;
     
     // XXX: Should we buffer a few hundred items and then save at once?
     $this->store($data);
   }
   fclose($file);
   
   printf('Loaded %d lines.' . PHP_EOL, $i);
 }
 
 /**
  * Parse a line and return an associative array of data.
  *
  * This function should parse a line from the desired logfile and return an associative array
  * of data. The data will then be stored in the database in the corresponding format.
  *
  * It is a good idea to try to repeat the naming conventions of other loggers so that simple
  * queries can be constructed.
  *
  * Here's an example return value from Apache error logs:
  * @code
  * $data = array(
  *   'raw' => $buffer[0],
  *   'date' => strtotime($buffer[1]),
  *   'client' => $buffer[2],
  *   'message' => empty($buffer[3]) ? $buffer[5]: $buffer[3],
  *   'referer' => isset($buffer[4]) ? $buffer[4] : '',
  *   'level' => BaseLogParser::LEVEL_ERROR,
  * );
  * @endcode
  *
  * @param string $line
  *  A log line to parse
  * @return array
  *  An associative array of field names and values.
  */
 abstract function parseLine($line);
 
 public function store($data) {
   $this->context->datasource('db')->get()->logs->save($data);
   //print_r($data);
 }
}