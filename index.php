<?php
$client = new SoapClient('http://www.henjiweb.com.br/Webservice/wsReserva.asmx?WSDL');
$params = array(
  "pUsuario" => "reserva",
  "pSenha" => "r3s3rva&2019"
);
  
$response = $client->__soapCall("GetImagensPromocoes", array($params));
$xml = simplexml_load_string($response->GetImagensPromocoesResult->any);
$imagens = $xml->NewDataSet->ImagensPromo;

$response = $client->__soapCall("GetGrupoVeiculo", array($params));

$xml = simplexml_load_string($response->GetGrupoVeiculoResult->any);

$grupos = $xml->NewDataSet->Table1;

if(isset($_GET['news'])){
  $email = $_GET['email'];
  $client = new SoapClient('http://www.henjiweb.com.br/Webservice/wsReserva.asmx?WSDL');
  $params = array(
    "pUsuario" => "reserva",
    "pSenha" => "r3s3rva&2019",
    "pEmail" => $email
  );
  
  $response = $client->__soapCall("GravaEmailPromocoes", array($params));
}

get_header(); ?>
<div id="page">
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
  <div class="page-espaco">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center mb-3">
          <h3>Conheça nossa Frota</h3>
          <p>
            As melhores opçoes pra você reservar e aproveitar
          </p>
        </div>
      </div>
      <div class="row pb-3 justify-content-center">
        <?php foreach($grupos as $grupo){ ?>
          <div class="col-md-3 text-center mb-3">
            <img src="<?php echo $grupo->UrlImagem ?>" />
            <h3>Grupo <?php echo $grupo->Descricao ?></h3>
            <p class="bigger"><?php echo $grupo->Complemento ?></p>
          </div>
        <?php }  ?>
      </div>
      <?php get_template_part( 'content', 'redes' ); ?>
    </div>
    
  </div>
</div>
<?php
if(isset($_GET['news'])){
?>
<!-- Modal -->
<div class="modal fade" id="exampleModalb" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Aviso do sistema!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body py-3">
        E-mail cadastrado com sucesso!
      </div>
    </div>
  </div>
</div>
<script>
  $('#exampleModalb').modal('show');
</script>
<?php
}
?>
<?php get_footer(); ?>
