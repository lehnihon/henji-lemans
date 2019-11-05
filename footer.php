<div id="footer">
  <div class="container">
    <div class="row no-gutters">
      <div class="col-md-8 left-back pr-md-3">
        <div class="row">
          <div class="col-sm-4 mb-sm-5">
            <h4>Institucional</h4>
            <ul>
              <li><a href="<?php echo home_url( '/a-empresa' ); ?>">Sobre a Empresa</a></li>
              <li><a href="<?php echo home_url( '/frota' ); ?>">Nossa Frota</a></li>
            </ul> 
          </div>
          <div class="col-sm-4">
            <h4>Informações</h4>
            <ul>
              <li><a href="<?php echo home_url( '/condicoes-de-locacao' ); ?>">Condições de Locação</a></li>
              <li><a href="<?php echo home_url( '/politica-de-privacidade' ); ?>">Política de Privacidade</a></li>
            </ul> 
          </div>
          <div class="col-sm-4">
            <h4>Acesse</h4>
            <ul>
              <li><a href="http://henjiweb.com.br/Login/Login.aspx?ReturnUrl=%2fAdministracao%2fCentralReserva%2fDefault.aspx">Administração</a></li>
            </ul> 
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <h4>Localização</h4>
            <p>
              Avenida Monte Castelo, 60<br>
              Bairro Proença - Campinas<br>
              CEP: 13.026-240 
            </p>
          </div>
          <div class="col-sm-4">
            <h4>Atendimento</h4>
            <ul>
              <li>(19) 3255-7770 </li>
              <li>(19) 99772-1714</li>
            </ul> 
          </div>
        </div>
      </div>
      <div class="col-md-4 right-back pl-md-5">
        <form action="<?php echo home_url( '' ); ?>"  method="GET">
        <div class="row">
          <div class="col-12">
            <h4>Receba nossas ofertas</h4>
            <p>Cadastre seu e-mail e receba ofertas especiais</p>
            <div class="row">
              <div class="col-8">
                <input type="text" name="email" class="news" placeholder="Digite seu e-mail aqui">
              </div>
              <div class="col-4">
                <input type="submit" name="news" class="btn-news" value="Enviar">
              </div>
            </div><br><br>
            <h4>Siga Henji</h4>
            <div class="fb-page" data-href="https://www.facebook.com/henjioficial/" data-tabs="timeline" data-width="" data-height="72" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/henjioficial/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/henjioficial/">Henji</a></blockquote></div>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="footer-copy">
  <div class="container">
    <div class="row py-4">
      <div class="col-12 text-center">
      © Lemans Locadora - Todos os direitos reservados <?php echo date('Y'); ?>
      </div>
    </div>
  </div>
</div>
<?php wp_footer(); ?>