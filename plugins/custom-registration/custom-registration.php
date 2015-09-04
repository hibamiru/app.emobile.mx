<?php

/*
  Plugin Name: Custom Registration
  Plugin URI: http://inovanto.com
  Description: Creates a registration form with custom user meta. Use the [in_custom_registration] shortcode to display the form.
  Version: 1.0
  Author: hibamiru
  Author URI: http://inovanto.com
 */

//FRONT END FORM
function in_custom_registration_function() {
    if (isset($_POST['submit'])) {
        in_registration_validation(
	        $_POST['fname'],
	        $_POST['email'],
	        $_POST['password'],
	        $_POST['ciudad'],
	        $_POST['estado'],
	        $_POST['tipo_usuario'],
	        $_POST['nivel_educativo']
			);
		
		// sanitize user form input
        global $fname, $email, $password, $ciudad, $estado, $tipo_usuario, $nivel_educativo;
        $fname 				= 	sanitize_text_field($_POST['fname']);
        $email 				= 	sanitize_email($_POST['email']);
        $password 			= 	esc_attr($_POST['password']);
        $ciudad 			= 	sanitize_text_field($_POST['ciudad']);
        $estado 			= 	sanitize_text_field($_POST['estado']);
        $tipo_usuario 		= 	sanitize_text_field($_POST['tipo_usuario']);
        $nivel_educativo	= 	sanitize_text_field($_POST['nivel_educativo']);
        //print_array($_POST);

        //echo 'Bu!'. $_POST['estado']; die;
		// call @function complete_registration to create the user
		// only when no WP_error is found
        in_complete_registration(
	        $fname,
	        $email,
	        $password,
	        $ciudad,
	        $estado,
	        $tipo_usuario,
	        $nivel_educativo
			);
    }

    in_registration_form(
        $fname,
        $email,
        $password,
        $ciudad,
        $estado,
        $tipo_usuario,
        $nivel_educativo
		);
}

function in_registration_form( $fname, $email, $password, $ciudad, $estado, $tipo_usuario, $nivel_educativo ) {
	?>
    
    <style>
	div {
		margin-bottom:2px;
	}
	
	input{
		margin-bottom:4px;
	}
	</style>
	

    
    <form action="<?php $_SERVER['REQUEST_URI'] ?>" method="post">

		<div>
			<label for="firstname">Nombre completo </label>
			<input type="text" name="fname" value="<?php echo (isset($_POST['fname']) ? $fname : null) ?>">
		</div>

		<div>
			<label for="email">Correo <strong>*</strong></label>
			<input type="text" name="email" value="<?php echo (isset($_POST['email']) ? $email : null) ?>">
		</div>
		
		<div>
		<label for="password">Contraseña <strong>*</strong></label>
		<input type="password" name="password" value="">
		</div>
		
		
		<div>
			<label for="ciudad">Ciudad </label>
			<input type="text" name="ciudad" value="<?php echo (isset($_POST['ciudad']) ? $ciudad : null) ?>">
		</div>
		
		
		<div>
			<label for="estado">Estado </label>
			<select name="estado">
				<?php echo (isset($_POST['estado']) ? '<option selected value="'.$estado.'">'.$estado.'</option>' : '') ?>				
				<option value="">Elegir estado</option>
				<option value="Aguascalientes">Aguascalientes</option>
				<option value="Baja California">Baja California</option>
				<option value="Baja California Sur">Baja California Sur</option>
				<option value="Campeche">Campeche</option>
				<option value="Chiapas">Chiapas</option>
				<option value="Chihuahua">Chihuahua</option>
				<option value="Coahuila">Coahuila</option>
				<option value="Colima">Colima</option>
				<option value="Distrito Federal">Distrito Federal</option>
				<option value="Durango">Durango</option>
				<option value="Estado de México">Estado de México</option>
				<option value="Guanajuato">Guanajuato</option>
				<option value="Guerrero">Guerrero</option>
				<option value="Hidalgo">Hidalgo</option>
				<option value="Jalisco">Jalisco</option>
				<option value="Michoacán">Michoacán</option>
				<option value="Morelos">Morelos</option>
				<option value="Nayarit">Nayarit</option>
				<option value="Nuevo León">Nuevo León</option>
				<option value="Oaxaca">Oaxaca</option>
				<option value="Puebla">Puebla</option>
				<option value="Querétaro">Querétaro</option>
				<option value="Quintana Roo">Quintana Roo</option>
				<option value="San Luis Potosí">San Luis Potosí</option>
				<option value="Sinaloa">Sinaloa</option>
				<option value="Sonora">Sonora</option>
				<option value="Tabasco">Tabasco</option>
				<option value="Tamaulipas">Tamaulipas</option>
				<option value="Tlaxcala">Tlaxcala</option>
				<option value="Veracruz">Veracruz</option>
				<option value="Yucatán">Yucatán</option>
				<option value="Zacatecas">Zacatecas</option>
			</select>
		</div>
		
		<div>
			<label for="tipo_usuario">Tipo de usuario </label>
			<select name="tipo_usuario">
				<?php echo (isset($_POST['tipo_usuario']) ? '<option value="'.$tipo_usuario.'">'.$tipo_usuario.'</option>' : '') ?>
				
				<option value="">Elegir tipo de usuario</option>
				<option value="Docente">Docente</option>
				<option value="Padre de familia">Padre de familia</option>
				<option value="Estudiante">Estudiante</option>
				<option value="Otro">Otro</option>
			</select>
		</div>
		
		<div>
			<label for="nivel_educativo">Nivel educativo </label>
			<select name="nivel_educativo">
				<?php echo (isset($_POST['nivel_educativo']) ? '<option value="'.$nivel_educativo.'">'.$nivel_educativo.'</option>' : '') ?>
				
				<option value="">Elegir nivel educativo de interés</option>
				<option value="Preescolar">Preescolar</option>
				<option value="Primaria">Primaria</option>
				<option value="Secundaria">Secundaria</option>
				
		</div>
		
		<input type="submit" name="submit" value="Registrarse"/>

	</form>

<?php
}

function in_registration_validation( $fname, $email, $password, $ciudad, $estado, $tipo_usuario, $nivel_educativo )  {
    global $reg_errors;
    $reg_errors = new WP_Error;

    if ( empty( $fname ) || empty( $email ) || empty( $password ) || empty( $ciudad ) || empty( $estado ) || empty( $tipo_usuario ) || empty( $nivel_educativo ) ) {
        $reg_errors->add('field', 'Todos los campos son requeridos');
    }

    if ( !is_email( $email ) ) {
        $reg_errors->add('email_invalid', 'El email no es válido');
    }

    if ( strlen( $password ) < 4 ) {
        $reg_errors->add('password', 'La contraseña debe contener al menos 4 caracteres');
    }

    if ( email_exists( $email ) ) {
        $reg_errors->add('email', 'Este email ya se encuentra registrado');
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

function in_complete_registration() {
    global $reg_errors, $fname, $email, $password, $ciudad, $estado, $tipo_usuario, $nivel_educativo;
    if ( count($reg_errors->get_error_messages()) < 1 ) {
        $userdata = array(
        'user_login'	=> 	$email,
        'user_email' 	=> 	$email,
        'user_pass' 	=> 	$password,
        'first_name' 	=> 	$fname,
		);
        $user_id = wp_insert_user( $userdata );
        update_user_meta( $user_id, 'ciudad', $ciudad );
        update_user_meta( $user_id, 'estado', $estado );
        update_user_meta( $user_id, 'tipo_usuario', $tipo_usuario );
        update_user_meta( $user_id, 'nivel_educativo', $nivel_educativo );

        /*$user_login = $password;
        $user = get_userdatabylogin($user_login);
        wp_set_current_user($user_id, $user_login);
        wp_set_auth_cookie($user_id);
        do_action('wp_login', $user_login);*/
		
        echo '¡Te registraste exitosamente! Ya puedes iniciar sesión en el formulario de la derecha.';
	}
}

// Register a new shortcode: [cr_custom_registration]
add_shortcode('in_custom_registration', 'in_custom_registration_shortcode');

// The callback function that will replace [book]
function in_custom_registration_shortcode() {
    ob_start();
    in_custom_registration_function();
    return ob_get_clean();
}



//WORDPRESS USER PROFILE FORM (Modify at your own risk!)
add_action( 'show_user_profile', 'in_custom_registration_form_fields' );
add_action( 'edit_user_profile', 'in_custom_registration_form_fields' );

function in_custom_registration_form_fields( $user )
{
    ?>
        <h3>Datos de usuarios regulares</h3>

        <table class="form-table">
            <tr>
                <th><label for="ciudad">Ciudad</label></th>
                <td><input type="text" name="ciudad" value="<?php echo esc_attr(get_the_author_meta( 'ciudad', $user->ID )); ?>" class="regular-text" /></td>
            </tr>

            <tr>
                <th><label for="estado">Estado</label></th>
                <td>
                	<?php $estado = get_the_author_meta( 'estado', $user->ID ); ?>
					<select name="estado">
						<?php echo (!empty($estado) ? '<option selected value="'.$estado.'">'.$estado.'</option>' : '') ?>				
						<option value="">Elegir estado</option>
						<option value="Aguascalientes">Aguascalientes</option>
						<option value="Baja California">Baja California</option>
						<option value="Baja California Sur">Baja California Sur</option>
						<option value="Campeche">Campeche</option>
						<option value="Chiapas">Chiapas</option>
						<option value="Chihuahua">Chihuahua</option>
						<option value="Coahuila">Coahuila</option>
						<option value="Colima">Colima</option>
						<option value="Distrito Federal">Distrito Federal</option>
						<option value="Durango">Durango</option>
						<option value="Estado de México">Estado de México</option>
						<option value="Guanajuato">Guanajuato</option>
						<option value="Guerrero">Guerrero</option>
						<option value="Hidalgo">Hidalgo</option>
						<option value="Jalisco">Jalisco</option>
						<option value="Michoacán">Michoacán</option>
						<option value="Morelos">Morelos</option>
						<option value="Nayarit">Nayarit</option>
						<option value="Nuevo León">Nuevo León</option>
						<option value="Oaxaca">Oaxaca</option>
						<option value="Puebla">Puebla</option>
						<option value="Querétaro">Querétaro</option>
						<option value="Quintana Roo">Quintana Roo</option>
						<option value="San Luis Potosí">San Luis Potosí</option>
						<option value="Sinaloa">Sinaloa</option>
						<option value="Sonora">Sonora</option>
						<option value="Tabasco">Tabasco</option>
						<option value="Tamaulipas">Tamaulipas</option>
						<option value="Tlaxcala">Tlaxcala</option>
						<option value="Veracruz">Veracruz</option>
						<option value="Yucatán">Yucatán</option>
						<option value="Zacatecas">Zacatecas</option>
					</select>
                </td>
            </tr>

            <tr>
                <th><label for="tipo_usuario">Tipo de usuario</label></th>
                <td>
                	<?php $tipo_usuario = get_the_author_meta( 'tipo_usuario', $user->ID ); ?>
					<select name="tipo_usuario">
						<?php echo (!empty($tipo_usuario) ? '<option selected value="'.$tipo_usuario.'">'.$tipo_usuario.'</option>' : '') ?>				
						<option value="">Elegir tipo de usuario</option>
						<option value="Docente">Docente</option>
						<option value="Padre de familia">Padre de familia</option>
						<option value="Estudiante">Estudiante</option>
						<option value="Otro">Otro</option>
					</select>
                </td>
            </tr>

            <tr>
                <th><label for="nivel_educativo">Elegir nivel educativo</label></th>
                <td>
                	<?php $nivel_educativo = get_the_author_meta( 'nivel_educativo', $user->ID ); ?>
					<select name="nivel_educativo">
						<?php echo (!empty($nivel_educativo) ? '<option selected value="'.$nivel_educativo.'">'.$nivel_educativo.'</option>' : '') ?>				
						<option value="">Elegir nivel educativo</option>
						<option value="Preescolar">Preescolar</option>
						<option value="Primaria">Primaria</option>
						<option value="Secundaria">Secundaria</option>
					</select>
                </td>
            </tr>

        </table>
    <?php
}

add_action( 'personal_options_update', 'in_save_custom_registration_form_fields' );
add_action( 'edit_user_profile_update', 'in_save_custom_registration_form_fields' );

function in_save_custom_registration_form_fields( $user_id )
{
    update_user_meta( $user_id,'ciudad', sanitize_text_field( $_POST['ciudad'] ) );
    update_user_meta( $user_id,'estado', sanitize_text_field( $_POST['estado'] ) );
    update_user_meta( $user_id,'tipo_usuario', sanitize_text_field( $_POST['tipo_usuario'] ) );
    update_user_meta( $user_id,'tipo_usuario', sanitize_text_field( $_POST['tipo_usuario'] ) );
    update_user_meta( $user_id,'nivel_educativo', sanitize_text_field( $_POST['nivel_educativo'] ) );
}