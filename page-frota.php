<?php
$client = new SoapClient('http://www.henjiweb.com.br/Webservice/wsReserva.asmx?WSDL');
$params = array(
  "pUsuario" => "reserva",
  "pSenha" => "r3s3rva&2019"
);
  
$response = $client->__soapCall("GetGrupoVeiculo", array($params));

$xml = simplexml_load_string($response->GetGrupoVeiculoResult->any);

$grupos = $xml->NewDataSet->Table1;

get_header(); 
?>
<div id="page">
  <div id="titulo-topo" class="titulo-topo p-md-4 p-1">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1>Nossa Frota</h1>
          <h5>Alugue um carro com na Henji e aproveite ao máximo seu passeio!</h5>
        </div>
      </div>
    </div>
  </div>

  <?php get_template_part( 'content', 'breadcrumb' ); ?>

  <div>
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
<?php get_footer(); ?>
