<?php
class csv{
  private $data = '';
  private $fileName = 'CSVReport.csv';
  public function __construct($fileName='CSVReport.csv'){
    if(trim($fileName)!=''){
      $this->fileName = $fileName;      
    }
  }
  public function getReport($reportName,$reportData){
    $data = '';
    foreach($reportData as $z=>$v){
      $data .= '"Zone_'.$z.'","'.$v.'"'."\n";
    }
    $fname = 'app/'./*$reportName.'_'.*/$this->fileName;
    $f = fopen($fname , 'wb');
    fwrite($f , $data );
    fclose($f);
    return $fname;
  }
}