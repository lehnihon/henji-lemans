<?php
/**
 * The template for displaying all single posts.
 *
 * @package site
 */

get_header('blog-single'); ?>
<?php while ( have_posts() ) : the_post(); ?>
<style>
  #blog-single-header{
    background-image: url("<?php echo get_the_post_thumbnail_url(get_the_ID()); ?>");
  }
</style>
<div id="blog-single-header"></div>
<div id="page" class="blog-single">
	<div class="container">
    <div class="row">
      <div class="blog-content col-12">
        <p class="etime"><?php echo estimated_reading(get_the_content()); ?></p>
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
      </div>
      <div class="col-lg-10 offset-lg-1">
        <div class="subs-box">
          <?php
          $page = get_page_by_path( 'blog' );
          echo do_shortcode(get_field('blog_newsletter_single', $page->ID));
          ?>
        </div>
      </div>
      <div class="col-lg-10 offset-lg-1">
        <hr>
      </div>
      <div class="tags col-lg-10 offset-lg-1">
        <div class="row">
          <div class="col-12">
            <div class="row">
              <div class="col-lg-3 mb-lg-0 mb-3 self-align-center">
                <?php echo get_avatar( get_the_author_meta( 'ID' ) , '','','', array('class' => 'img-fluid img-avatar') ); ?>
              </div>
              <div class="col-lg-9">
                <h5><?php the_author(); ?></h5>
                <p><?php  the_author_meta('description'); ?></p>
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="comments col-lg-10 offset-lg-1">
        <?php
        if ( comments_open() || get_comments_number() ) :
          comments_template();
        endif;
        ?>
      </div>
    </div>
  </div>
  <div class="related">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Publicações relacionadas</h2>
        </div>
      </div>
      <div class="row blog-related-list"></div>
      <div class="row">
        <div class="col-12 text-center">
          <button data-id="2" class="btn-mais-single" title="">ver mais publicações</button>
        </div>
      </div>
    </div>
  </div>
</div><!-- #content -->
<?php endwhile; ?>
<?php get_footer(); ?>
