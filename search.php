<?php
get_header('blog'); 
?>
<div id="page" class="blog">
  <div class="container">
    <div class="row">
      <div class="col">
	  	<h1 class="page-title text-left"><?php printf( esc_html__( 'Resultados da procura por: %s', 'site' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
      </div>
    </div>
    <div class="row blog-materiais">
     <?php while ( have_posts() ) : the_post(); ?>
      <article class="col-lg-4 col-sm-6">
        <a href="<?php the_permalink(); ?>" title="">
          <div class="row">
            <div class="col-sm-4 mb-3 mb-sm-0">
              <?php 
              the_post_thumbnail('blog-thumb', array(
                'class' => "img-fluid",
              ))
              ?>
            </div>
            <div class="col-sm-8 align-self-center">
              <h5><?php the_title(); ?></h5>
              <p><?php the_excerpt_custom(20); ?></p>
            </div>
          </div>
        </a>
      </article>
      <?php endwhile; ?>
    </div>
  </div>
</div><!-- #content -->
<?php get_footer(); ?>

