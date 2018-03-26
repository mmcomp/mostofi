<?php
class kml{
  private $data = '';
  public function __construct($template_file='app/kml_tmp.kml'){
    $this->data = file_get_contents($template_file);
  }
  public function getReport($reportName,$reportData){
    $tmp = $this->data;
    $tmp = str_replace('#BACHI_REPORT#',$reportName,$tmp);
    for($i = 1;$i <= 300;$i++){
      if(isset($reportData[$i])){
        $tmp = str_replace('#BACHI_AMOUNT_'.$i.'#',$reportData[$i],$tmp);
      }else{
        $tmp = str_replace('#BACHI_AMOUNT_'.$i.'#','0',$tmp);
      }
    }
    $fname = $reportName.'_'.date('Y-m-d_H-i-s').'.kml';
    file_put_contents('kml/'.$fname,$tmp);
    return $fname;
  }
}