<?php
date_default_timezone_set ( 'America/Guayaquil' );

class UtilitarioM extends CI_Model {

	const ARTICULO =1;
	const COMBO=2;
	const CATEGORIA_COMBO=1;

	const TIPO_PROVEEDOR=1;
	const TIPO_CLIENTE=2;

	const ACTIVO=1;
	const INACTIVO=0;

	/***
	 * metodo que retorna la fecha y hora actual
	 * @return string
	 */
	function fecha_hora_actual() {
		$resultado = date ( 'Y' ) . "-" . date ( 'm' ) . "-" . date ( 'd' ) . " " . date ( 'H' ) . ":" . date ( 'i' );
		return $resultado;
	}
	/***
	 * metodo que retorna la fecha actual
	 * @return string
	 */
	function fecha_actual() {
		$resultado = date ( 'Y' ) . "-" . date ( 'm' ) . "-" . date ( 'd' ) ;
		return $resultado;
	}
	
	/***
	 * Metodo que retorna el nombre del mes dependiendo del numero ingresado
	 * @param  $mes - valor numeroco de mes
	 * @return string
	 */
	function mesNombre($mes)
	{
		$nombre = "";
		
		if ($mes == 1) {
			$nombre = "Enero";
		} else if ($mes == 2) {
			$nombre = "Febrero";
		} else if ($mes == 3) {
			$nombre = "Marzo";
		} else if ($mes == 4) {
			$nombre = "Abril";
		} else if ($mes == 5) {
			$nombre = "Mayo";
		} else if ($mes == 6) {
			$nombre = "Junio";
		} else if ($mes == 7) {
			$nombre = "Julio";
		} else if ($mes == 8) {
			$nombre = "Agosto";
		} else if ($mes == 9) {
			$nombre = "Septiembre";
		} else if ($mes == 10) {
			$nombre = "Octubre";
		} else if ($mes == 11) {
			$nombre = "Noviembre";
		} else if ($mes == 12) {
			$nombre = "Diciembre";
		}
		
		return $nombre;
	}
	
	
	/**
	 * metodo encargado de retornar una lista de tipos de sexo
	 */
	function listaTipoSexo()
	{
		$lista=array("Masculino","Femenino","Indeterminado");
		return $lista;
	}
	
	
	/***
	 * Metodo encargado de calcular edad dependiendo de la fecha de naciemiento
	 * @param unknown $fecha fecha de naciemiento
	 * @return unknown
	 */
	function calcularEdad($fecha)
	{
		$date1=date_create($fecha);
		$date2= new DateTime();
		$diff=date_diff($date1,$date2);
		return  $diff->y;
	}
	
	/***
	 * Metodo encargado de generar una clave aleatoria
	 * @return string retorna clave generada
	 */
	function claveAleatoria(){
		//Se define una cadena de caractares. Te recomiendo que uses esta.
		$cadena = "1234567890";
		//Obtenemos la longitud de la cadena de caracteres
		$longitudCadena=strlen($cadena);
			
		//Se define la variable que va a contener la contraseña
		$pass = "";
		//Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
		$longitudPass=6;
			
		//Creamos la contraseña
		for($i=1 ; $i<=$longitudPass ; $i++){
			//Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
			$pos=rand(0,$longitudCadena-1);
	
			//Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
			$pass .= substr($cadena,$pos,1);
		}
		return $pass;
	}
	
	
	
	/***
	 * Metodo encargado de retornar una lista de tipos de usuario
	 * @return string[]
	 */
	function listaTipoUsuario()
	{
		$lista=array("Administrador","Usuario");
		return $lista;
	}
	
	/***
	 * Metodo encargado de retornar el nombre de tipo de usuario dependiendo el codigo de tipo de usuario
	 * @param unknown $tipoUsuario codigo de tipo de usuario
	 * @return string
	 */
	function tipoUsuario($tipoUsuario)
	{
		if ($tipoUsuario==1)
		{
			$nombre="Administrador";
		}elseif ($tipoUsuario==2)
		{
			$nombre="Usuario";
		}
		return $nombre;
	}


	function estado($tipo)
	{
		if ($tipo==1)
		{
			$estado="Activo";
		}elseif ($tipo==0)
		{
			$estado="Inactivo";
		}
		return $estado;
	}

	/***
	 * Metodo encargado de retornar el nombre de tipo documento dependiendo el codigo 
	 * @param unknown $tipoUsuario codigo de tipo de usuario
	 * @return string
	 */
	function tipoDocumento($tipoDocumento)
	{
		if ($tipoDocumento==1)
		{
			$nombre="Cédula";
		}elseif ($tipoDocumento==2)
		{
			$nombre="RUC";
		}
		return $nombre;
	}

	/***
	 * Metodo encargado de retornar una lista de tipos de identificacion
	 * @return string[]
	 */
	function listaTipoIdentificacion()
	{
		$lista=array("Cédula","RUC");
		return $lista;
	}

	/***
	 * Metodo encargado de retornar una lista de tipos de comprobante
	 * @return string[]
	 */
	function listaTipoComprobante()
	{
		$lista=array("Factura","Nota de Venta");
		return $lista;
	}

	/***
	 * Metodo encargado de retornar el nombre de tipo comprobante dependiendo el codigo 
	 * @param unknown $tipoComprobante codigo de tipo de comprobante
	 * @return string
	 */
	function tipoComprobante($tipoComprobante)
	{
		if ($tipoComprobante==1)
		{
			$nombre="Factura";
		}elseif ($tipoComprobante==2)
		{
			$nombre="Nota de venta";
		}
		return $nombre;
	}
	
	
}
?>