<?php

abstract class BaseLogParser extends BaseFortissimoCommand {
 
 protected $ds = NULL;
 
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
   //$this->ds->logs->save($data);
   var_dump($this->context->datasource('db'));//->get()->logs->save();
   //print_r($data);
   // /var_dump($this->context);
 }
}