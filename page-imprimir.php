<?php

$client = new SoapClient('http://www.henjiweb.com.br/Webservice/wsReserva.asmx?WSDL');
$params = array(
  "pUsuario" => "reserva",
  "pSenha" => "r3s3rva&2019",
  "pFiltro" => "I",
  "pIdReserva" => $_GET['id']
);
  
$response = $client->__soapCall("GetHtml", array($params));

$xml = simplexml_load_string($response->GetHtmlResult->any);
$html = $xml->NewDataSet->HTML;

echo $html->texto;
echo "<script>window.print()</script>";