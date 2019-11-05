<?php
get_header(); ?>
<div id="page">
  <div id="banner" class="banner">
    <div class="container">
      <div class="row">
        <div class="col-md-6 offset-md-3">
          <p>Fundada em 1980, a Marina Tropical é uma das 
          marinas mais antigas e tradicionais do Guarujá.</p>
          <p>Disposta numa área de 25000m², às margens do canal de 
          Bertioga, rodeada por mata atlântica e manguezais, a Marina 
          encontra-se numa das mais belas regiões do litoral paulista.</p>
          <p>Próxima aos mais exclusivos condomínios residências 
          do Guarujá, restaurantes renomados e, a poucos 
          minutos da saída para o mar. A Marina Tropical 
          dispõe de localização privilegiada.</p>
          <p>Possui capacidade para aproximadamente 400 embarcações, 
          sendo 150 vagas na bacia e 250 em garagens cobertas.</p>
          <p>
          A Marina ainda conta com restaurante, piscina com vestiários, estacionamento coberto para autos, portaria fechada e 
          segurança 24 horas.
          </p>
          <p>Foi a primeira Marina do Estado de São Paulo a obter o 
          certificado de LICENCIAMENTO AMBIENTAL, graças ao seu
          comprometimento com o meio ambiente como: áreas de
          preservação, tratamento de esgoto, coleta seletiva
          de lixo, entre outros.</p>
        </div>
      </div>
    </div>
  </div>
  <div id="estrutura" class="estrutura">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h2>NOSSA ESTRUTURA</h2>
        </div>
      </div>
    </div>
    <div class="slider">
      <?php while ( have_posts() ) : the_post(); ?>

        <?php the_content(); ?>

      <?php endwhile; // End of the loop. ?>
    </div>
  </div>
  <div id="servicos" class="servicos">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h2>SERVIÇOS</h2>
        </div>
      </div>
      <?php
      $args = array(
      'posts_per_page' => 999,
        'orderby' => 'post_date',
        'order' => 'DESC');
        $query = new WP_Query( $args );
      $order = 0; 
      ?>
      <?php if ( $query->have_posts() ) : ?>
        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
          <?php if($order%2 == 0):?>
            <article class="row">
              <div class="col-md-6 order-1 order-md-2">
                <?php the_post_thumbnail('home-thumb', array(
                  'class' => "img-fluid",
                )); ?>
              </div>
              <div class="col-md-6 order-2 order-md-1 align-self-md-center">
                <h3>
                  <?php the_title() ?>
                </h3>
                <p><?php the_content() ?></p>
              </div>
            </article>
          <?php else: ?>
            <article class="row">
              <div class="col-md-6 order-1 order-md-1">
                <?php the_post_thumbnail('home-thumb', array(
                  'class' => "img-fluid",
                )); ?>
              </div>
              <div class="col-md-6 order-2 order-md-2 align-self-md-center">
                <h3>
                  <?php the_title() ?>
                </h3>
                <p><?php the_content() ?></p>
              </div>
            </article>
          <?php endif; ?>
        <?php $order++; endwhile; ?>
      <?php endif; ?>
    </div>
  </div>
  <div id="localizacao" class="localizacao embed-responsive embed-responsive-21by9">
    <div class="container">
      <div class="box d-flex align-items-center">
        <div>
          <h2>LOCALIZAÇÃO</h2>
          <p class="text-center">
            Endereço:<br>
            Estrada Guarujá – Bertioga, 
            km 15,5 na cidade de Guarujá, cerca de 100 km de São Paulo. Pode ser acessada tanto via Guarujá ou Bertioga (balsa).
          </p>
        </div>
      </div>
    </div>
    
    <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12466.107827119102!2d-46.19714644321774!3d-23.89585806559786!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cd8d0bcc35d229%3A0xd7e4fa910bf169a3!2sMarina+Tropical+N%C3%A1utica!5e1!3m2!1spt-BR!2sbr!4v1556642555396!5m2!1spt-BR!2sbr" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>         
  </div>
</div>
<?php get_footer(); ?>
