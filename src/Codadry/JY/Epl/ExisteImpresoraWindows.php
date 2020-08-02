<?php
	
	class ExisteImpresoraWindows{

		//Necesitamos el powershell
	    private static $RUTA_POWERSHELL = 'c:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe'; 

	    //Se ejecuta el powershell y se escribe el comando "-c" para decirle que ejecutaremos un comando
	    private static $OPCION_PARA_EJECUTAR_COMANDOS = "-c";

	    //ayudante para concatenar
	    private static $ESPACIO = " "; 

	     //ayudante para concatenar
	    private static $COMILLA = '"';

	    //Comando de powershell para obtener lista de impresoras
	    private static $COMANDO = 'get-WmiObject -class Win32_printer |ft name'; 	        

		public function __construct() {        	
			//...
    	}

    	public function verificarImpresora($dispositivo, $informacion = false){
    		$lista_de_impresoras = array();
    		$bandera = false;
    		$info = "";
    		exec(
        		ExisteImpresoraWindows::$RUTA_POWERSHELL
        		. ExisteImpresoraWindows::$ESPACIO
        		. ExisteImpresoraWindows::$OPCION_PARA_EJECUTAR_COMANDOS
        		. ExisteImpresoraWindows::$ESPACIO
        		. ExisteImpresoraWindows::$COMILLA
        		. ExisteImpresoraWindows::$COMANDO
        		. ExisteImpresoraWindows::$COMILLA
        		,$resultado
        		,$codigo_salida
        	);

    		if ($codigo_salida === 0) {    	

		        if (is_array($resultado)) {		
		        	// Verificamos si existe el dispositivo
				    $bandera = in_array($dispositivo, $resultado);					
		        		        
			        if ($informacion) {
			        	if ($bandera) 	                	
			            $info .= "<h3>Dispositivo ".$dispositivo." encontrado.</h3>";
			        	else $info .= "<h3>Dispositivo ".$dispositivo." NO encontarado.</h3>";

			        	//Omitir los primeros 3 datos del arreglo, porque son el encabezado
			            for($i = 3; $i < count($resultado); $i++){	
			            	$info .= "<b>".trim($resultado[$i])."</b><br>"; 
			            }

			        	echo "<pre><h1>Lista de impresoras</h1></pre>";
			        	echo $info;
		        		echo "";
			        }
		    	}

		        
		        
		    } else {
		        echo "Error al ejecutar el comando.";
		    }
		    return $bandera;
    	}

    	



	}


?>