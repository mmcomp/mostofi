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
    $data = "Zone Name\tValue\n";
    foreach($reportData as $z=>$v){
      $data .= "Zone_$z\t$v\n";//'"Zone_'.$z.'","'.$v.'"'."\n";
    }
    return $data;
  }
}