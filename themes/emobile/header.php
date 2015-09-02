<?php
/**
 * El encabezado del tema
 * Despliega toda la sección de <head> hasta <div id="main">
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'emobile' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<!-- Bootstrap stylesheet -->
<link href="<?php plantilla_url(); ?>/css/bootstrap.min.css" rel="stylesheet">
<!-- CSS main stylesheet -->
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<!-- Bootstrap -->
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!-- Font Awesome -->
<link href="<?php plantilla_url(); ?>/inc/font-awesome/css/font-awesome.css" rel="stylesheet">
<!-- Google Web Fonts -->
<link href='http://fonts.googleapis.com/css?family=Dosis:400,300,600&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<!-- Favicon -->
<link href=" <?php echo get_plantilla_url().'/images/favicon.png'; ?>" rel="icon" type="image/x-icon" />
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<!-- Google Analytics -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-56635227-3', 'auto');
  ga('send', 'pageview');

</script>

  <header  id="header" class="navbar navbar-default navbar-fixed-top">        
    <nav class="container" role="navigation">

          <a class="navbar-brand text-muted" href="<?php bloginfo( 'url' ); ?>/escritorio/">
            <img src="<?php echo get_plantilla_url().'/images/logo.png'; ?>" alt="<?php bloginfo('name'); ?>">
          </a>
      
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            Menu de navegación
          </button>

        <div class="collapse navbar-collapse">
          <?php 
            if ( is_home() ) {
                $args = array('menu' => 'Login', 'menu_class'  => 'nav  nav-pills pull-right', 'container'   => 'false');
                                wp_nav_menu( $args );
            } else {
                 $args = array('menu' => 'Participante', 'menu_class'  => 'nav  nav-pills pull-right', 'container'   => 'false');
                                wp_nav_menu( $args );
            }
            


          ?>
        </div><!--/.navbar-collapse -->
    </nav>
  </header>
<div id="page"> 
  <div id="main" class="group container">