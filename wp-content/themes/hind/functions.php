<?php
/**
 * Hind functions
 *
 * @package Hind
 */

/*
 *	@@@ iPanel Path Constant @@@
*/
define( 'IPANEL_PATH' , get_template_directory() . '/iPanel/' ); 

/*
 *	@@@ iPanel URI Constant @@@
*/
define( 'IPANEL_URI' , get_template_directory_uri() . '/iPanel/' );

/*
 *	@@@ Usage Constant @@@
*/
define( 'IPANEL_PLUGIN_USAGE' , false );


/*
 *	@@@ Include iPanel Main File @@@
*/
include_once IPANEL_PATH . 'iPanel.php';

global $hind_theme_options;

if(get_option('HIND_PANEL')) {
	
	$hind_theme_options = maybe_unserialize(get_option('HIND_PANEL'));

} else {
	$hind_theme_options = '';
}

if (!isset($content_width))
	$content_width = 810; /* pixels */

if (!function_exists('hind_setup')) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function hind_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Hind, use a find and replace
	 * to change 'hind' to the name of your theme in all the template files
	 */
	load_theme_textdomain('hind', get_template_directory() . '/languages');

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support('automatic-feed-links');

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support('post-thumbnails');

	/**
	 * Enable support for Title Tag
	 *
	 */
	function hind_theme_slug_setup() {
	   add_theme_support( 'title-tag' );
	}
	add_action( 'after_setup_theme', 'hind_theme_slug_setup' );

	/**
	 * Enable support for Logo
	 */
	add_theme_support( 'custom-header', array(
	    'default-image' =>  get_template_directory_uri() . '/img/logo.png',
            'width'         => 195,
            'flex-width'    => true,
            'flex-height'   => false,
            'header-text'   => false,
	));

	/**
	 *	Woocommerce support
	 */
	add_theme_support( 'woocommerce' );
	/**
	 * Enable custom background support
	 */
	add_theme_support( 'custom-background' );
	/**
	 * Change customizer features
	 */
	add_action( 'customize_register', 'hind_theme_customize_register' );
	function hind_theme_customize_register( $wp_customize ) {
		$wp_customize->remove_section( 'colors' );

		$wp_customize->add_setting( 'hind_header_transparent_logo' , array(
		    'default'     => '',
		    'transport'   => 'refresh',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'hind_header_transparent_logo', array(
		    'label'    => __( 'Logo for Transparent Header (Light logo)', 'hind' ),
		    'section'  => 'header_image',
		    'settings' => 'hind_header_transparent_logo',
		) ) );
	}

	/**
	 * Theme resize image
	 */
	add_image_size( 'blog-thumb', 1170, 660, true);
    add_image_size( 'mgt-post-image-large', 1170, 230, true);

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
            'primary' => __('Main Menu', 'hind'),
	) );
	/*
	* Change excerpt length
	*/
	function hind_new_excerpt_length($length) {
		global $hind_theme_options;

		if(isset($hind_theme_options['post_excerpt_legth'])) {
			$post_excerpt_length = $hind_theme_options['post_excerpt_legth'];
		} else {
			$post_excerpt_length = 18;
		}

		return $post_excerpt_length;
	}
	add_filter('excerpt_length', 'hind_new_excerpt_length');
	/**
	 * Enable support for Post Formats
	 */
	add_theme_support('post-formats', array('aside', 'image', 'gallery', 'video', 'audio', 'quote', 'link', 'status', 'chat'));
}
endif;
add_action('after_setup_theme', 'hind_setup');

/**
 * Enqueue scripts and styles
 */
function hind_scripts() {
	global $hind_theme_options;

	wp_register_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css');
	wp_enqueue_style( 'bootstrap' );

	wp_enqueue_style( 'hind-fonts', hind_google_fonts_url(), array(), '1.0' );

	wp_register_style('owl-main', get_template_directory_uri() . '/js/owl-carousel/owl.carousel.css');
	wp_register_style('owl-theme', get_template_directory_uri() . '/js/owl-carousel/owl.theme.css');
	wp_enqueue_style( 'owl-main' );
	wp_enqueue_style( 'owl-theme' );

	wp_register_style('stylesheet', get_stylesheet_uri(), array(), '1.2', 'all');
	wp_enqueue_style( 'stylesheet' );

	wp_register_style('responsive', get_template_directory_uri() . '/responsive.css', '1.0', 'all');
	wp_enqueue_style( 'responsive' );

	if(isset($hind_theme_options['enable_theme_animations']) && $hind_theme_options['enable_theme_animations']) {
		wp_register_style('animations', get_template_directory_uri() . '/css/animations.css');
		wp_enqueue_style( 'animations' );
	}

	if(isset($hind_theme_options['megamenu_enable']) && $hind_theme_options['megamenu_enable']) {
		wp_register_style('mega-menu', get_template_directory_uri() . '/css/mega-menu.css');
		wp_enqueue_style( 'mega-menu' );
		wp_register_style('mega-menu-responsive', get_template_directory_uri() . '/css/mega-menu-responsive.css');
		wp_enqueue_style( 'mega-menu-responsive' );
	}

	wp_register_style('font-awesome-4.5', get_template_directory_uri() . '/css/font-awesome.css');
	wp_register_style('select2-mgt', get_template_directory_uri() . '/js/select2/select2.css');
	wp_register_style('offcanvasmenu', get_template_directory_uri() . '/css/offcanvasmenu.css');
	wp_register_style('nanoscroller', get_template_directory_uri() . '/css/nanoscroller.css');

	wp_enqueue_style( 'font-awesome-4.5' );
	wp_enqueue_style( 'select2-mgt' );
	wp_enqueue_style( 'offcanvasmenu' );
	wp_enqueue_style( 'nanoscroller' );

	add_thickbox();
	
	wp_register_script('hind-bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '3.1.1', true);
	wp_register_script('hind-easing', get_template_directory_uri() . '/js/easing.js', array(), '1.3', true);
	wp_register_script('hind-template', get_template_directory_uri() . '/js/template.js', array(), '1.0', true);
	wp_register_script('hind-parallax', get_template_directory_uri() . '/js/jquery.parallax.js', array(), '1.1.3', true);
	wp_register_script('hind-select2', get_template_directory_uri() . '/js/select2/select2.min.js', array(), '3.5.1', true);
	wp_register_script('owl-carousel', get_template_directory_uri() . '/js/owl-carousel/owl.carousel.min.js', array(), '1.3.3', true);
	wp_register_script('nanoscroller', get_template_directory_uri() . '/js/jquery.nanoscroller.min.js', array(), '3.4.0', true);
	wp_register_script('mixitup', get_template_directory_uri() . '/js/jquery.mixitup.min.js', array(), '2.1.7', true);

	wp_register_script('tweenmax', get_template_directory_uri() . '/js/TweenMax.min.js', array(), '1.0', true);
	wp_register_script('scrollorama', get_template_directory_uri() . '/js/jquery.superscrollorama.js', array(), '1.0', true);

	wp_enqueue_script('hind-script', get_template_directory_uri() . '/js/template.js', array('jquery', 'hind-bootstrap', 'hind-easing', 'hind-parallax', 'hind-select2', 'owl-carousel', 'nanoscroller', 'mixitup', 'tweenmax', 'scrollorama'), '1.2', true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

}
add_action('wp_enqueue_scripts', 'hind_scripts');

// Custom theme title
add_filter( 'wp_title', 'hind_wp_title', 10, 2 );
function hind_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'hind' ), max( $paged, $page ) );
	}

	return $title;
}

/**
 * Enqueue scripts and styles for admin area
 */
function hind_admin_scripts() {
	wp_register_style( 'hind-style-admin', get_template_directory_uri() .'/css/admin.css' );
	wp_enqueue_style( 'hind-style-admin' );
	wp_register_style('font-awesome-admin', get_template_directory_uri() . '/css/font-awesome.css');
	wp_enqueue_style( 'font-awesome-admin' );

	wp_register_script('hind-template-admin', get_template_directory_uri() . '/js/template-admin.js', array(), '1.0', true);
	wp_enqueue_script('hind-template-admin');

}
add_action( 'admin_init', 'hind_admin_scripts' );

function hind_old_ie_fixes() {
    global $is_IE;
    if ( $is_IE ) {
        echo '<!--[if lt IE 9]>';
        echo '<script src="' . get_template_directory_uri() . '/js/html5shiv.js" type="text/javascript"></script>';
        echo '<![endif]-->';
    }
}
add_action( 'wp_head', 'hind_old_ie_fixes' );

function hind_load_wp_media_files() {
  wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'hind_load_wp_media_files' );

/**
 * Theme Welcome message
 */
function hind_show_admin_notice() {
    global $current_user;
	$user_id = $current_user->ID;

	if ( ! get_user_meta($user_id, 'mgt_hind_welcome_message_ignore') && ( current_user_can( 'install_plugins' ) ) ):
    ?>
    <div class="updated mgt-welcome-message">
    	<div class="mgt-welcome-message-show-steps"><div class="mgt-welcome-logo"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/logo.png" style="height: 20px;" alt="<?php bloginfo('name'); ?>"></div><p class="about-description" style="display: inline-block;margin-bottom: 0; margin-top:3px;margin-right: 5px;">Follow this steps to setup your Hind theme within minutes</p> <a class="button button-primary" id="mgt-welcome-message-show-steps">Show steps</a> <a class="button button-secondary" href="<?php echo esc_url( add_query_arg( 'mgt_welcome_message_dismiss', '0' ) );?>">Hide this message forever</a></div>
    	<div class="mgt-welcome-message-steps-wrapper">
	    	<h2>Thanks for choosing Hind WordPress theme</h2>
	        <p class="about-description">Follow this steps to setup your website within minutes:</p>
	    	<div class="mgt-divider"><a href="themes.php?page=install-required-plugins" class="button button-primary button-hero"><span class="button-step">1</span>Install required & recommended plugins</a></div>
	    	<div class="mgt-divider"><a href="themes.php?page=radium_demo_installer" class="button button-primary button-hero"><span class="button-step">2</span>Use 1-Click Demo Data Import</a></div>
	    	<div class="mgt-divider"><a href="admin.php?page=ipanel_HIND_PANEL" class="button button-primary button-hero"><span class="button-step">3</span>Manage theme options</a></div>
	    	<div class="mgt-divider"><a href="http://magniumthemes.com/go/hind-docs/" target="_blank" class="button button-secondary button-hero"><span class="button-step">4</span>Read Theme Documentation Guide</a></div>
	    	<div class="mgt-divider"><a href="http://eepurl.com/WXNyr" target="_blank" class="button button-secondary button-hero"><span class="button-step">5</span>Subscribe to updates</a></div>
			<div class="mgt-divider"><a href="http://magniumthemes.com/how-to-rate-items-on-themeforest/" target="_blank" class="button button-secondary button-hero"><span class="button-step">6</span>Rate our Theme if you enjoy it!</a><a id="mgt-dismiss-notice" class="button-secondary" href="<?php echo esc_url( add_query_arg( 'mgt_welcome_message_dismiss', '0' ) );?>">Hide this message</a></div>
    	</div>
    </div>
    <?php
	endif;
}
add_action( 'admin_notices', 'hind_show_admin_notice' );

function hind_welcome_message_dismiss() {
	global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if ( isset($_GET['mgt_welcome_message_dismiss']) && '0' == $_GET['mgt_welcome_message_dismiss'] ) {
	    add_user_meta($user_id, 'mgt_hind_welcome_message_ignore', 'true', true);
	}
}
add_action( 'admin_init', 'hind_welcome_message_dismiss' );
/**
 * Theme Update message
 */
function hind_show_admin_notice_update() {
	global $current_user;
	$user_id = $current_user->ID;

	if ( ! get_user_meta($user_id, 'mgt_hind_update_message_ignore') && ( current_user_can( 'install_plugins' ) ) ):
    ?>
    <div class="updated below-h2">
		<a href="<?php echo esc_url( add_query_arg( 'mgt_update_message_dismiss', '0' ) ); ?>" style="float: right;padding-top: 9px;">(never show this message again)&nbsp;&nbsp;<b>X</b></a><p style="display: inline-block;">Hi! Would you like to receive Hind theme updates news & get premium support? Subscribe to email notifications: </p>
		<form style="display: inline-block;" action="//magniumthemes.us8.list-manage.com/subscribe/post?u=6ff051d919df7a7fc1c84e4ad&amp;id=9285b358e7" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
		   <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="Your email">
		   <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
		</form>
		
    </div>
    <?php
	endif;
}
add_action( 'admin_notices', 'hind_show_admin_notice_update' );

function hind_update_message_dismiss() {
	global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if ( isset($_GET['mgt_update_message_dismiss']) && '0' == $_GET['mgt_update_message_dismiss'] ) {
	    add_user_meta($user_id, 'mgt_hind_update_message_ignore', 'true', true);
	}
}
add_action( 'admin_init', 'hind_update_message_dismiss' );

/**
 * Custom mega menu
 */
if(isset($hind_theme_options['megamenu_enable']) && $hind_theme_options['megamenu_enable']) {
	require get_template_directory() . '/inc/mega-menu/custom-menu.php';
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/theme-tags.php';

/**
 * Load theme functions.
 */
require get_template_directory() . '/inc/theme-functions.php';

/**
 * Load theme dynamic CSS.
 */
require get_template_directory() . '/inc/theme-css.php';

/**
 * Load theme dynamic JS.
 */
require get_template_directory() . '/inc/theme-js.php';

/**
 * Load theme metaboxes.
 */
require get_template_directory() . '/inc/theme-metaboxes.php';

/**
 * Load one click demo import.
 */
global $pagenow;

if (( $pagenow !== 'admin-ajax.php' ) && (is_admin())) {
	require get_template_directory() .'/inc/oneclick-demo-import/init.php';
}

// Register and load the widget
function wpb_load_widget() {
    register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

// Creating the widget
class wpb_widget extends WP_Widget {

    function __construct() {
        parent::__construct(

// Base ID of your widget
            'wpb_widget',

// Widget name will appear in UI
            __('WPBeginner Widget', 'wpb_widget_domain'),

// Widget description
            array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'wpb_widget_domain' ), )
        );
    }

// Creating widget front-end

    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );

// before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
        echo __( 'Hello, World!', 'wpb_widget_domain' );
        echo $args['after_widget'];
    }

// Widget Backend
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'wpb_widget_domain' );
        }
// Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

// Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
} // Class wpb_widget ends here
