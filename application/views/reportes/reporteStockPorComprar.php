<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



include_once(APPPATH.'libraries/PHPJasperXML/class/tcpdf/tcpdf.php');
include_once(APPPATH.'libraries/PHPJasperXML/class/PHPJasperXML-0.9d.inc.php');

$server="localhost";
$db="totorodb";
$user="root";
$pass="";



$PHPJasperXML = new PHPJasperXML();
$fechaEmision=$_GET["fechaEmision"];
$PHPJasperXML->arrayParameter=array("fechaEmision"=>$fechaEmision);

//$PHPJasperXML-> debugsql = true; // SIRVE PA VER LA CONSULTA 


$PHPJasperXML->load_xml_file("assets/reportes/reporteStockPorTerminarse.jrxml");

$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file


?>
