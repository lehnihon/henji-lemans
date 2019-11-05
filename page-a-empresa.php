<?php

$client = new SoapClient('http://www.henjiweb.com.br/Webservice/wsReserva.asmx?WSDL');
$params = array(
  "pUsuario" => "reserva",
  "pSenha" => "r3s3rva&2019",
  "pFiltro" => "S",
  "pIdReserva" => "0"
);
  
$response = $client->__soapCall("GetHtml", array($params));

$xml = simplexml_load_string($response->GetHtmlResult->any);
$html = $xml->NewDataSet->HTML;

$client = new SoapClient('http://www.henjiweb.com.br/Webservice/wsReserva.asmx?WSDL');
$params = array(
  "pUsuario" => "reserva",
  "pSenha" => "r3s3rva&2019"
);
  
$response = $client->__soapCall("GetImagensPromocoes", array($params));
$xml = simplexml_load_string($response->GetImagensPromocoesResult->any);
$imagens = $xml->NewDataSet->ImagensPromo;

get_header(); 
?>
<div id="page">
  <div id="titulo-topo" class="titulo-topo p-md-4 p-1">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1>Sobre a Empresa</h1>
          <h5>Alugue um carro com na Henji e aproveite ao máximo seu passeio!</h5>
        </div>
      </div>
    </div>
  </div>

  <?php get_template_part( 'content', 'breadcrumb' ); ?>

  <div class="page-sobre">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h3>A Henji</h3>

            <?php 
            echo $html->texto;
            ?> 
        </div>
        <div class="col-md-12 text-center">
        <a href="<?php echo home_url( '/fale-conosco' ); ?>" class="btn-selecionar">Fale Conosco</a>
        </div>
      </div>
      <div class="row mt-5 pt-4 ">
        <div class="col-12 text-center">
          <h3>Conheça nossa Frota</h3>
          <p>
            As melhores opçoes pra você reservar e aproveitar
          </p>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-8 owl-carousel owl-theme">
          <?php foreach($imagens as $imagem){?>
            <div>
              <img class="img-fluid" src="<?php echo $imagem-> urlImagem; ?>" />
            </div>
          <?php } ?>

        </div>
      </div>
      <?php get_template_part( 'content', 'redes' ); ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>
