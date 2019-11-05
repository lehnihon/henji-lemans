<?php
get_header(); ?>
<div id="page" class="back1">
  <?php while ( have_posts() ) : the_post(); ?>

    <?php the_content(); ?>

  <?php endwhile; // End of the loop. ?>
</div>
<?php get_footer(); ?>
