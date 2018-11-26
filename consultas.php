<?php 
//Script para conexión con base de datos en Mysql
include("db_controller/mysql_script.php");

// Obtenemos parametros
$obj = (object)$_REQUEST;

switch ($obj->action) {
  case 'exportToCSV':
  	//Obtenemos los 100 primeros registros por medio de la siguiente consulta
    $rows = query("SELECT * FROM personal ORDER BY nombres ASC LIMIT 100");
	//Establecemos un rombre al documento que vamos a generar
	$filename = "Reporte en CSV.csv";
	//Establecemos el tipo de archivo que vamos a exportar
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    
	//create a file pointer
    $fl = fopen('php://memory', 'w');
    //Deliminar por comas
	$delimiter = ",";
	//Establecemos la cabecera del documento
    $rowArr = ['ID', 'NOMBRES', 'FRECUENCIA', 'SERIE'];
    //Añadimos al archivo la cabecera
    fputcsv($fl, $rowArr, $delimiter);
    //Recorremos por todos los registros
    foreach ($rows as $key => $row) {
    	//Adjuntamos en una variable el registro array
    	$rowArr = [ $row['id_personal'], $row['nombres'], $row['frecuencia'], $row['serie'] ];
    	//Añadimos al arhivo csv los registros en formato array
        fputcsv($fl, $rowArr, $delimiter);
    }
	//Volver al principio del archivo
    fseek($fl, 0);

    //generar todos los datos restantes en un archivo
    fpassthru($fl);
    
    break; 
}
?>