<?php
/**
 * site functions and definitions
 *
 * @package site
 */

if ( ! function_exists( 'site_setup' ) ) :

function site_setup() {

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	add_image_size('home-thumb',600,450,true);

}
endif; // site_setup
add_action( 'after_setup_theme', 'site_setup' );


/**
 * Enqueue scripts and styles.
 */
function site_scripts() {
    wp_enqueue_style( 'site-owl', get_template_directory_uri() ."/assets/css/owl.carousel.min.css" );
    wp_enqueue_style( 'site-owl-theme', get_template_directory_uri() ."/assets/css/owl.theme.default.min.css" );
    wp_enqueue_style( 'site-style', get_stylesheet_uri() );

    wp_enqueue_script( 'site-script-jquery', get_template_directory_uri() ."/assets/js/jquery-3.1.1.min.js");
    wp_enqueue_script( 'site-script-jqueryui', "https://code.jquery.com/ui/1.12.1/jquery-ui.js");
    wp_enqueue_script( 'site-script-mask', get_template_directory_uri() . '/assets/js/jquery.mask.min.js');
    wp_enqueue_script( 'site-script-fontawesome', "https://kit.fontawesome.com/3bccf4d453.js");
    wp_enqueue_script( 'site-script-owl', get_template_directory_uri() ."/assets/js/owl.carousel.min.js");
    wp_enqueue_script( 'site-script', get_template_directory_uri() . '/assets/js/script.js',array(),false,true);
}
add_action( 'wp_enqueue_scripts', 'site_scripts' );

function change_post_menu_label() {
  global $menu;
  global $submenu;
  $menu[5][0] = 'Serviço';
  $submenu['edit.php'][5][0] = 'Serviços';
  $submenu['edit.php'][10][0] = 'Adicionar Serviços';
  echo '';
}
function change_post_object_label() {
      global $wp_post_types;
      $labels = &$wp_post_types['post']->labels;
      $labels->name = 'Serviços';
      $labels->singular_name = 'Serviço';
      $labels->add_new = 'Adicionar Serviço';
      $labels->add_new_item = 'Adicionar Serviço';
      $labels->edit_item = 'Editar Serviço';
      $labels->new_item = 'Serviço';
      $labels->view_item = 'Ver Serviço';
      $labels->search_items = 'Procurar Serviço';
      $labels->not_found = 'Serviço não encontrado';
      $labels->not_found_in_trash = 'Sem Serviços na lixeira';
}
add_action( 'init', 'change_post_object_label' );
add_action( 'admin_menu', 'change_post_menu_label' );

function the_breadcrumb()
{
    $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter = '>'; // delimiter between crumbs
    $home = 'Página Inicial'; // text for the 'Home' link
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before = '<span class="current">'; // tag before the current crumb
    $after = '</span>'; // tag after the current crumb
    global $post;
    $homeLink = get_bloginfo('url');
    if (is_home() || is_front_page()) {
        if ($showOnHome == 1) {
            echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a></div>';
        }
    } else {
        echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
        if (is_category()) {
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) {
                echo get_category_parents($thisCat->parent, true, ' ' . $delimiter . ' ');
            }
            echo $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;
        } elseif (is_search()) {
            echo $before . 'Search results for "' . get_search_query() . '"' . $after;
        } elseif (is_day()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('d') . $after;
        } elseif (is_month()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('F') . $after;
        } elseif (is_year()) {
            echo $before . get_the_time('Y') . $after;
        } elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
                if ($showCurrent == 1) {
                    echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
                }
            } else {
                $cat = get_the_category();
                $cat = $cat[0];
                $cats = get_category_parents($cat, true, ' ' . $delimiter . ' ');
                if ($showCurrent == 0) {
                    $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
                }
                echo $cats;
                if ($showCurrent == 1) {
                    echo $before . get_the_title() . $after;
                }
            }
        } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;
        } elseif (is_attachment()) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            $cat = $cat[0];
            echo get_category_parents($cat, true, ' ' . $delimiter . ' ');
            echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
            if ($showCurrent == 1) {
                echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
        } elseif (is_page() && !$post->post_parent) {
            if ($showCurrent == 1) {
                echo $before . get_the_title() . $after;
            }
        } elseif (is_page() && $post->post_parent) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            for ($i = 0; $i < count($breadcrumbs); $i++) {
                echo $breadcrumbs[$i];
                if ($i != count($breadcrumbs)-1) {
                    echo ' ' . $delimiter . ' ';
                }
            }
            if ($showCurrent == 1) {
                echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
        } elseif (is_tag()) {
            echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
        } elseif (is_author()) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . 'Articles posted by ' . $userdata->display_name . $after;
        } elseif (is_404()) {
            echo $before . 'Error 404' . $after;
        }
        if (get_query_var('paged')) {
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                echo ' (';
            }
            echo __('Page') . ' ' . get_query_var('paged');
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                echo ')';
            }
        }
        echo '</div>';
    }
} // end the_breadcrumb()