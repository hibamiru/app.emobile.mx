<?php
/** Archivo de funciones EMobile */

/**
 * Funciones personalizadas de Soluciones Hipermedia
 */
//IMPRIME ARRAY EN FORMATO LEGIBLE
function print_array($array=array()) {
	echo '<pre style="display:block;">'; print_r($array); echo '</pre>';
}
//Enable errors
function show_errors($array=array()) {
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
}
/** Imprime una variable de OT; valida que exista la función y permite 
 * imprimir un valor por defecto si el campo está vacio  
 */
function print_ot($variable, $defecto) { 
  if (function_exists ('ot_get_option')) {
        	if (ot_get_option ($variable) != '') { echo ot_get_option ($variable); } else { echo $defecto; } 
  } else { echo 'No esta activado OT'; }
}
/** Regresa una variable de OT; valida que exista la función y permite
 * imprimir un valor por defecto si el campo está vacio
 */
function get_ot($variable) {
  if (function_exists ('ot_get_option')) {
        	if (ot_get_option ($variable) != '') { return ot_get_option ($variable); } else { return ''; } 
  } 
}
/** Imprime el url del home  */
function inicio_url() {
	echo get_home_url();
}
/** Envía el valor del url del home  */
function get_inicio_url() {
	return get_home_url();
}
//Adds vars to the allowed query vars
add_action('init','wpse46108_register_param');
function wpse46108_register_param() { 
    global $wp; 
    $wp->add_query_var('id_planeacion'); 
}
/** Envía el valor del url del tema en uso  */
function plantilla_url() {
	echo get_bloginfo( 'template_url' );
}
/** Envía el valor del url del tema en uso  */
function get_plantilla_url() {
	return get_bloginfo( 'template_url' );
}
function the_social_share() {
	get_template_part( 'socialshare');
}

/**
 * Pide a WP que corra emobile_setup() cuando el hook 'after_setup_theme' se está ejecutando
 */
add_action( 'after_setup_theme', 'emobile_setup' );

if ( ! function_exists( 'emobile_setup' ) ):
/**
 * Establece y activa varias de las capacidades de WordPress para el tema y desactiva otras
 *
 */
function emobile_setup() {

	/* Permite traducir el tema
	 * la traducción se agrega en el directorio /languages/ 
	 */
	load_theme_textdomain( 'emobile', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'emobile' ) );

	// Add support for custom backgrounds.
	add_theme_support( 'custom-background', array(
		// Let WordPress know what our default background color is.
		// This is dependent on our current color scheme.
		'default-color' => $default_background_color,
	) );

	// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
	add_theme_support( 'post-thumbnails' );

	//Register new menus
	register_nav_menu( 'login', __( 'Login', '' ) );
	register_nav_menu( 'participante', __( 'Participante', '' ) );
	
	// Elimina algunas opciones del menu de administración de WP
	add_action( 'admin_menu', 'my_remove_menus', 999 );

	function my_remove_menus() {

		// provide a list of usernames who can edit all menues here
		$admins = array( 
			'admin',
			'hipermedia',
		);
	 
		// get the current user
		$current_user = wp_get_current_user();
	 
		// match and remove if needed
		if( !in_array( $current_user->user_login, $admins ) )
		{
			//TOP MENUES
			remove_menu_page( 'tools.php' );					//Tools
			remove_menu_page( 'plugins.php' );					//Plugins
			remove_menu_page( 'ot-settings' );					//OT Settings
			remove_menu_page('edit.php?post_type=acf');			//ACF Settings
			
			//SUBMENUES
			remove_submenu_page( 'themes.php', 'themes.php' );					//Theme changer
			remove_submenu_page( 'themes.php', 'theme-editor.php' );					//Theme Editor
			remove_submenu_page( 'options-general.php', 'options-permalink.php' );		//Permalinks option
		}
	 
	}
	
}
endif; // emobile_setup

/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function emobile_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'emobile_excerpt_length' );

if ( ! function_exists( 'emobile_continue_reading_link' ) ) :
/**
 * Returns a "Continue Reading" link for excerpts
 */
function emobile_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'emobile' ) . '</a>';
}
endif; // emobile_continue_reading_link

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and emobile_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function emobile_auto_excerpt_more( $more ) {
	return ' &hellip;' . emobile_continue_reading_link();
}
add_filter( 'excerpt_more', 'emobile_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function emobile_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= emobile_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'emobile_custom_excerpt_more' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function emobile_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'emobile_page_menu_args' );

/**
 * Register our sidebars and widgetized areas. Also register the default Epherma widget.
 *
 * @since EMobile 1.0
 */
function emobile_widgets_init() {

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'emobile' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'emobile_widgets_init' );

if ( ! function_exists( 'emobile_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function emobile_content_nav( $html_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo esc_attr( $html_id ); ?>">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'emobile' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'emobile' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'emobile' ) ); ?></div>
		</nav><!-- #nav-above -->
	<?php endif;
}
endif; // emobile_content_nav


/**
 * Return the first link from the post content. If none found, the
 * post permalink is used as a fallback.
 *
 * @uses get_url_in_content() to get the first URL from the post content.
 *
 * @return string
 */
function emobile_get_first_url() {
	$content = get_the_content();
	$has_url = function_exists( 'get_url_in_content' ) ? get_url_in_content( $content ) : false;

	if ( ! $has_url )
		$has_url = emobile_url_grabber();

	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}

/**
 * Return the URL for the first link found in the post content.
 *
 * @since EMobile 1.0
 * @return string|bool URL or false when no link is present.
 */
function emobile_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}

if ( ! function_exists( 'emobile_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own emobile_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since EMobile 1.0
 */
function emobile_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'emobile' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'emobile' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'emobile' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'emobile' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'emobile' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'emobile' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'emobile' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for emobile_comment()

if ( ! function_exists( 'emobile_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own emobile_posted_on to override in a child theme
 *
 * @since EMobile 1.0
 */
function emobile_posted_on() {
	printf( __( '<span class="sep">Publicado el </span><strong><time class="entry-date" datetime="%3$s">%4$s</time></strong><span class="by-author"> <span class="sep"> por </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'emobile' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'Ver todas las publicaciones por %s', 'emobile' ), get_the_author() ) ),
		get_the_author()
	);
}
endif;



/**
 *  Crea los meta tag de Google en nuestro tema de WordPress
 */
function add_google_tags() {
	global $post;
	$image_obt = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium' );
	$image = $image_obt['0'];
	if (!$image)
	$image =  get_bloginfo( 'template_url' ) . '/images/logo.png';
?>

<meta itemprop="name" content="<?php the_title(); ?>">
<meta itemprop="description" content="<?php wp_limit_post(120,'...',true); ?>">
<meta itemprop="image" content="<?php echo $image; ?>">

<?php 
add_action('wp_head', 'add_google_tags',99);
}

/** BOOTSRAP INTEGRATION */
/**
 *  Agrega los js necesarios si el navegador es una versión anterior a IE9
 */

function theme_js() {

	global $wp_scripts;

	wp_register_script( 'html5_shiv', 'https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js', '', '', false );
	wp_register_script( 'respond_js', 'https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js', '', '', false );


	$wp_scripts->add_data( 'html5_shiv', 'conditional', 'lt IE 9' );
	$wp_scripts->add_data( 'respond_js', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'bootstrap_js', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '', true );
}
add_action( 'wp_enqueue_scripts', 'theme_js' );

/**
 *  Desaparece el menu de administración
 */
add_filter( 'show_admin_bar', '__return_false' );




/**
 *  LIMITA CARACTERES DEL EXTRACTO DE WORDPRESS
 */

function wp_limit_post($max_char, $more_link_text = '[...]',$notagp = false, $stripteaser = 0, $more_file = '') {
    $content = get_the_excerpt($more_link_text, $stripteaser, $more_file);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    $content = strip_tags($content);

   if (strlen($_GET['p']) > 0) {
      if($notagp) {
      echo substr($content,0,$max_char);
      }
      else {
      echo '<p>';
      echo substr($content,0,$max_char);
      echo "</p>";
      }
   }
   else if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) {
        $content = substr($content, 0, $espacio);
        $content = $content;
        if($notagp) {
        echo substr($content,0,$max_char);
        echo $more_link_text;
        }
        else {
        echo '<p>';
        echo substr($content,0,$max_char);
        echo $more_link_text;
        echo "</p>";
        }
   }
   else {
      if($notagp) {
      echo substr($content,0,$max_char);
      }
      else {
      echo '<p>';
      echo substr($content,0,$max_char);
      echo "</p>";
      }
   }
}

/**
 * Retrieves the IDs for images in a gallery.
 *
 * @uses get_post_galleries() first, if available. Falls back to shortcode parsing,
 * then as last option uses a get_posts() call.
 *
 * @since EMobile 1.6.
 *
 * @return array List of image IDs from the post gallery.
 */
function emobile_get_gallery_images() {
	$images = array();

	if ( function_exists( 'get_post_galleries' ) ) {
		$galleries = get_post_galleries( get_the_ID(), false );
		if ( isset( $galleries[0]['ids'] ) )
		 	$images = explode( ',', $galleries[0]['ids'] );
	} else {
		$pattern = get_shortcode_regex();
		preg_match( "/$pattern/s", get_the_content(), $match );
		$atts = shortcode_parse_atts( $match[3] );
		if ( isset( $atts['ids'] ) )
			$images = explode( ',', $atts['ids'] );
	}

	if ( ! $images ) {
		$images = get_posts( array(
			'fields'         => 'ids',
			'numberposts'    => 999,
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
			'post_mime_type' => 'image',
			'post_parent'    => get_the_ID(),
			'post_type'      => 'attachment',
		) );
	}

	return $images;
}

//Fecha en array. Procesa una fecha en formato año, mes, día. El separador es obligatorio y puede ser cualquier símbolo.
function fecha_en_array($fecha_para_array) {
	$fecha_en_array['year'] = substr($fecha_para_array, -10, 4);
	$fecha_en_array['month'] = substr($fecha_para_array, -5, 2);
	$fecha_en_array['day'] = substr($fecha_para_array, -2, 2);
	return $fecha_en_array;
}

//Mes en texto. Convierte un número en formato 00 a el mes correspondiente.
function mes_en_texto($num_mes) { 
  switch ($num_mes) {
	case "01": 	echo "enero"; break;
    case "02": 	echo "febrero"; break;
	case "03": 	echo "marzo"; break;
    case "04": 	echo "abril"; break;
	case "05": 	echo "mayo"; break;
    case "06": 	echo "junio"; break;
	case "07": 	echo "julio"; break;
    case "08": 	echo "agosto"; break;
	case "09": 	echo "septiembre"; break;
    case "10": 	echo "octubre"; break;
	case "11": 	echo "noviembre"; break;
    case "12": 	echo "diciembre"; break;
    }
}

//Paginación
if ( ! function_exists( 'the_numbered_nav' ) ) :
function the_numbered_nav() { ?>
	<?php global $wp_query; ?>
	<nav id="numbered-pagination">
		<?php $big = 999999999; // need an unlikely integer
        echo paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
            'total' => $wp_query->max_num_pages,
        ) ); ?>
	</nav>
<?php }
endif; // emobile_numerated_nav

//Paginación personalizada
if ( ! function_exists( 'the_custom_numbered_nav' ) ) :
function the_custom_numbered_nav( $custom_query ) { ?>
	<?php $custom_query; ?>
	<nav id="numbered-pagination">
		<?php $big = 999999999; // need an unlikely integer
        echo paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
            'total' => $custom_query->max_num_pages,
        ) ); ?>
	</nav>
<?php }
endif; // emobile_numerated_nav

//FLEXSLIDER
function flexslider_sh() {
$template_url = get_bloginfo( 'template_url' );

	wp_enqueue_style( 'flexslider-style', $template_url .'/inc/flexslider/flexslider.css', '1' );

	wp_enqueue_script( 'flexslider', $template_url .'/inc/flexslider/jquery.flexslider-min.js', array('jquery'), '1.10.2', 1);
		
	/*wp_enqueue_script( 'methodsvalidate', $template_url .'/js/additional-methods.js', array('jquery'), '1.10.2', 1);*/
	
	wp_enqueue_script( 'config-flexslider', $template_url .'/inc/flexslider/config.js', array('jquery','flexslider'), '', 1);
}

//FLEXSLIDER CUSTOM CONFIG
function flexslider_custom_config_sh() {
$template_url = get_bloginfo( 'template_url' );

	wp_enqueue_style( 'flexslider-style', $template_url .'/inc/flexslider/flexslider.css', '1' );

	wp_enqueue_script( 'flexslider', $template_url .'/inc/flexslider/jquery.flexslider-min.js', array('jquery'), '1.10.2', 1);
}

//SMOOTH TABS
function smooth_tabs() {
$template_url = get_bloginfo( 'template_url' );

	wp_enqueue_style( 'smooth-tabs-style', $template_url .'/inc/tabs/jquery.smooth_tabs.css', '1' );

	wp_enqueue_script( 'smooth-tabs', $template_url .'/inc/tabs/jquery.smooth_tabs.js', array('jquery'), '1.10.2', 1);
	
	wp_enqueue_script( 'config-smooth-tabs', $template_url .'/inc/tabs/smooth_tabs.config.js', array('jquery','smooth-tabs'), '', 1);
}

//PRETTY PHOTO
function pretty_photo_sh() {
$template_url = get_bloginfo( 'template_url' );

	wp_enqueue_style( 'prettyphotho-style', $template_url .'/inc/prettyphoto/css/prettyPhoto.css', '1' );

	wp_enqueue_script( 'prettyphoto', $template_url .'/inc/prettyphoto/js/jquery.prettyPhoto.js', array('jquery'), '1.10.2', 1);

	wp_enqueue_script( 'config-prettyphoto', $template_url .'/inc/prettyphoto/js/config.js', array('jquery','prettyphoto'), '', 1);
}

/* */
//Agrego mi archivo JS al formulario de edición de planeación
function editar_planeacion_sh() {
	$template_url = get_bloginfo( 'template_url' );
	wp_enqueue_script( 'editar_planeacion', $template_url . '/js/editar_planeacion.js', array('jquery'), '1.0', 1);
}
add_action( 'wp_enqueue_scripts', 'editar_planeacion_sh' );

//LOGIN FOOTER
//function custom_login_footer() {
    //return '<a href="' . site_url( '/alta-de-participante' ) . '">Registrarse</a>';
//}
//add_filter('login_form_bottom', 'custom_login_footer');

add_action('init','custom_login');
function custom_login(){
	global $pagenow;
	if( 'wp-login.php' == $pagenow ) {
		wp_redirect( home_url() );
	}
}

//LOGOUT REDIRECT
function go_home(){
  wp_redirect( home_url() );
  exit();
}
add_action('wp_logout','go_home');

//EMAILS THROUGH SMTP
add_action( 'phpmailer_init', 'send_through_smtp' );
function send_through_smtp( $phpmailer ) {
	$from_name				= $phpmailer->FromName;
	$phpmailer->Mailer		= 'smtp';
	$phpmailer->Sender		= 'servicio.de.correo@solucioneshipermedia.com';
	$phpmailer->ReturnPath	= 'servicio.de.correo@solucioneshipermedia.com';
	$phpmailer->From		= 'servicio.de.correo@solucioneshipermedia.com';
	$phpmailer->FromName	= 'Educación Mobile';
	$phpmailer->SMTPSecure	= 'ssl';
	$phpmailer->Host		= 'email-smtp.us-east-1.amazonaws.com';
	$phpmailer->Port		= 465;
	$phpmailer->SMTPAuth	= TRUE;
	$phpmailer->Username	= 'AKIAJWCJTKMZTSG6KR7A';
	$phpmailer->Password	= 'Agw6Jq+KkXEYPbioso5HgiJGWqSUpZpkXHtdeL4LkANM';
}

//User redirect
function user_redirect() {
	$user_ID = get_current_user_id();
	$is_logged_in = is_user_logged_in();
	$is_regular = get_user_meta( $user_ID, 'tipo_usuario', true );
	$is_fc = get_user_meta( $user_ID, 'p_fc', true );
	$is_ipad_sn = get_user_meta( $user_ID, 'p_nserie_ipad', true );
	$is_home = is_home();
	if ($is_logged_in)
	{
		
		//Don't bother regular users
		if ($is_regular)
		{
			
			if (is_page('escritorio') OR is_page('agregar-planeacion') OR is_page('registro'))
			{
				wp_redirect( site_url( '/buscar-planeaciones/' ) ); exit;
			}
			
		}

		if (!$is_fc)
		{
			if (is_page_template('validar-formulario.php'))
			{
				if ($is_fc)
				{
					wp_redirect( site_url( '/escritorio/' ) ); exit;
				}
			}
			else if (!is_page_template('registro.php'))
			{
				wp_redirect( site_url( '/registro/' ) ); exit;
			}
		}
		else
		{
			if (is_page_template('registro.php'))
			{
				wp_redirect( site_url( '/escritorio/' ) ); exit;
			}
			if (is_page_template('validar-formulario.php'))
			{
				if ($is_fc)
				{
					wp_redirect( site_url( '/escritorio/' ) ); exit;
				}
			}
			if (is_page_template('serie-ipad.php'))
			{
				if($is_ipad_sn)
				{
					wp_redirect( site_url( '/escritorio/' ) ); exit;
				}
			}
			else if ($is_home)
			{
				wp_redirect( site_url( '/escritorio/' ) ); exit;
			}
		}
			
	}
	else
	{
		if (!$is_home)
		{
			wp_redirect( site_url() ); exit;
		}
	}
}

//PARTICIPANT CUSTOM DATA
function participant_c_data() {
	$user_ID	= get_current_user_id();
	$user_meta	= get_user_meta( $user_ID );
	$user_data	= array();
	
	//Personal data
	$p_foto								= wp_get_attachment_image_src( $user_meta['p_foto'][0], 'thumbnail' );
	$p_mime_type						= get_post_mime_type($user_meta['p_foto'][0]);
	$user_data['mime_type']				= $p_mime_type;
	switch ($p_mime_type) {
		case 'image/jpg':
			$user_data['p_foto']		= $p_foto[0];
			break;
		case 'image/jpeg':
			$user_data['p_foto']		= $p_foto[0];
			break;
		default:
			$user_data['p_foto']		= get_bloginfo( 'template_url' ) . '/images/default-avatar.jpg';
			break;
	}
	$user_data['p_nombre']				= $user_meta['p_nombre'][0];
	$user_data['p_apellido_p']			= $user_meta['p_apellido_p'][0];
	$user_data['p_apellido_m']			= $user_meta['p_apellido_m'][0];
	$user_data['p_sexo']				= $user_meta['p_sexo'][0];
	$user_data['p_edad']				= $user_meta['p_edad'][0];
	$user_data['p_sede_c']				= $user_meta['p_sede_c'][0];
	$user_data['p_lider_c']				= $user_meta['p_lider_c'][0];
	$user_data['p_cuenta_icloud']		= $user_meta['p_cuenta_icloud'][0];
	$user_data['p_rfc']					= strtoupper($user_meta['p_rfc'][0]);
	$user_data['p_curp']				= strtoupper($user_meta['p_curp'][0]);
	$user_data['p_email']				= $user_meta['nickname'][0];
	$user_data['p_telefono_p']			= $user_meta['p_telefono_p'][0];
	$user_data['p_telefono_c']			= $user_meta['p_telefono_c'][0];
	
	//Job data
	$user_data['p_nivel_se']			= $user_meta['p_nivel_se'][0];
	$user_data['p_modalidad_pre_pri']	= $user_meta['p_modalidad_pre_pri'][0];
	$user_data['p_modalidad_sec']		= $user_meta['p_modalidad_sec'][0];
	$user_data['p_modalidad_eesp']		= $user_meta['p_modalidad_eesp'][0];
	$user_data['p_modalidad']			= '';
	if ($user_data['p_nivel_se'] == 'Preescolar' OR $user_data['p_nivel_se'] == 'Primaria')
	{
		$user_data['p_modalidad']		= $user_data['p_modalidad_pre_pri'];
	}
	else if ($user_data['p_nivel_se'] == 'Secundaria')
	{
		$user_data['p_modalidad']		= $user_data['p_modalidad_sec'];
	}
	else if ($user_data['p_nivel_se'] == 'Educación especial')
	{
		$user_data['p_modalidad']		= $user_data['p_modalidad_eesp'];
	}
	$user_data['p_educacion_especial']	= $user_meta['educacion_especial'][0];
	if ($user_data['p_educacion_especial']) {
		$user_data['p_modalidad_eesp']	= $user_data['p_modalidad_eesp'];
	}
	$user_data['p_sostenimiento']		= $user_meta['p_sostenimiento'][0];
	$user_data['p_funcion_grado']		= unserialize($user_meta['p_funcion_grado'][0]);
	$user_data['p_nombre_ct']			= $user_meta['p_nombre_ct'][0];
	$user_data['p_clave_cct']			= strtoupper($user_meta['p_clave_cct'][0]);
	$user_data['p_telefono_t']			= $user_meta['p_telefono_t'][0];
	$user_data['p_localidad']			= $user_meta['p_localidad'][0];
	$user_data['p_municipio']			= $user_meta['p_municipio'][0];
	$user_data['p_zona_escolar']		= $user_meta['p_zona_escolar'][0];
	$user_data['p_sector_escolar']		= $user_meta['p_sector_escolar'][0];
	$user_data['p_antiguedad_se']		= $user_meta['p_antiguedad_se'][0];
	$user_data['p_cantidad_alumnos']	= $user_meta['p_cantidad_alumnos'][0];

	//Academic data
	$user_data['p_participa_cm']		= $user_meta['p_participa_cm'][0];
	$user_data['p_nivel_cm']			= '';
	$user_data['p_vertiente_cm']		= '';
	if ($user_data['p_participa_cm'] == 'Sí')
	{
		$user_data['p_nivel_cm']		= $user_meta['p_nivel_cm'][0];
		$user_data['p_vertiente_cm']	= $user_meta['p_vertiente_cm'][0];
	}
	$user_data['p_licenciatura']		= $user_meta['p_licenciatura'][0];
	$user_data['p_institucion']			= $user_meta['p_institucion'][0];
	$user_data['p_grado_estudios']		= $user_meta['p_grado_estudios'][0];
	$user_data['p_institucion_uge']		= $user_meta['p_institucion_uge'][0];
	$user_data['p_estudios_actuales']	= $user_meta['p_estudios_actuales'][0];
	$user_data['p_nserie_ipad']			= $user_meta['p_nserie_ipad'][0];

	//Principals data
	
	$user_data['p_organizacion']		= '';
	$user_data['p_ngrupos_participantes']='';
	$user_data['p_ct_gra_1']			= '';
	$user_data['p_ct_gru_1']			= '';
	$user_data['p_ct_gra_2']			= '';
	$user_data['p_ct_gru_2']			= '';
	$user_data['p_ct_gra_3']			= '';
	$user_data['p_ct_gru_3']			= '';
	$user_data['p_ct_gra_4']			= '';
	$user_data['p_ct_gru_4']			= '';
	$user_data['p_ct_nserie_ipads']		= '';
	if (in_array('Dirección',$user_data['p_funcion_grado']))
	{
		$user_data['p_organizacion']	= $user_meta['p_organizacion'][0];
		$user_data['p_ngrupos_participantes']=$user_meta['p_ngrupos_participantes'][0];
		$user_data['p_ct_gra_1']		= $user_meta['p_ct_gra_1'][0];
		$user_data['p_ct_gru_1']		= $user_meta['p_ct_gru_1'][0];
		$user_data['p_ct_gra_2']		= $user_meta['p_ct_gra_2'][0];
		$user_data['p_ct_gru_2']		= $user_meta['p_ct_gru_2'][0];
		$user_data['p_ct_gra_3']		= $user_meta['p_ct_gra_3'][0];
		$user_data['p_ct_gru_3']		= $user_meta['p_ct_gru_3'][0];
		$user_data['p_ct_gra_4']		= $user_meta['p_ct_gra_4'][0];
		$user_data['p_ct_gru_4']		= $user_meta['p_ct_gru_4'][0];
		$user_data['p_ct_nserie_ipads']	= '';
		$ipads = get_field('p_ct_nserie_ipads', 'user_' . $user_ID);
		if ($ipads)
		{
			foreach ($ipads as $ipad_array)
			{
				foreach ($ipad_array as $ipad_s)
				{
					$ipads_array[$ipad_s] = $ipad_s;
				}
			}
			$ipads_array = array_values($ipads_array);
			$user_data['p_ct_nserie_ipads'] = $ipads_array;
		}
	}
	//Form completed
	$user_data['p_fc']					= $user_meta['p_fc'][0];

	return $user_data;
}

//PARTICIPANT CUSTOM DATA
function allow_form_validation() {
	$user_ID	= get_current_user_id();
	$user_meta	= get_user_meta( $user_ID );
	$user_data	= array();
	
	//Personal data
	$user_data['p_foto']				= $user_meta['p_foto'][0]?'True':'False';;
	$user_data['p_nombre']				= $user_meta['p_nombre'][0]?'True':'False';
	$user_data['p_apellido_p']			= $user_meta['p_apellido_p'][0]?'True':'False';
	$user_data['p_apellido_m']			= $user_meta['p_apellido_m'][0]?'True':'False';
	$user_data['p_curp']				= $user_meta['p_curp'][0]?'True':'False';
	$user_data['p_rfc']					= $user_meta['p_rfc'][0]?'True':'False';
	$user_data['p_sexo']				= $user_meta['p_sexo'][0]?($user_meta['p_sexo'][0]!='null'?'True':'False'):('False');
	$user_data['p_edad']				= $user_meta['p_edad'][0]?'True':'False';
	$user_data['p_sede_c']				= $user_meta['p_sede_c'][0]?($user_meta['p_sede_c'][0]!='null'?'True':'False'):('False');
	$user_data['p_lider_c']				= $user_meta['p_lider_c'][0]?($user_meta['p_lider_c'][0]!='null'?'True':'False'):('False');
	$user_data['p_cuenta_icloud']		= $user_meta['p_cuenta_icloud'][0]?'True':'False';
	$user_data['p_email']				= $user_meta['nickname'][0]?'True':'False';
	$user_data['p_telefono_p']			= 'True';
	$user_data['p_telefono_c']			= 'True';
	
	//Job data
	$user_data['p_nivel_se']			= $user_meta['p_nivel_se'][0]?($user_meta['p_nivel_se'][0]!='null'?'True':'False'):('False');
	$user_data['p_modalidad_pre_pri']	= $user_meta['p_modalidad_pre_pri'][0];
	$user_data['p_modalidad_sec']		= $user_meta['p_modalidad_sec'][0];
	$user_data['p_modalidad_eesp']		= $user_meta['p_modalidad_eesp'][0];
	$user_data['p_modalidad']			= 'False';
	if ($user_meta['p_nivel_se'][0] == 'Preescolar' OR $user_meta['p_nivel_se'][0] == 'Primaria')
	{
		$user_data['p_modalidad']		= $user_meta['p_modalidad_pre_pri']?($user_meta['p_modalidad_pre_pri'][0]!='null'?'True':'False'):('False');
	}
	else if ($user_meta['p_nivel_se'][0] == 'Secundaria')
	{
		$user_data['p_modalidad']		= $user_meta['p_modalidad_sec']?($user_meta['p_modalidad_sec'][0]!='null'?'True':'False'):('False');
	}
	else if ($user_meta['p_nivel_se'][0] == 'Educación especial')
	{
		$user_data['p_modalidad']		= $user_meta['p_modalidad_eesp']?($user_meta['p_modalidad_eesp'][0]!='null'?'True':'False'):('False');
	}
	$user_data['p_sostenimiento']		= $user_meta['p_sostenimiento'][0]?($user_meta['p_sostenimiento'][0]!='null'?'True':'False'):('False');
	$user_data['p_funcion_grado']		= $user_meta['p_funcion_grado'][0]?'True':'False';
	$p_funcion_grado					= $user_meta['p_funcion_grado'][0];
	$user_data['p_nombre_ct']			= $user_meta['p_nombre_ct'][0]?'True':'False';
	$user_data['p_clave_cct']			= $user_meta['p_clave_cct'][0]?'True':'False';
	$user_data['p_telefono_t']			= 'True';
	$user_data['p_localidad']			= $user_meta['p_localidad'][0]?'True':'False';
	$user_data['p_municipio']			= $user_meta['p_municipio'][0]?'True':'False';
	$user_data['p_zona_escolar']		= $user_meta['p_zona_escolar'][0]?'True':'False';
	$user_data['p_sector_escolar']		= 'True';
	$user_data['p_antiguedad_se']		= $user_meta['p_antiguedad_se'][0]?'True':'False';
	$user_data['p_cantidad_alumnos']	= $user_meta['p_cantidad_alumnos'][0]?'True':'False';

	//Academic data
	$user_data['p_participa_cm']		= $user_meta['p_participa_cm'][0]?($user_meta['p_participa_cm'][0]!='null'?'True':'False'):('False');
	$p_participa_cm						= $user_meta['p_participa_cm'][0];
	$user_data['p_nivel_cm']			= '';
	$user_data['p_vertiente_cm']		= '';
	if ($p_participa_cm == 'Sí')
	{
		$user_data['p_nivel_cm']		= $user_meta['p_nivel_cm'][0]?($user_meta['p_nivel_cm'][0]!='null'?'True':'False'):('False');
		$user_data['p_vertiente_cm']	= $user_meta['p_vertiente_cm'][0]?($user_meta['p_vertiente_cm'][0]!='null'?'True':'False'):('False');
	}
	$user_data['p_licenciatura']		= $user_meta['p_licenciatura'][0]?'True':'False';
	$user_data['p_institucion']			= $user_meta['p_institucion'][0]?'True':'False';
	$user_data['p_grado_estudios']		= 'True';
	$user_data['p_institucion_uge']		= 'True';
	$user_data['p_estudios_actuales']	= 'True';
	$user_data['p_nserie_ipad']			= '';
	if ($user_data['p_fc'])
	{
		$user_data['p_nserie_ipad']			= $user_meta['p_nserie_ipad'][0]?'True':'False';
	}

	//Principals data
	
	$user_data['p_organizacion']		= '';
	$user_data['p_ngrupos_participantes']='';
	$user_data['p_ct_gra_1']			= '';
	$user_data['p_ct_gru_1']			= '';
	$user_data['p_ct_gra_2']			= '';
	$user_data['p_ct_gru_2']			= '';
	$user_data['p_ct_gra_3']			= '';
	$user_data['p_ct_gru_3']			= '';
	$user_data['p_ct_gra_4']			= '';
	$user_data['p_ct_gru_4']			= '';
	$user_data['p_ct_nserie_ipads']		= '';
	if ($p_funcion_grado)
	{
		if (in_array('Dirección',unserialize($p_funcion_grado)))
		{
			$user_data['p_nserie_ipad']		= 'True';
			$user_data['p_organizacion']	= $user_meta['p_organizacion'][0]?'True':'False';
			$user_data['p_ngrupos_participantes']=$user_meta['p_ngrupos_participantes'][0]?($user_meta['p_ngrupos_participantes'][0]!='null'?'True':'False'):('False');
			$p_ngrupos_participantes		= $user_meta['p_ngrupos_participantes'][0];
			if ($p_ngrupos_participantes == 1)
			{
				$user_data['p_ct_gra_1']		= $user_meta['p_ct_gra_1'][0]?($user_meta['p_ct_gra_1'][0]!='null'?'True':'False'):('False');
				$user_data['p_ct_gru_1']		= $user_meta['p_ct_gru_1'][0]?($user_meta['p_ct_gru_1'][0]!='null'?'True':'False'):('False');
			}
			else if ($p_ngrupos_participantes == 2)
			{
				$user_data['p_ct_gra_1']		= $user_meta['p_ct_gra_1'][0]?($user_meta['p_ct_gra_1'][0]!='null'?'True':'False'):('False');
				$user_data['p_ct_gru_1']		= $user_meta['p_ct_gru_1'][0]?($user_meta['p_ct_gru_1'][0]!='null'?'True':'False'):('False');
				$user_data['p_ct_gra_2']		= $user_meta['p_ct_gra_2'][0]?($user_meta['p_ct_gra_2'][0]!='null'?'True':'False'):('False');
				$user_data['p_ct_gru_2']		= $user_meta['p_ct_gru_2'][0]?($user_meta['p_ct_gru_2'][0]!='null'?'True':'False'):('False');
			}
			else if ($p_ngrupos_participantes == 3)
			{
				$user_data['p_ct_gra_1']		= $user_meta['p_ct_gra_1'][0]?($user_meta['p_ct_gra_1'][0]!='null'?'True':'False'):('False');
				$user_data['p_ct_gru_1']		= $user_meta['p_ct_gru_1'][0]?($user_meta['p_ct_gru_1'][0]!='null'?'True':'False'):('False');
				$user_data['p_ct_gra_2']		= $user_meta['p_ct_gra_2'][0]?($user_meta['p_ct_gra_2'][0]!='null'?'True':'False'):('False');
				$user_data['p_ct_gru_2']		= $user_meta['p_ct_gru_2'][0]?($user_meta['p_ct_gru_2'][0]!='null'?'True':'False'):('False');
				$user_data['p_ct_gra_3']		= $user_meta['p_ct_gra_3'][0]?($user_meta['p_ct_gra_3'][0]!='null'?'True':'False'):('False');
				$user_data['p_ct_gru_3']		= $user_meta['p_ct_gru_3'][0]?($user_meta['p_ct_gru_3'][0]!='null'?'True':'False'):('False');
			}
			else if ($p_ngrupos_participantes == 4)
			{
				$user_data['p_ct_gra_1']		= $user_meta['p_ct_gra_1'][0]?($user_meta['p_ct_gra_1'][0]!='null'?'True':'False'):('False');
				$user_data['p_ct_gru_1']		= $user_meta['p_ct_gru_1'][0]?($user_meta['p_ct_gru_1'][0]!='null'?'True':'False'):('False');
				$user_data['p_ct_gra_2']		= $user_meta['p_ct_gra_2'][0]?($user_meta['p_ct_gra_2'][0]!='null'?'True':'False'):('False');
				$user_data['p_ct_gru_2']		= $user_meta['p_ct_gru_2'][0]?($user_meta['p_ct_gru_2'][0]!='null'?'True':'False'):('False');
				$user_data['p_ct_gra_3']		= $user_meta['p_ct_gra_3'][0]?($user_meta['p_ct_gra_3'][0]!='null'?'True':'False'):('False');
				$user_data['p_ct_gru_3']		= $user_meta['p_ct_gru_3'][0]?($user_meta['p_ct_gru_3'][0]!='null'?'True':'False'):('False');
				$user_data['p_ct_gra_4']		= $user_meta['p_ct_gra_4'][0]?($user_meta['p_ct_gra_4'][0]!='null'?'True':'False'):('False');
				$user_data['p_ct_gru_4']		= $user_meta['p_ct_gru_4'][0]?($user_meta['p_ct_gru_4'][0]!='null'?'True':'False'):('False');
			}
			$user_data['p_ct_nserie_ipads']	= 'True';
			$ipads = get_field('p_ct_nserie_ipads', 'user_' . $user_ID);
			if ($ipads)
			{
				$user_data['p_ct_nserie_ipads'] = 'True';
			}
		}
	}
	
	//return $user_data;
	$allow_form_validation = in_array('False',$user_data)?FALSE:TRUE;
	//$allow_form_validation = $user_data;

	return $allow_form_validation;
}

//IMPRIMIR PDF
function planeacion_pdf_info($id_planeacion) {
	$user_ID	= get_current_user_id();
	$user_meta	= get_user_meta( $user_ID );
	$user_data	= array();
	//$pdf_data['user_data'] = $user_meta;
	
	/*Basic post info*/
	//$pdf_data['post_info'] = get_post( $id_planeacion );

	/*PDF Info*/
	$args = array(
			'p' => $id_planeacion,
		);
		
	$the_query = new WP_Query( $args );
	// The Loop
	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
		// Do Stuff
			$status = 'Sin publicar';
			if (get_field('pl_publicar_p') == 'Sí') {
				$status = 'Publicada';
			}
			
			//Suffix
			if (get_field('pl_nivel') == 'Primaria') {
				$pri_sec = 'pri';
			} else if (get_field('pl_nivel') == 'Secundaria') {
				$pri_sec = 'sec';
			} else {
				$pri_sec = '';
			}
			
			//Constants
			$count = 0;
			$pl_propuesta = array(
								0 => FALSE,
								1 => FALSE,
								2 => FALSE,
								3 => FALSE,
								4 => FALSE,
								5 => FALSE,
								6 => FALSE,
							);
			
			//Pre process p1
			$autor = $user_meta['p_nombre'][0] . ' ' . $user_meta['p_apellido_p'][0] . ' ' . $user_meta['p_apellido_m'][0];
			$pdf_data['p1'] = array(
					'ID'					=> get_the_ID(),
					'pl_autor'				=> $autor,
					'pl_nivel'				=> get_field('pl_nivel'),
					'pl_asignatura'			=> get_field('pl_asignatura'),
					'pl_grado_'.$pri_sec	=> get_field('pl_grado_'.$pri_sec),
					'pl_bloque'				=> get_field('pl_bloque'),
					'pl_componente'			=> get_field('pl_componente'),
					'pl_ambito'				=> get_field('pl_ambito'),
					'pl_psl'				=> get_field('pl_psl'),
					'pl_tipo_texto'			=> get_field('pl_tipo_texto'),
					'pl_aprendizaje_esp'	=> get_field('pl_aprendizaje_esp'),
					'pl_tema_ref'			=> get_field('pl_tema_ref'),
					'pl_ppdp'				=> get_field('pl_ppdp'),
					'pl_eje'				=> get_field('pl_eje'),
					'pl_tema'				=> get_field('pl_tema'),
					'pl_contenido'			=> get_field('pl_contenido'),
					'pl_campo_f'			=> get_field('pl_campo_f'),
					'pl_tipo_sa'			=> get_field('pl_tipo_sa'),
					'pl_titulo_sa'			=> get_field('pl_titulo_sa'),
				);
				
				//Pre process p2
				$pl_conocimientos_pr = get_field('pl_conocimientos_pr');
				if ($pl_conocimientos_pr) {
					foreach ($pl_conocimientos_pr as $item) {
						$item_r[$count] = $item[pl_cpr_conocimiento];
						$count++;
					}
					$count = 0;
					$pl_conocimientos_pr = $item_r;
					$item_r = FALSE;
					$item = FALSE;
				}
				
				$pl_otras_ar = get_field('pl_otras_ar');
				if ($pl_otras_ar) {
					foreach ($pl_otras_ar as $item) {
						$item_r[$count] = $item[pl_oar_asignatura];
						$count++;
					}
					$count = 0;
					$pl_otras_ar = $item_r;
					$item_r = FALSE;
					$item = FALSE;
				}
				
				$pdf_data['p2'] = array(
					'pl_topico_g'			=> get_field('pl_topico_g'),
					'pl_conocimientos_pr'	=> $pl_conocimientos_pr,
					'pl_otras_ar'			=> $pl_otras_ar,
					'pl_porque_ia'			=> get_field('pl_porque_ia'),
				);
				
				$pdf_data['p3'] = array(
					'pl_propuesta_1' => get_field('pl_propuesta_1'),
					'pl_propuesta_2' => get_field('pl_propuesta_2'),
					'pl_propuesta_3' => get_field('pl_propuesta_3'),
					'pl_propuesta_4' => get_field('pl_propuesta_4'),
					'pl_propuesta_5' => get_field('pl_propuesta_5'),
					'pl_propuesta_6' => get_field('pl_propuesta_6'),
				);
				
				$pdf_data['p4'] = array(
					'pl_1_1' => get_field('pl_1_1'),
					'pl_1_2' => get_field('pl_1_2'),
					'pl_1_3' => get_field('pl_1_3'),
					'pl_1_4' => get_field('pl_1_4'),
					'pl_1_5' => get_field('pl_1_5'),
					'pl_1_6' => get_field('pl_1_6'),
					'pl_1_7' => get_field('pl_1_7'),
					'pl_1_8' => get_field('pl_1_8'),
					'pl_1_9' => get_field('pl_1_9'),
					'pl_2_1' => get_field('pl_2_1'),
					'pl_2_2' => get_field('pl_2_2'),
					'pl_2_3' => get_field('pl_2_3'),
					'pl_2_4' => get_field('pl_2_4'),
					'pl_2_5' => get_field('pl_2_5'),
					'pl_2_6' => get_field('pl_2_6'),
					'pl_2_7' => get_field('pl_2_7'),
					'pl_2_8' => get_field('pl_2_8'),
					'pl_2_9' => get_field('pl_2_9'),
					'pl_3_1' => get_field('pl_3_1'),
					'pl_3_2' => get_field('pl_3_2'),
					'pl_3_3' => get_field('pl_3_3'),
					'pl_3_4' => get_field('pl_3_4'),
					'pl_3_5' => get_field('pl_3_5'),
					'pl_3_6' => get_field('pl_3_6'),
					'pl_3_7' => get_field('pl_3_7'),
					'pl_3_8' => get_field('pl_3_8'),
					'pl_3_9' => get_field('pl_3_9'),
					'pl_4_1' => get_field('pl_4_1'),
					'pl_4_2' => get_field('pl_4_2'),
					'pl_4_3' => get_field('pl_4_3'),
					'pl_4_4' => get_field('pl_4_4'),
					'pl_4_5' => get_field('pl_4_5'),
					'pl_4_6' => get_field('pl_4_6'),
					'pl_4_7' => get_field('pl_4_7'),
					'pl_4_8' => get_field('pl_4_8'),
					'pl_4_9' => get_field('pl_4_9'),
					'pl_5_1' => get_field('pl_5_1'),
					'pl_5_2' => get_field('pl_5_2'),
					'pl_5_3' => get_field('pl_5_3'),
					'pl_5_4' => get_field('pl_5_4'),
					'pl_5_5' => get_field('pl_5_5'),
					'pl_5_6' => get_field('pl_5_6'),
					'pl_5_7' => get_field('pl_5_7'),
					'pl_5_8' => get_field('pl_5_8'),
					'pl_5_9' => get_field('pl_5_9'),
					'pl_6_1' => get_field('pl_6_1'),
					'pl_6_2' => get_field('pl_6_2'),
					'pl_6_3' => get_field('pl_6_3'),
					'pl_6_4' => get_field('pl_6_4'),
					'pl_6_5' => get_field('pl_6_5'),
					'pl_6_6' => get_field('pl_6_6'),
					'pl_6_7' => get_field('pl_6_7'),
					'pl_6_8' => get_field('pl_6_8'),
					'pl_6_9' => get_field('pl_6_9'),
					'pl_7_1' => get_field('pl_7_1'),
					'pl_7_2' => get_field('pl_7_2'),
					'pl_7_3' => get_field('pl_7_3'),
					'pl_7_4' => get_field('pl_7_4'),
					'pl_7_5' => get_field('pl_7_5'),
					'pl_7_6' => get_field('pl_7_6'),
					'pl_7_7' => get_field('pl_7_7'),
					'pl_7_8' => get_field('pl_7_8'),
					'pl_7_9' => get_field('pl_7_9'),
					'pl_8_1' => get_field('pl_8_1'),
					'pl_8_2' => get_field('pl_8_2'),
					'pl_8_3' => get_field('pl_8_3'),
					'pl_8_4' => get_field('pl_8_4'),
					'pl_8_5' => get_field('pl_8_5'),
					'pl_8_6' => get_field('pl_8_6'),
					'pl_8_7' => get_field('pl_8_7'),
					'pl_8_8' => get_field('pl_8_8'),
					'pl_8_9' => get_field('pl_8_9'),
					'pl_9_1' => get_field('pl_9_1'),
					'pl_9_2' => get_field('pl_9_2'),
					'pl_9_3' => get_field('pl_9_3'),
					'pl_9_4' => get_field('pl_9_4'),
					'pl_9_5' => get_field('pl_9_5'),
					'pl_9_6' => get_field('pl_9_6'),
					'pl_9_7' => get_field('pl_9_7'),
					'pl_9_8' => get_field('pl_9_8'),
					'pl_9_9' => get_field('pl_9_9'),
					'pl_10_1' => get_field('pl_10_1'),
					'pl_10_2' => get_field('pl_10_2'),
					'pl_10_3' => get_field('pl_10_3'),
					'pl_10_4' => get_field('pl_10_4'),
					'pl_10_5' => get_field('pl_10_5'),
					'pl_10_6' => get_field('pl_10_6'),
					'pl_10_7' => get_field('pl_10_7'),
					'pl_10_8' => get_field('pl_10_8'),
					'pl_10_9' => get_field('pl_10_9'),
					'pl_11_1' => get_field('pl_11_1'),
					'pl_11_2' => get_field('pl_11_2'),
					'pl_11_3' => get_field('pl_11_3'),
					'pl_11_4' => get_field('pl_11_4'),
					'pl_11_5' => get_field('pl_11_5'),
					'pl_11_6' => get_field('pl_11_6'),
					'pl_11_7' => get_field('pl_11_7'),
					'pl_11_8' => get_field('pl_11_8'),
					'pl_11_9' => get_field('pl_11_9'),
					'pl_12_1' => get_field('pl_12_1'),
					'pl_12_2' => get_field('pl_12_2'),
					'pl_12_3' => get_field('pl_12_3'),
					'pl_12_4' => get_field('pl_12_4'),
					'pl_12_5' => get_field('pl_12_5'),
					'pl_12_6' => get_field('pl_12_6'),
					'pl_12_7' => get_field('pl_12_7'),
					'pl_12_8' => get_field('pl_12_8'),
					'pl_12_9' => get_field('pl_12_9'),
					'pl_13_1' => get_field('pl_13_1'),
					'pl_13_2' => get_field('pl_13_2'),
					'pl_13_3' => get_field('pl_13_3'),
					'pl_13_4' => get_field('pl_13_4'),
					'pl_13_5' => get_field('pl_13_5'),
					'pl_13_6' => get_field('pl_13_6'),
					'pl_13_7' => get_field('pl_13_7'),
					'pl_13_8' => get_field('pl_13_8'),
					'pl_13_9' => get_field('pl_13_9'),
				);
				
				$pdf_data['p5'] = array(
					'pl_consideracion_1' => get_field('pl_consideracion_1'),
					'pl_consideracion_2' => get_field('pl_consideracion_2'),
					'pl_consideracion_3' => get_field('pl_consideracion_3'),
					'pl_consideracion_4' => get_field('pl_consideracion_4'),
					'pl_consideracion_5' => get_field('pl_consideracion_5'),
					'pl_consideracion_6' => get_field('pl_consideracion_6'),
				);
				
				//Preprocess p6
				$pl_recursos_tic = get_field('pl_recursos_tic');
				if ($pl_recursos_tic) {
					foreach ($pl_recursos_tic as $item) {
							$item_r[$count][pl_recurso_tic] = $item[pl_recurso_tic];
							$pl_propuesta_c = $pl_propuesta;
							if ($item[pl_propuesta]) {
								foreach ($item[pl_propuesta] as $row2) {
									$pl_propuesta_c[$row2] = TRUE;
								}
								$item_r[$count][pl_propuesta] = $pl_propuesta_c;
							}
							$item_r[$count][pl_uso] = $item[pl_uso];
						$count++;
					}
					$count = 0;
					$pl_recursos_tic = $item_r;
					$item_r = FALSE;
					$item = FALSE;
				}
				
				$pdf_data['p6'] = array(
					'pl_recursos_tic' => $pl_recursos_tic,
				);
				
				$pdf_data['p7'] = array(
					'pl_secuencia_aplicacion' => get_field('pl_secuencia_aplicacion'),
				);
		endwhile;
	endif;	

	return $pdf_data;
}

//UPLOAD CAPABILITY
function add_theme_caps() {
    // gets the author role
    $role = get_role( 'subscriber' );

    // would allow subscriber to upload files
	$role->add_cap( 'upload_files' );
	$role->add_cap( 'publish_posts' );
	$role->add_cap( 'delete_posts' );
	$role->add_cap( 'delete_published_posts' );
	//$role->add_cap( 'delete_others_posts' );
}
add_action( 'admin_init', 'add_theme_caps');

function wpse85351_media_strings($strings) {
    // only for subscribers:
    if(!current_user_can('edit_posts')){
        // remove "mediaLibraryTitle"
        unset($strings["mediaLibraryTitle"]);
    }
    return $strings;
}
add_filter('media_view_strings','wpse85351_media_strings');

function my_acf_pre_save_post( $post_id )
{
    // check if this is to be a new post
    if( $post_id != 'new' )
    {
		$_POST['return'] = add_query_arg( array('id_planeacion' => $post_id), $_POST['return'] );
        return $post_id;
    }

	//Get user level or educational service
	$user_ID	= get_current_user_id();
	$user_meta	= get_user_meta( $user_ID );
	$level = $user_meta['p_nivel_se'][0];
	$autor = $user_meta['p_nombre'][0] . ' ' . $user_meta['p_apellido_p'][0] . ' ' . $user_meta['p_apellido_m'][0] . ' ';
	
	if ( current_user_can( 'manage_options' ) ) {
		$cats = array(5);
	} else {
		switch ($level) {
			case 'Preescolar' :
				$cats = array(5,6);
				break;
			case 'Primaria' ;
				$cats = array(5,7);
				break;
			case 'Secundaria' ;
				$cats = array(5,8);
				break;
			default :
				$cats = array(5);
		}
	}

    // Create a new post
    $post = array(
        'post_status'	=> 'publish' ,
        'post_title'	=> 'Planeación - ' . $autor ,
        'post_type'		=> 'post' ,
		'post_category'	=> $cats,
    );  

    // insert the post
    $post_id = wp_insert_post( $post ); 

    // update $_POST['return']
    $_POST['return'] = get_inicio_url();
	//$_POST['return'] = add_query_arg( array('post_id' => $post_id), $_POST['return'] );

    // return the new ID
    return $post_id;
}
add_filter('acf/pre_save_post' , 'my_acf_pre_save_post' );

function my_acf_load_field( $field )
{
	//Get user level or educational service
	$user_ID	= get_current_user_id();
	$user_meta	= get_user_meta( $user_ID );
	$level = $user_meta['p_nivel_se'][0];
	if ( current_user_can( 'manage_options' ) ) {
		return $field;
	} else {
		switch ($level) {
			case 'Preescolar' :
				$field['choices'] = array(
					'Preescolar' => 'Preescolar'
				);
				break;
			case 'Primaria' ;
				$field['choices'] = array(
					'Primaria' => 'Primaria'
				);
				break;
			case 'Secundaria' ;
				$field['choices'] = array(
					'Secundaria' => 'Secundaria'
				);
				break;
			case 'Educación especial' ;
				$field['choices'] = array(
					'Secundaria' => 'Educación especial'
				);
				break;
			default :
				$field['choices'] = $field['choices'];
		}
	
		return $field;
	}

}
add_filter('acf/load_field/name=pl_nivel', 'my_acf_load_field'); // v4.0.0 and above

function my_acf_load_field_pl_autor( $field )
{
	//Get user level or educational service
	$user_ID	= get_current_user_id();
	$user_meta	= get_user_meta( $user_ID );
	$autor = $user_meta['p_nombre'][0] . ' ' . $user_meta['p_apellido_p'][0] . ' ' . $user_meta['p_apellido_m'][0] . ' ';

	if ( current_user_can( 'manage_options' ) ) {
		return $field;
	} else {
		$field['choices'] = array(
			$user_ID => $autor
		);	
		return $field;
	}
}
add_filter('acf/load_field/name=pl_autor', 'my_acf_load_field_pl_autor'); // v4.0.0 and above

function query_user_plans() {
	//Get user level or educational service
	$user_ID	= get_current_user_id();
	$user_meta	= get_user_meta( $user_ID );
	$author = $user_meta['p_nombre'][0] . ' ' . $user_meta['p_apellido_p'][0] . ' ' . $user_meta['p_apellido_m'][0] . ' ';
	$level = $user_meta['p_nivel_se'][0];

	switch ($level) {
		case 'Preescolar' :
			$cat = 6;
			$grado = '';
			break;
		case 'Primaria' ;
			$cat = 7;
			$grado = 'pri';
			break;
		case 'Secundaria' ;
			$cat = 8;
			$grado = 'sec';
			break;
		default :
			$cat = '';
	}

	$args = array(
			'author' => $user_ID,
			'cat' => $cat,
		);
		
	$the_query = new WP_Query( $args );
	// The Loop
	if ( $the_query->have_posts() ) :
	while ( $the_query->have_posts() ) : $the_query->the_post();
	// Do Stuff
		$status = 'Sin publicar';
		if (get_field('pl_publicar_p') == 'Sí') {
			$status = 'Publicada';
		}
		
		$titles[get_the_ID()] = array(
				'ID' => get_the_ID(),
				'autor' => $author,
				'nivel' => $level,
				'asignatura' => get_field('pl_asignatura'),
				'grado' => get_field('pl_grado_'.$grado),
				'bloque' => get_field('pl_bloque'),
				'aprendizaje_esp' => get_field('pl_aprendizaje_esp'),
				'url_editar' => get_inicio_url() . '/editar-planeacion/?id_planeacion='.get_the_ID(),
				'url_publicar' => get_inicio_url() . '/publicar-planeacion/?id_planeacion='.get_the_ID(),
				'url_imp_pdf' => get_inicio_url() . '/imprimir-planeacion/?id_planeacion='.get_the_ID(),
				'estado' => $status,
			);
	endwhile;
	endif; 
	return $titles;
}

//GET LESSON PLANS
function get_plans($inargs = array()) {
	$result = FALSE;
	$args = array(
			'posts_per_page' => 10000,
			'nopaging' => true,
		);
		
	foreach ($inargs as $key => $value) {
		$args[$key] =  $value;
	}
		
	$the_query = new WP_Query( $args );
	// The Loop
	if ( $the_query->have_posts() ) :
	while ( $the_query->have_posts() ) : $the_query->the_post();
		if (get_field('pl_publicar_p') == 'Sí') {
		$result[get_the_ID()] = array(
				'id' => get_the_ID(),
				'url' => get_the_permalink(),
				'title' => get_the_title(),
				'nivel' => get_field('pl_nivel'),		
			);
		}
	endwhile;
	endif;
	// Reset Post Data
	wp_reset_postdata();

	return $result;
}


/* function add_rewrite_rules($aRules) {
    $aNewRules = array('editar-planeacion/([^/]+)/?$' => 'index.php?pagename=editar-planeacion&id_planeacion=$matches[1]');
    $aRules = $aNewRules + $aRules;
    return $aRules;
}
add_filter('rewrite_rules_array', 'add_rewrite_rules'); */