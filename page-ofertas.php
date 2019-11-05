<?php
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

  <?php get_template_part( 'content', 'breadcrumb' ); ?>

  <div class="banner mt-1">
    <div class="container">
      <div class="col-12">
        <div class="owl-carousel owl-theme">
          <?php foreach($imagens as $imagem){?>
            <div>
              <img class="img-fluid" src="<?php echo $imagem-> urlImagem; ?>" />
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <?php get_template_part( 'content', 'redes' ); ?>
</div>
<?php get_footer(); ?>
