<?php
/**
 * Template Name: Imprimir PDF
 * Description: Esta página genera un pdf con los datos del participante.
 *
 * @package WordPress
 * @subpackage EMobile
 * @since EMobile 1.0
 */
user_redirect();
?>

<?php while ( have_posts() ) : the_post(); ?>

<?php $participante = participant_c_data(); ?>
<?php //echo '<pre style="display:block;">'; print_r($pdf); echo '</pre>'; // PRINT_R ?>
<?php require(TEMPLATEPATH . '/inc/fpdf/fpdf.php'); ?>
<?php define('FPDF_FONTPATH', TEMPLATEPATH . '/inc/fpdf/font'); //Absolute font path. Si lo defines en el config, podria ser asi: BASEPATH."application/libraries/font/"

//------------------------INICIO VARIABLES--------------------------//
$title = 'Ficha de registro eMobile';

//Fecha de validación
function fechaFormateada2($FechaStamp) {
	$ano = date('Y',$FechaStamp);
	$mes = date('n',$FechaStamp);
	$dia = date('d',$FechaStamp);
	$diasemana = date('w',$FechaStamp);
	 
	$diassemanaN= array("Domingo","Lunes","Martes","Miércoles",
	"Jueves","Viernes","Sábado"); $mesesN=array(1=>"enero","febrero","marzo","abril","mayo","junio","julio",
	"agosto","septiembre","octubre","noviembre","diciembre");
	return $diassemanaN[$diasemana].", $dia de ". $mesesN[$mes] ." de $ano";
}

 $fecha = time();
 $fecha_validacion = FechaFormateada2($fecha);

$nombre_participante = $participante['p_nombre'] . ' ' . $participante['p_apellido_p'] . ' ' . $participante['p_apellido_m'];
// $participante 
foreach ($participante[p_funcion_grado] as &$grado) {                            
    $funciones .= $grado; 
    if (end($participante[p_funcion_grado]) != $grado) { $funciones .= ', '; }  
} 
// $carrera_magisterial
if  ($tus_datos[p_participa_cm] == 'No') { 
	$carrera_magisterial = 'No';
} else {
	$carrera_magisterial = 'Nivel ' . $participante[p_nivel_cm] . ' vertiente ' .  $participante[p_vertiente_cm]; 

} 

//----------------CREO FUNCIONES PARA EL DOCUMENTO----------------//
class PDF extends FPDF {
	// Cabecera de página
	function Header()	{
	    // Logo
	    $this->Image(get_plantilla_url() . '/images/logo.png',10,8,30);
		// Logo 2
	    $this->Image(get_plantilla_url() . '/images/logo-sev.png',165,8,40);
	    // Arial bold 15
	    $this->SetFont('Helvetica','B',15);
	    // Arial bold 15
		$this->SetTextColor(49,40,41);
		
		// Títulos
		$this->Ln(4);
	    $this->Cell(80);
	    $this->Cell(30,7,utf8_decode('Educación Mobile Veracruz'),0,1,'C');
		$this->Ln(4);
		//subtitulo
		$this->SetFont('Helvetica','',14);
		$this->SetTextColor(68,68,68);
		$this->SetDrawColor(197,197,197);
		$this->Cell(0,7,' ','B',0,'C');
		$this->Ln(7);
		$this->Cell(0,7,utf8_decode('FICHA DE REGISTRO DEL PARTICIPANTE'),'B',1,'C');
		$this->Ln(3);
	}

	// Pie de página
	function Footer()	{
	    // Posición: a 1,5 cm del final
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Número de página
	    $this->Cell(0,10,utf8_decode('Página ').$this->PageNo(),0,0,'C');
	}

	function TituloSeccion($num, $label)	{
	    // Fuente
	    $this->SetFont('Helvetica','',15);
	    // Color de fondo
	    $this->SetFillColor(255,255,255);
		// Color de texto
		$this->SetTextColor(186,28,35);
		// Color de linea
		$this->SetDrawColor(197,197,197);
	    // Título
	    $this->Cell(0,6,utf8_decode("Sección $num : $label"),'B',1,'L',true);
	    // Salto de línea
	    $this->Ln(4);
	}

	function SubtituloSeccion($label)	{
	    // Fuente
	    $this->SetFont('Helvetica','',12);
	    // Color de fondo
	    $this->SetFillColor(0,127,62);
		// Color de texto
		$this->SetTextColor(252,252,252);
	    // Título
	    $this->Cell(0,6,$label,0,1,'C',true);
	}
	function Etiqueta($etiqueta) {
		// Arial 12
	    $this->SetFont('Helvetica','',11);
		$this->SetFillColor(224,224,224);
		$this->SetTextColor(34,34,34);
		$this->Cell(50,6,' ' . utf8_decode($etiqueta). ':',0,0,'L',1);
	}
	function EtiquetaLarga($etiqueta) {
		// Arial 12
	   $this->SetFont('Helvetica','',11);
		$this->SetFillColor(204,204,204);
		$this->SetTextColor(34,34,34);
		$this->Cell(0,6,' ' . utf8_decode($etiqueta). ':',0,1,'L',1);
	}
	function Valor($valor) {
		// Arial 12
	    $this->SetFont('Helvetica','',11);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(34,34,34);
		$this->SetDrawColor(239,239,239);
		$this->Cell(0,6,utf8_decode(' ' . $valor),'B',1,'L',1);
	}
	function ValorTexto($valor) {
	    $this->SetFont('Helvetica','',11);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(34,34,34);
		$this->SetDrawColor(239,239,239);
		$this->MultiCell(0,6,utf8_decode(' ' . $valor),'B',1,'L',1);
	}
	function ValorMulti($valor) {
	    $this->SetFont('Helvetica','',11);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(34,34,34);
		$this->SetDrawColor(239,239,239);
		$this->Cell(0,6,'        - ' . utf8_decode($valor),'B',1,'L',1);
	}
}

//----------------CREO EL DOCUMENTO----------------//
$pdf = new PDF();

$pdf->SetTitle(utf8_decode($title));
$pdf->SetAuthor('eMobile');
$pdf->AddPage();

$pdf->Ln(2);

// DATOS PERSONAL
//Foto
$pdf->Ln(22);
$pdf->Image($participante['p_foto'],20,43,20);
// Datos generales
$pdf->SubtituloSeccion('Datos personales');
$pdf->Etiqueta('Nombre');
$pdf->Valor($nombre_participante);
$pdf->Etiqueta('CURP');
$pdf->Valor($participante['p_curp']);
$pdf->Etiqueta('RFC');
$pdf->Valor($participante['p_rfc']);
$pdf->Etiqueta('Sexo');
$pdf->Valor($participante['p_sexo']);
$pdf->Etiqueta('Edad');
$pdf->Valor($participante['p_edad']);
$pdf->Etiqueta('Teléfono particular');
$pdf->Valor($participante['p_telefono_p']);
$pdf->Etiqueta('Teléfono celular');
$pdf->Valor($participante['p_telefono_c']);
$pdf->Etiqueta('Email');
$pdf->Valor($participante['p_email']);
$pdf->Etiqueta('Sede de capacitación');
$pdf->Valor($participante['p_sede_c']);
$pdf->Etiqueta('Líder creativo');
$pdf->Valor($participante['p_lider_c']);
$pdf->Etiqueta('ID de iCloud');
$pdf->Valor($participante['p_cuenta_icloud']);
$pdf->Ln(4);

// Datos laborales
$pdf->SubtituloSeccion('Datos laborales');
$pdf->Etiqueta('Función o grado');
$pdf->Valor($funciones);
$pdf->Etiqueta('Nivel o servicio educativo');
$pdf->Valor($participante['p_nivel_se']);
$pdf->Etiqueta('Modalidad');
$pdf->Valor($participante['p_modalidad']);
$pdf->Etiqueta('Sostenimiento');
$pdf->Valor($participante['p_sostenimiento']);
$pdf->Etiqueta('Nombre del CT');
$pdf->Valor($participante['p_nombre_ct']);
$pdf->Etiqueta('Clave del CT');
$pdf->Valor($participante['p_clave_cct']);
$pdf->Etiqueta('Localidad');
$pdf->Valor($participante['p_localidad']);
$pdf->Etiqueta('Municipio');
$pdf->Valor($participante['p_municipio']);
$pdf->Etiqueta('Zona escolar');
$pdf->Valor($participante['p_zona_escolar']);
$pdf->Etiqueta('Sector escolar');
$pdf->Valor($participante['p_sector_escolar']);
$pdf->Etiqueta('Antiguedad en el servicio');
$pdf->Valor($participante['p_antiguedad_se'] . ' años');
$pdf->Etiqueta('Alumnos en su grupo');
$pdf->Valor($participante['p_cantidad_alumnos']);
$pdf->Etiqueta('Teléfono del trabajo');
$pdf->Valor($participante['p_telefono_t']);
$pdf->Ln(4);

// Información profesional
$pdf->SubtituloSeccion(utf8_decode('Información profesional'));
$pdf->Etiqueta('Carrera magisterial');
$pdf->Valor($carrera_magisterial);
$pdf->Etiqueta('Carrera');
$pdf->Valor($participante['p_licenciatura']);
$pdf->Etiqueta('Institución donde cursó');
$pdf->Valor($participante['p_institucion']);
$pdf->Etiqueta('Grado máximo de estudios');
$pdf->Valor($participante['p_grado_estudios']);
$pdf->Etiqueta('Institución último grado');
$pdf->Valor($participante['p_institucion_uge']);
$pdf->Etiqueta('Estudios actuales');
$pdf->Valor($participante['p_estudios_actuales']);

$pdf->AddPage();
$pdf->Ln(14);

// iPad
$pdf->Etiqueta('Número de serie de iPad');
$pdf->Valor($participante['p_nserie_ipad']);
$pdf->Ln(14);

//$pdf->Cell(10);
//$pdf->MultiCell(170,6,utf8_decode(''),'',1,'L',1);
//$pdf->Cell(0);
//$pdf->Ln(28);

$pdf->SetFont('Helvetica','B',12);
$pdf->Cell(0,6,utf8_decode('Certifico que los datos aquí expuestos son verídicos.'),'',1,'C');
$pdf->Ln(8);
$pdf->SetFont('Helvetica','',12);
$pdf->Cell(0,6,utf8_decode($fecha_validacion),'',1,'C');
$pdf->Ln(8);
$pdf->Cell(0,6,'___________________________________','',1,'C');
$pdf->Cell(0,6, utf8_decode($nombre_participante),'',1,'C');


// ley de protección
$pdf->Ln(20);
$pdf->SetFont('Helvetica','',8);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(107,107,107);
$pdf->SetDrawColor(239,239,239);
$pdf->MultiCell(0,6,utf8_decode('Los datos personales que nos proporcione serán protegidos conforme a lo dispuesto por la Ley Federal de Transparencia y Acceso a la Información Pública Gubernamental, los cuales serán incorporados y tratados en el sistema de datos personales denominado REGISTRO DE ORIENTACIÓN A LOS PARTICULARES. Dicho sistema fue registrado en el listado de sistemas de datos personales que administra el Instituto Federal de Acceso a la Información y Protección de Datos (www.ifai.mx).'),'B',1,'L',1);




$pdf->Output();
?>

<?php endwhile; // end of the loop. ?>

<?php //get_footer(); ?>