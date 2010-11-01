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
   
   $file = fopen($this->param('file'), 'r');
   $i = 0;
   while (($line = fgets($file)) && $i++ < 100) {
     //print $i++ . ' ' . $line;
     $data = $this->parseLine($line);
     
     // Add this to make it easier to identify the lines later.
     $data['filename'] = $this->param('file');
     
     // XXX: Should we buffer a few hundred items and then save at once?
     $this->store($data);
   }
   fclose($file);
 }
 
 /**
  * Parse a line and return an associative array of data.
  */
 abstract function parseLine($line);
 
 public function store($data) {
   //$this->context->datasource('db')->get()->logs->save($data);
   print_r($data);
 }
}