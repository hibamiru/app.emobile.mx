<?php

/*
  Plugin Name: Alta participante
  Plugin URI: http://solucioneshipermedia.com
  Description: Formulario de registro de participantes
  Version: 1.0
  Author: Hibam Iru Dionisio Alor
  Author URI: http://solucioneshipermedia.com
 */

function custom_registration_function() {
	global $display_form;
    $display_form 		= 	TRUE;
    if (isset($_POST['submit'])) {
        registration_validation(
			$_POST['email']
			);
		
		// sanitize user form input
        global $password, $cpassword, $email;
        $username	= 	sanitize_user($_POST['email']);
        $email 		= 	sanitize_email($_POST['email']);

		// call @function complete_registration to create the user
		// only when no WP_error is found
        complete_registration(
			$username,
			$email
			);
    }
	
	registration_form(
		$email,
		$display_form
		);
}

function registration_form( $email ) {
	global $display_form;
	if ($display_form) {
		echo '
		<style>
		div {
			margin-bottom:2px;
		}
		
		input{
			margin-bottom:4px;
		}
		</style>
		';
	
		echo '
		<p>Escribe tu dirección de correo electrónico y presiona el botón para iniciar tu proceso de registro.</p>
		<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
		
		<div>
		<label for="email">Email <strong>*</strong></label>
		<input type="text" name="email" value="' . (isset($_POST['email']) ? $email : null) . '">
		</div>
	
		<input type="submit" name="submit" value="Darse de alta"/>
		</form>
		';
	}
}

function registration_validation( $email )  {
    global $reg_errors;
    $reg_errors = new WP_Error;

    if ( empty( $email ) ) {
        $reg_errors->add('field', 'Te faltó llenar un campo obligatorio');
    }

    if ( !is_email( $email ) ) {
        $reg_errors->add('email_invalid', 'El email no es válido');
    }

    if ( email_exists( $email ) ) {
        $reg_errors->add('email', 'Este email ya existe en nuestro sistema.');
    }

    if ( is_wp_error( $reg_errors ) ) {

        foreach ( $reg_errors->get_error_messages() as $error ) {
            echo '<div>';
            echo '<strong>ERROR</strong>:';
            echo $error . '<br/>';

            echo '</div>';
        }
    }
}

function complete_registration() {
    global $reg_errors, $email, $display_form;
    if ( count($reg_errors->get_error_messages()) < 1 ) {
		$ramdom_pass = rand(100000, 999999);
		$password = $ramdom_pass;
        $userdata = array(
			'user_login'	=> 	$email,
			'user_email' 	=> 	$email,
			'user_pass' 	=> 	$password,
		);
		$headers = 'From: Educacuón Mobile Veracruz <servicio.de.correo@solucioneshipermedia.com>' . "\r\n";
		$headers = 'Content-type: text/html' . "\r\n";
		$to = $email;
		$subject = 'Acceso a plataforma eMobile';
		$message = '<strong>Nombre de usuario:</strong> ' . $email . '<br />';
		$message .= '<strong>Contraseña:</strong> ' . $password . '<br />';
		$message .= '<strong>Link para inicio se desión:</strong> <a href="' . get_site_url() . '"> emobile.mx </a><br />';
        $message .= '<p>Guarda esta información pues será necesaria para acceder a la plataforma eMobile</p>';
        $user = wp_insert_user( $userdata );
		wp_mail( $to, $subject, $message, $headers );
        echo '¡Gracias! Debemos verificar tu email. Te hemos enviado un email a tu dirección ' . $email . ' con la contraseña para iniciar sesión en emobile y completar tu registro. <br><strong>Ingresa a tu correo para continuar</strong>';   
        $display_form 		= 	FALSE;
	}
}

// Register a new shortcode: [cr_custom_registration]
add_shortcode('alta_participante', 'custom_registration_shortcode');

// The callback function that will register new user
function custom_registration_shortcode() {
    ob_start();
    custom_registration_function();
    return ob_get_clean();
}
