<?php

$client = new SoapClient('http://www.henjiweb.com.br/Webservice/wsReserva.asmx?WSDL');
$params = array(
  "pUsuario" => "reserva",
  "pSenha" => "r3s3rva&2019",
  "pFiltro" => "P",
  "pIdReserva" => "0"
);
  
$response = $client->__soapCall("GetHtml", array($params));

$xml = simplexml_load_string($response->GetHtmlResult->any);
$html = $xml->NewDataSet->HTML;
get_header(); 
?>
<div id="page">
  <div id="titulo-topo" class="titulo-topo p-md-4 p-1">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1>Política de Privacidade</h1>
        </div>
      </div>
    </div>
  </div>

  <?php get_template_part( 'content', 'breadcrumb' ); ?>

  <div class="page-sobre">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h3>Políticas</h3>

            <?php 
            echo $html->texto;
            ?> 
        </div>
        <div class="col-md-12 text-center">
        <a href="<?php echo home_url( '/fale-conosco' ); ?>" class="btn-selecionar">Fale Conosco</a>
        </div>
      </div>

      <?php get_template_part( 'content', 'redes' ); ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>
