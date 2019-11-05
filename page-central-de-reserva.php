<?php
$client = new SoapClient('http://www.henjiweb.com.br/Webservice/wsReserva.asmx?WSDL');
$dtretirada = $_POST['dtretirada'];
$hrretirada = $_POST['hrretirada'];
$dtretorno = $_POST['dtretorno'];
$hrretorno = $_POST['hrretorno'];
$lcretirada = $_POST['lcretirada'];
$lcretorno = $_POST['lcretorno'];
$idtarifa = $_POST['idtarifa'];
$idgrupo = $_POST['idgrupo'];
if(empty($lcretorno)){
  $lcretorno = $lcretirada;
}

$dtretirada_completa = DateTime::createFromFormat('d/m/Y', $dtretirada)->format('Y-m-d')."T$hrretirada:00";
$dtretorno_completa = DateTime::createFromFormat('d/m/Y', $dtretorno)->format('Y-m-d')."T$hrretorno:00";

// CONSULTA DADOS DE RESERVA

if(!empty($_POST['reservar'])){
  //GRAVA DADOS DE RESERVA
  $nacionalidade = $_POST['nacionalidade'];
  $form1 = $_POST['form1'];
  $form2 = $_POST['form2'];
  $form3 = $_POST['form3'];
  $form4 = $_POST['form4'];
  $form5 = $_POST['form5'];
  $form8 = $_POST['form8'];
  $form17 = $_POST['form17'];
  $form10 = $_POST['form10'];
  $form11 = $_POST['form11'];
  $form12 = $_POST['form12'];
  $form13 = $_POST['form13'];
  $form15 = $_POST['form15'];
  $form16 = $_POST['form16'];
  $form14 = $_POST['form14'];
  $form9 = $_POST['form9'];

  if($nacionalidade == 'S'){
    $form2 = $_POST['form20'];
    $form21 = $_POST['form21'];
    $form4 = $_POST['form22'];
    $form5 = $_POST['form23'];
    $form24 = $_POST['form24'];
    $form9 = $_POST['form25'];
  }
  $adicionais = $_POST['adicionais'];
  $protecoes = $_POST['protecoes'];

  $params = array(
    "pUsuario" => "reserva",
    "pSenha" => "r3s3rva&2019",
    "pDataRetirada" => $dtretirada_completa,
    "pDataRetorno" => $dtretorno_completa,
    "pIdLocalRetirada" => $lcretirada,
    "pIdLocalRetorno" => $lcretorno, 
    "pIdTarifa" => $idtarifa,
    "pTipoPessoaLocatario" => $form1,
    "pNomeLocatario" => $form2,
    "pDocumentoLocatario" => $form3,
    "pEmailLocatario" => $form4,
    "pTelefoneLocatario" => $form5,
    "pNumeroCNHLocatario" => $form8,
    "pEstrangeiroLocatario" => $nacionalidade,
    "pPaisOrigemLocatario" => $form24,
    "pNumeroPassaporteLocatario" => $form21,
    "pNomeCondutor" => '',
    "pDocumentoCondutor" => '',
    "pEmailCondutor" => '',
    "pTelefoneCondutor" => '',
    "pNumeroCNHCondutor" => '',
    "pEstrangeiroCondutor" => '',
    "pPaisOrigemCondutor" => '',
    "pNumeroPassaporteCondutor" => '',
    "pObservacao" => $form9,
    "pIdsProtecao" => $protecoes,
    "pIdsOpcionais" => $adicionais
  );
  $response = $client->__soapCall("GravaReserva", array($params));
  $xml = simplexml_load_string($response->GravaReservaResult->any);
  $reserva = $xml->NewDataSet->Reserva;
  $id = $reserva->NumeroReserva;
  $status = $reserva->StatusReserva;
  $veiculo = $reserva->Complemento." - ".$reserva->Descricao;
  wp_redirect(home_url("/central-de-reserva-resposta?id=$id&status=$status&veiculo=$veiculo"));
}else{
  $params = array(
    "pUsuario" => "reserva",
    "pSenha" => "r3s3rva&2019",
    "pDataRetirada" => $dtretirada_completa,
    "pDataRetorno" => $dtretorno_completa,
    "pIdLocalRetirada" => $lcretirada,
    "pIdLocalRetorno" => $lcretorno, 
    "pIdGrupoVeiculo" => 0
  );

  if(isset($_POST['idgrupo'])){
    $params["pIdGrupoVeiculo"] = $_POST['idgrupo'];
  }
  try{
  $response = $client->__soapCall("GetInformacoesTarifa", array($params));
  }catch(Throwable $e){
    $msgerror = "Erro ao consultar o servidor - Valores inválidos";
  }
  $xml = simplexml_load_string($response->GetInformacoesTarifaResult->any);

  $tarifasf = $xml->NewDataSet->Tarifas;

  $tarifas = null;

  if(empty($idtarifa)){
    $tarifas = $tarifasf;
  }else{
    foreach($tarifasf as $i => $t){
      if($idtarifa == $t->Id_Tarifa){
        $tarifas = $t;
      }
    }
  }

  $lojas = $xml->NewDataSet->Lojas;
  $opcionais = $xml->NewDataSet->Opcionais;
  $protecoes = $xml->NewDataSet->Protecao;
  $taxas = $xml->NewDataSet->Taxas;
}
function geraHtmlGrupo($tarifas){
  $grupo_veiculos = array();
  foreach($tarifas as $val){
    $index = $val->Id_GrupoVeiculo;
    $grupo_veiculos["$index"][] = $val;
  }
  $topodias = true;
  ksort($grupo_veiculos);
  foreach($grupo_veiculos as $grupo){
    $topogrupo = true;
    foreach($grupo as $val){
      if($topodias){
        $html_grupos .= "
          <div class='veiculos-top'>
            Tarifa referente a locação de {$val->QtdeDias}  dia(s)
          </div>
          ";
        $topodias = false;
      }
      if($topogrupo){
        $html_grupos .= "
          <div class='veiculos-box'>
            <div class='veiculos-grupo' data-id='{$val->Descricao}'>
              <span class='triangulo-baixo show-{$val->Descricao}'>&#9207;</span> <span class='hide-{$val->Descricao} veiculos-hide triangulo-direita'>&#9205;</span> {$val->Descricao} - {$val->Complemento}
            </div>
            <div class='veiculos-detalhes show-{$val->Descricao}'>
              <div class='row'>
                <div class='col-12 text-center'>
                  <img class='ml-auto mr-auto mt-2 mb-3 img-fluid' src='{$val->UrlImagem}' />
                </div>
                <div class='col-12'>
                  <table class='table'>
                    <thead>
                      <tr>
                        <th>Diária</th>
                        <th>Franquia</th>
                        <th>KM Adc.</th>
                        <th class='presp'>Proteção</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
          ";
        $topogrupo = false;
      }
      $html_grupos .= "
        <tr>
          <td>R$".str_replace('.',',',$val->VlrDiaria)."</td>
          <td>".$val->LimiteKm."</td>
          <td>R$".str_replace('.',',',$val->VlrKmExcedente)."</td>
          <td class='presp'>R$".str_replace('.',',',$val->VlrProtecaoDiaria)."</td>
          <td><a class='btn-veiculo' data-grupo='{$val->Id_GrupoVeiculo}' data-tarifa='{$val->Id_Tarifa}' href='#'>Selecionar</a></td>
        </tr>
      ";
    }
    $html_grupos .= "
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    ";  
  }
  return $html_grupos;
}

function geraHtmlOpcionais($opcionais){
  $html_extras = "
    <div class='extras-grupo'>
      ADICIONAIS
    </div>
    <div class='extras-detalhes px-2 pt-2'>";
      foreach($opcionais as $val){
        $html_extras .= "
        <div class='row mb-2'>
          <div class='col-8'>
            <div class='form-check'>
            <input class='form-check-input opcheck' data-descricao='{$val->Descricao}' data-valor='{$val->VlrAcessorioDiaria}' value='{$val->Id_Opcionais}' type='checkbox' id='op{$val->Id_Opcionais}'>
            <label class='form-check-label' for='op{$val->Id_Opcionais}'>
              {$val->Descricao}
            </label>
            </div>
          </div>
          <div class='col-4'>R$".str_replace(".",",",$val->VlrAcessorioDiaria)."/dia</div>
        </div>
        ";
      }
  $html_extras .= "
    </div>";
  return $html_extras;
}

function geraHtmlProtecoes($protecoes){
  $html_protecao = "
    <div class='extras-grupo'>
      PROTEÇÃO
    </div>
    <div class='extras-detalhes px-2 pt-2'>";
      foreach($protecoes as $val){
        $popconteudo = "<strong>PARTICIPAÇÃO</strong><br>";
        if($val->IncluidaDanosMateriais == 'S'){
          if($val->TipoDanosMateriais == 'V'){
            $popconteudo .= "Danos Materiais: R$".str_replace(".",",",$val->VlrPercDanosMateriais)."<br>";
          }else{
            $popconteudo .= "Danos Materiais: ".str_replace(".",",",$val->VlrPercDanosMateriais)."%<br>";
          }
        }

        if($val->IncluidaFurtoRoubo == 'S'){
          if($val->TipoFurtoRoubo == 'V'){
            $popconteudo .= "Furto e Roubo: R$".str_replace(".",",",$val->VlrPercFurtoRoubo)."<br>";
          }else{
            $popconteudo .= "Furto e Roubo: ".str_replace(".",",",$val->VlrPercFurtoRoubo)."%<br>";
          }
        }

        if($val->IncluidaPerdaTotal == 'S'){
          if($val->TipoPerdaTotal == 'V'){
            $popconteudo .= "Perda Total: R$".str_replace(".",",",$val->VlrPercPerdaTotal)."<br>";
          }else{
            $popconteudo .= "Perda Total: ".str_replace(".",",",$val->VlrPercPerdaTotal)."%<br>";
          }
        }

        if($val->IncluidaTerceiros == 'S'){
          if($val->TipoTerceiros == 'V'){
            $popconteudo .= "Terceiros: R$".str_replace(".",",",$val->VlrPercTerceiros)."<br>";
          }else{
            $popconteudo .= "Terceiros: ".str_replace(".",",",$val->VlrPercTerceiros)."%<br>";
          }
        }
        
        $html_protecao .= "
        <div class='row mb-2'>
          <div class='col-8'>
            <div class='form-check'>
            <input class='form-check-input opcheckb' type='checkbox' data-descricao='{$val->Descricao}' data-valor='{$val->VlrProtecaoDiaria}' name='protecao' id='op{$val->Id_Protecao}' value='{$val->Id_Protecao}'>
            <label data-container='body' data-html='true' data-toggle='popover' data-trigger='hover' data-placement='top' data-content='$popconteudo' class='form-check-label' for='op{$val->Id_Protecao}'>
              {$val->Descricao}
            </label>
            </div>
          </div>
          <div class='col-4'>R$".str_replace(".",",",$val->VlrProtecaoDiaria)."/dia</div>
        </div>
        ";
      }
  $html_protecao .= "
    </div>";
  return $html_protecao;
}

function geraHtmlTaxas($taxas){
  $html_taxas = "
    <div class='extras-grupo'>
     TAXAS
    </div>
    <div class='extras-detalhes px-2 pt-2'>";
  foreach($taxas as $val){
  }
  $html_taxas .="
    </div>
  ";
  return $html_taxas;
}

function geraHtmlTermosCond($tarifa){
  $html_protecao = "
    <div class='extras-grupo'>
      TERMOS E CONDIÇÕES
    </div>
    <div class='extra-reserva extras-detalhes px-2 pt-2'>
      {$tarifa->Mensagem_Locadora}
    </div>";
  return $html_protecao;
}

function geraHtmlRetirada($lojas,$data,$hora){
  $html_retirada = '';
  foreach($lojas as $val){
    if($val->RetiradaDevolucao == 'R'){
    $html_retirada.="
      <p>
        {$val->Nome}<br>
        ENDEREÇO: <span>{$val->Endereco} {$val->Cidade} / {$val->Estado}</span><br>
        DATA: $data $hora
      </p>";
    }
  }
  return $html_retirada;
}
 
function geraHtmlRetorno($lojas,$data,$hora){
  $html_devolucao = '';
  foreach($lojas as $val){
    if($val->RetiradaDevolucao == 'D'){
    $html_devolucao.="
      <p>
        {$val->Nome}<br>
        ENDEREÇO: <span>{$val->Endereco} {$val->Cidade} / {$val->Estado} </span><br>
        DATA: $data $hora
      </p>";
    }
  }
  return $html_devolucao;
}

function geraHtmlVeiculo($tarifa){
  echo "<p>
    <strong>{$tarifa->Descricao} - {$tarifa->Complemento}</strong><br>
    <img class='img-fluid' src='{$tarifa->UrlImagem}' />
    </p>
  ";
}

function geraHtmlTotal($tarifa){
  $html_total = "
    <div class='row mb-1'>
      <div class='col-7'>
        Tarifa Base:
      </div>
      <div class='col-5'>
        R$".str_replace(".",",",$tarifa->VlrDiaria)."/dia
      </div>
    </div>
    <div class='row mb-1'>
      <div class='col-7'>
        Tarifa Total:
      </div>
      <div class='col-5'>
        R$".number_format($tarifa->VlrDiaria*$tarifa->QtdeDias,2,',','')."
      </div>
    </div>
    <hr>
    <div class='row mb-1'>
      <div class='col-12'>
        <strong>ADICIONAIS</strong>
      </div>
    </div>
    <div class='row mb-1'>
      <div class='col-12 adicionais-lista'>
      </div>
      <div class='col-12'>
        <div class='row'><div class='col-7'><strong>Total:</strong></div><div class='col-5'>R$<span class='adicionais-total'>0</span></div></div>
      </div>
    </div>
    <hr>
    <div class='row mb-1'>
      <div class='col-12'>
        <strong>PROTEÇÕES</strong>
      </div>
    </div>
    <div class='row'>
        <div class='col-7'>
          PROTEÇÃO
        </div>
        <div class='col-5'>
          R$".str_replace(".",",",$tarifa->VlrProtecaoDiaria)."/dia
        </div>
    </div>
    <div class='row mb-1'>
      <div class='col-12 protecoes-lista'>
      </div>
      <div class='col-12'>
        <div class='row'><div class='col-7'><strong>Total:</strong></div><div class='col-5'>R$<span data-valor='".$tarifa->VlrProtecaoDiaria*$tarifa->QtdeDias."' class='protecao-total'>".number_format($tarifa->VlrProtecaoDiaria*$tarifa->QtdeDias,2,",","")."</span></div></div>
      </div>
    </div>
    <hr>
    <div class='row mb-1'>
      <div class='col-7'>
        Franquia de Km
      </div>
      <div class='col-5'>
        ".$tarifa->QtdeKm."KM
      </div>
    </div>
    <div class='row mb-1'>
      <div class='col-7'>
        Franquia Tipo
      </div>
      <div class='col-5'>
        ".$tarifa->LimiteKm."
      </div>
    </div>
    <div class='row mb-1'>
      <div class='col-7'>
        KM Adicional
      </div>
      <div class='col-5'>
        R$".str_replace(".",",",$tarifa->VlrKmExcedente)."
      </div>
    </div>
    <hr>
    <div class='total-estimado py-2 px-2'>
      <div class='row'>
        <div class='col-7'>
          DIAS
        </div>
        <div class='col-5'>
          ".$tarifa->QtdeDias." dias
        </div>
      </div>
      <div class='row'>
        <div class='col-7'>
          TOTAL DIÁRIA
        </div>
        <div class='col-5 totaldiaria' data-dias='{$tarifa->QtdeDias}' data-valor='".($tarifa->VlrDiaria+$tarifa->VlrProtecaoDiaria)."'>
          R$".number_format($tarifa->VlrDiaria+$tarifa->VlrProtecaoDiaria,2,',','')."
        </div>
      </div>
      <div class='row'>
        <div class='col-7'>
          <strong>TOTAL GERAL</strong>
        </div>
        <div class='col-5 total'>
          R$".number_format((($tarifa->VlrDiaria+$tarifa->VlrProtecaoDiaria)*$tarifa->QtdeDias),2,',','')."
        </div>
      </div>
    </div>
    <div class='row'>
      <div class='col-12 total-obs mt-3'>
        <strong>Observação:</strong> Sua tarifa foi calculada com base nas informações fornecidas. Algumas modificações podem alterar essa tarifa.
      </div>
    </div>
  ";
  return $html_total;
}

function formFinal(){
?>
  <input type="hidden" name="reservar" class="reserv">
  <div class="row mb-4 alerta-erro" style="display:none">
    <div class="col-12">
      <div class="alert alert-warning alerta-msg" role="alert">
        
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-12">
      <div class="form-check form-check-inline">
        <input checked class="form-check-input nacionalidade" type="radio" name="nacionalidade" id="nacionalidade1" value="N">
        <label class="form-check-label" for="nacionalidade1">Sou Brasileiro</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input nacionalidade" type="radio" name="nacionalidade" id="nacionalidade2" value="S">
        <label class="form-check-label" for="nacionalidade2">Não Sou Brasileiro</label>
      </div>
    </div>
  </div>
  <div class="row brasileiro">
    <div class="col-12 mb-2">
      <h4>Dados Cliente</h4>
    </div>
    <div class="col-12 form-group mb-2">
      <label for="form1">Tipo Pessoa:</label>
      <select name="form1" id="form1" class="form-control form1">
        <option value="F">Física</option>
        <option value="J">Jurídica</option>
      </select> 
    </div>
    <div class="col-12 form-group mb-2">
      <label for="form2">Nome:</label>
      <input type="text" name="form2" id="form2" class="form-control form2"> 
    </div>
    <div class="col-12 form-group mb-2">
      <label for="form3">CPF/CNPJ:</label>
      <input type="text" name="form3" id="form3" class="form-control cpf form3"> 
    </div>
    <div class="col-12 form-group mb-2">
      <label for="form4">E-mail:</label>
      <input type="email" name="form4" id="form4" class="form-control form4"> 
    </div>
    <div class="col-12 form-group mb-2">
      <label for="form5">Telefone:</label>
      <input type="text" name="form5" id="form5" class="form-control form5"> 
    </div>
    <div class="col-12 form-group mb-2">
      <label for="form8">CNH</label>
      <input type="text" name="form8" id="form8" class="form-control form8"> 
    </div>
    <div class="col-12 form-group mb-2">
      <label for="form9">Obsevações</label>
      <textarea name="form9" id="form9" class="form-control"> 
      </textarea>
    </div>
  </div>
  <div class="row nbrasileiro">
    <div class="col-12 mb-2">
      <h4>Dados Cliente</h4>
    </div>
    <div class="col-12 form-group mb-2">
      <label for="form20">Nome:</label>
      <input type="text" name="form20" id="form20" class="form-control"> 
    </div>
    <div class="col-12 form-group mb-2">
      <label for="form22">E-mail:</label>
      <input type="email" name="form22" id="form22" class="form-control"> 
    </div>
    <div class="col-12 form-group mb-2">
      <label for="form23">Telefone:</label>
      <input type="text" name="form23" id="form23" class="form-control"> 
    </div>
    <div class="col-12 form-group mb-2">
      <label for="form21">Passaporte:</label>
      <input type="text" name="form21" id="form21" class="form-control"> 
    </div>
    <div class="col-12 form-group mb-2">
      <label for="form24">País</label>
      <input type="text" name="form24" id="form24" class="form-control"> 
    </div>
    <div class="col-12 form-group">
      <label for="form25">Obsevações</label>
      <textarea name="form25" id="form25" class="form-control"> 
      </textarea>
    </div>
  </div>
  <div class="row mt-4">
    <div class='col-12'>
      <input type="button" class='btn-reserva reservar' value='Solicitar Reserva'>
    </div>
  </div>
<?php
}

function geraModalMsg($msg){
?>
   <!-- Modal -->
   <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Aviso do sistema!</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body py-3">
          <?php echo $msg; ?>
        </div>
      </div>
    </div>
  </div>
  <script>
    $('#exampleModal').modal('show');
    $('#exampleModal').on('hidden.bs.modal',function(e){
      history.go(-1);
    })
  </script>
<?php
}

get_header('central'); 
?>

<!-- INICIO DA PÁGINA -->
<div id="page">
  <div id="titulo-topo" class="titulo-topo p-md-4 p-1">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1>Central de Reserva</h1>
          <h5>Alugue um carro na Henji e aproveite ao máximo seu passeio!</h5>
        </div>
      </div>
    </div>
  </div>

  <?php get_template_part( 'content', 'breadcrumb' ); ?>

  <div class="page-reserva mb-5">
    <div class="container">
      <div class="row">
        <?php
        if(isset($xml->NewDataSet->Erros_Avisos->mensagem) or !empty($msgerror)){
          if(!empty($msgerror)){
            geraModalMsg($msgerror);
          }else{
            geraModalMsg($xml->NewDataSet->Erros_Avisos->mensagem[0]);
          }
        }elseif(!empty($_POST['reservar'])){
        ?>
          <div class="col-12">
            <h2>Redirecionando</h2>
          </div>
        <?php
        }else{
        ?>
        <div class="col-md-7">
          <!-- **********************************************************
            PASSO  1 -->
          <div class="passo-1">
            <?php 
            if(isset($_POST['idgrupo'])){
              echo geraHtmlOpcionais($opcionais);
              echo geraHtmlProtecoes($protecoes);
              echo geraHtmlTermosCond($tarifas);
            ?>
              <div class='row mt-4'>
                <div class='col-6'>
                  <div class='form-check'>
                    <input class='form-check-input check-aprovacao' type='checkbox' id='aprovacao'>
                    <label class='form-check-label' for='aprovacao'>
                      Li e concordo com os termos acima descritos
                    </label>
                  </div>
                </div>
                <div class='col-6 text-right'>
                  <a href="#" class='btn-reserva btn-adicionais'>Próximo <i class="fas fa-caret-right"></i></a>
                </div>
              </div>
            <?php
            }else{
              echo geraHtmlGrupo($tarifas);
            }
            ?>
          </div>

          <!-- **********************************************************
            PASSO  2 -->
          <form action="<?php echo home_url( '/central-de-reserva' ); ?>" id="form-values" method="POST">
            <input type="hidden" name="dtretirada" value="<?php echo $dtretirada ?>">
            <input type="hidden" name="hrretirada" value="<?php echo $hrretirada ?>">
            <input type="hidden" name="dtretorno" value="<?php echo $dtretorno ?>">
            <input type="hidden" name="hrretorno" value="<?php echo $hrretorno ?>">
            <input type="hidden" name="lcretirada" value="<?php echo $lcretirada ?>">
            <input type="hidden" name="lcretorno" value="<?php echo $_POST['lcretorno'] ?>">
            <input type="hidden" name="adicionais" class='adicionais-input' value="">
            <input type="hidden" name="protecoes" class='protecoes-input' value="">
            <input type="hidden" name="idgrupo" class="idgrupo" value="<?php echo $idgrupo; ?>">
            <input type="hidden" name="idtarifa" class="idtarifa" value="<?php echo $idtarifa; ?>">
            <div class="passo-2">
              <?php formFinal(); ?>
            </div>
          </div>
        </form>
        <!-- **********************************************************
            FIM PASSO  2 -->
        <!-- **********************************************************
            BARRA LATERAL -->
        <div class="col-md-5">
          <?php if(isset($_POST['idgrupo'])){ ?>
            <div class="reserva-box mb-2">
              <div class="reserva-top">
                Total Estimado
              </div>

              <div class="px-2 py-2">
                <?php echo geraHtmlTotal($tarifas) ?>
              </div>
            </div>
          <?php } ?>

          <div class="reserva-box">
            <div class="reserva-top">
              <span>Informações da Reserva</span><a class="voltar" href="<?php echo home_url( ); ?>">alterar</a>
            </div>

            <div class="reserva-retirada px-2">
              <strong>Informações da Retirada</strong>
              <?php echo geraHtmlRetirada($lojas,$dtretirada,$hrretirada) ?>
            </div>
            <div class="reserva-retorno px-2">
              <strong>Informações do Retorno</strong>
              <?php echo geraHtmlRetorno($lojas,$dtretorno,$hrretorno) ?>
            </div>
          </div>

          <?php if(isset($_POST['idgrupo'])){ ?>
          <div class="reserva-box mt-2">
            <div class="reserva-top">
              <span>Informações do Veículo</span>
              <a class="voltar alterar-veiculo" href="#">alterar</a>
            </div>

            <div class="px-2">
              <?php echo geraHtmlVeiculo($tarifas) ?>
            </div>
          </div>
          <?php } ?>
        </div>
        <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>
