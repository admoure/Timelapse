
<html>
<head>

</head>
<body>
<?php

	//funcion para descarga de fichero
	function download_file($archiv)
	{
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($archiv).'"');
		header('Content-Length: ' . filesize($archiv));
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		
		ob_clean();
		flush();
		readfile($archiv);
		exit;
	}

?>





<?php
	//Mira todos los ficheros de un directorio y los mete en un vector
	global $label_name,$label_server,$label_port,$label_folder,$label_time,$label_latitud,$label_longitud,$label_altitud;
	$meses_array=array("jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec");
	global $fich;
	if (isset($_GET['anito']))
	{
		$anio=$_GET['anito'];
		$mes=$_GET['mese'];
		$dey=$_GET['dia'];
		for ($h=0;$h<12;$h++)
		{
			if ($meses_array[$h] == $mes)
			{
				$month = $h+1;
				break;
			}
		}
		$dey=str_pad($dey,2,"0",STR_PAD_LEFT);
		$month=str_pad($month,2,"0",STR_PAD_LEFT);

	}
	else
	{
		$anio=date("Y");
		$mes=date("M");
		$month=date("m");
		$mes=strtolower($mes);
		$dey=date("d");
	}

	$dir = "/media/CAM/".$anio."/".$mes."/".$dey;
	$directorio = opendir($dir);
	while (false != ($archivo = readdir($directorio)))
	{
		if (!is_dir($archivo))
		{
			$lista[] = $archivo;
		}
	}
	sort($lista);
?>



<?php
	//cuando se pulsa el boton de envio se coge el nombre del fichero y se llama a la 
	//funcion de descarga
if (isset($_POST['envio']))
{
	$name = $_POST['ficheros'];
	//echo "El fichero que has seleccionado es ".$name."<br>";
	$anio = substr($name,0,4);
	$month=substr($name,4,2);
	$dey=substr($name,6,2);
	$mes=$meses_array[$month-1];
	$dir = "/media/CAM/".$anio."/".$mes."/".$dey;
	$fich=$dir."/".$name;
	download_file($fich);
	//echo $anio." ".$month." ".$mes." ".$dey;
}


if (isset($_POST['selecciona']))
{
	$anio=$_POST['anios'];
	$mes=strtolower($_POST['meses']);

	for ($h=0;$h<12;$h++)
	{
		if ($meses_array[$h] == $mes)
		{
			$month = $h+1;
			break;
		}
	}
}

if (isset($_POST['configurar']))
{
	$cf="/home/pi/timelapse/configura.txt";
	
	$cf_abierto=fopen($cf,"w");
	
	$line="NOMBRE=".$_POST['nombre']."\n";
	fwrite($cf_abierto,$line);
	$line="MUESTREO=".$_POST['tiempo']."\n";
	fwrite($cf_abierto,$line);
	$line="SERVIDOR=".$_POST['server']."\n";
	fwrite($cf_abierto,$line);
	$line="PORT=".$_POST['port']."\n";
	fwrite($cf_abierto,$line);
	$line="FOLDER=".$_POST['folder']."\n";
	fwrite($cf_abierto,$line);
	$line="LATITUD=".$_POST['lat']."\n";
	fwrite($cf_abierto,$line);
	$line="LONGITUD=".$_POST['lon']."\n";
	fwrite($cf_abierto,$line);
	$line="ALTITUD=".$_POST['alt']."\n";
	fwrite($cf_abierto,$line);
	fclose($cf_abierto);
	
}

	//MIRA EL FICHERO DE CONFIGURACION
	
	$cf="/home/pi/timelapse/configura.txt";
	
	if (file_exists($cf))
	{
		$cf_abierto=fopen($cf,"r");
		
		$get_line=fgets($cf_abierto);
		$tok=strtok($get_line,"=");
		$tok=strtok("=");
		$label_name=$tok;
				
		$get_line=fgets($cf_abierto);
		$tok=strtok($get_line,"=");
		$tok=strtok("=");
		$label_time=$tok;		
		
		$get_line=fgets($cf_abierto);
		$tok=strtok($get_line,"=");
		$tok=strtok("=");
		$label_server=$tok;				
		
		$get_line=fgets($cf_abierto);
		$tok=strtok($get_line,"=");
		$tok=strtok("=");
		$label_port=$tok;			
		
		$get_line=fgets($cf_abierto);
		$tok=strtok($get_line,"=");
		$tok=strtok("=");
		$label_folder=$tok;
		
		$get_line=fgets($cf_abierto);
		$tok=strtok($get_line,"=");
		$tok=strtok("=");
		$label_latitud=$tok;		
		
		$get_line=fgets($cf_abierto);
		$tok=strtok($get_line,"=");
		$tok=strtok("=");
		$label_longitud=$tok;	
		
		$get_line=fgets($cf_abierto);
		$tok=strtok($get_line,"=");
		$tok=strtok("=");
		$label_altitud=$tok;	
		
		fclose($cf_abierto);
			
	}

?>



<?
if (isset($_GET['width']))
{
	$width=$_GET['width'];
	$width_=$width*45/100;
	$height=$_GET['height'];
	//echo $width_;
	
}
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">


	<center>
	<H1> WEBCAM </H1>
	

	<div style="float:left;width:20%">
		<div style="float:left;width:100%">
			<h2>Configuraci&oacute;n </h2>
		</div>
		<div style="float:right;width:100%">
			<div style="float:left;width:45%">
				<p>Nombre de la estaci&oacute;n:</p>
			</div>
			<div style="float:left;width:45%">
				<p align="left"><input type=text size=10 name=nombre value=<?echo $label_name;?>></p>
			</div>
		</div>
		
		<div style="float:right;width:100%">
			<div style="float:left;width:45%">
				<p>Tiempo de toma de imagenes:</p>
			</div>
			<div style="float:left;width:45%">
				<p align="left"><input type=text size=3 name=tiempo value=<?echo $label_time;?>></p>
			</div>
		</div>
		
		
		<div style="float:right;width:100%">
			<div style="float:left;width:45%">
				<p>Servidor:</p>
			</div>
			<div style="float:left;width:45%">
				<p align="left"><input type=text size=18 name=server value=<?echo $label_server;?>></p>
				
			</div>
			<div style="float:left;width:45%">
				<p>Puerto:</p>
			</div>
			<div style="float:left;width:45%">
				<p align="left"><input type=text size=3 name=port value=<?echo $label_port?>></p>
			</div>
			<div style="float:left;width:45%">
				<p>Carpeta:</p>
			</div>
			<div style="float:left;width:45%">
				<p align="left"><input type=text size=18 name=folder value=<?echo $label_folder?>></p>
			</div>
			
		</div>

		<div style="float:right;width:100%">
				<h3>Localizaci&oacute;n</h3>
		</div>
		
					
		<div style="float:right;width:100%;height:30px">
			<div style="float:left;width:45%">
				<p>Latitud:</p>
			</div>
			<div style="float:left;width:45%">
				<p align="left"><input type=text size=10 name=lat value=<?echo $label_latitud;?>></p>
			</div>
		</div>
		
		<div style="float:right;width:100%;height:30px">
			<div style="float:left;width:45%">
				<p>Longitud:</p>
			</div>
			<div style="float:left;width:45%">
				<p align="left"><input type=text size=10 name=lon value=<?echo $label_longitud;?>></p>
			</div>
		</div>
		
		<div style="float:right;width:100%">
			<div style="float:left;width:45%">
				<p>Altitud:</p>
			</div>
			<div style="float:left;width:45%">
				<p align="left"><input type=text size=10 name=alt value=<?echo $label_altitud;?>></p>
			</div>
		</div>
		
		<div style="float:right;width:100%">
			<p align="center"><input type="submit" name="configurar" value="Guardar"></p>
		</div>

		
		
		
		
		
	</div>

	
	<div style="border-style:solid;border-width:thin;float:left;width:48%">
		<p align="center"><img src="actual.jpg" width=<?echo $width_;?>></p>
	

	</div>

	<img src="gravimetro.png">	
	
	
	<div style="float:left;width:30%">
	
		<div style="float:left;width:100%;height:70px">
		<h2 align="center"> Descarga de imagenes del dia </h2>	
		</div>

		<select size="1" name="anios">

		<?php
			$paranios=date("Y");
			for($j=0;$j<2;$j++)
			{
				if ($anio==$paranios+$j)
				{
					echo "<option selected>".($paranios+$j)."</option><br>";
				}
				else
				{
					echo "<option>".($paranios+$j)."</option><br>";
				}
			}

		?>
		</select>

		<select size="1" name="meses">
		<?php

			for($j=0;$j<12;$j++)
			{
				if ($mes == strtolower(date("M",mktime(0,0,0,$j+1,1,$anio))))
				{
					echo "<option selected>".date("M",mktime(0,0,0,$j+1,1,$anio))."</option><br>";

				}
				else
				{
					echo "<option>".date("M",mktime(0,0,0,$j+1,1,$anio))."</option><br>";
				}
			}

		?>
		</select>

		<input type="submit" name="selecciona" value="Select">

		<br>

		<?
			$pos=date("w",mktime(0,0,0,$month,1,$anio));
			if ($pos == 0)
			$pos = 7;
			$last=date("t",mktime(0,0,0,$month,1,$anio));
			//echo $pos."  ".$last;
			

		?>

		<div style="float:left;width:100%">
		<table>
		<tr>
			<?
			echo "<td colspan=2></td>";
			echo "<td colspan=5><strong>"." ".$mes." ".$anio."</strong></td>";
			echo "<td colspan=2></td>";
			?>
		</tr>

		<tr>
			<td><strong>Lun</strong></td>
			<td><strong>Mar</strong></td>
			<td><strong>Mie</strong></td>
			<td><strong>Jue</strong></td>
			<td><strong>Vie</strong></td>
			<td><strong>Sab</strong></td>
			<td><strong>Dom</strong></td>
			

		</tr>
		<tr>
			<?
				$k=1;
				$m=0;  //para que no tenga en cuenta los dias del mes anterior
				while($k<$last+1)
				{
					echo "<tr>";
					for ($j=0;$j<7;$j++)
					{
						$m++;
						if ($j+1 < $pos && $m < $pos)
						{
							echo "<td align=center> </td>";
						}
						else
						{
							echo "<td align=center><a href=index.php?width=".$width."&dia=".$k."&mese=".$mes."&anito=".$anio.">".$k."</a></td>";
							$k++;
						}
						if ($k==$last+1)
						break;

					}
					echo "</tr>";

				}

			?>

		</tr>


		</table>
		</div>
		<div style="float:left;width:100%">	
		<select size="1" name="ficheros">

		<?php
			for ($i=0;$i<count($lista);$i++)
			{
				echo "<option>".$lista[$i]."</option><br>";
			}
		?>

		</select>

		<input type="submit" name="envio" value="Descargar">
		</div>
		<br><br>
		<div style="float:left;width:100%;height:60px">
			<h2>Informaci&oacute;n </h2>
		</div>
		
		<?
			exec("df -h /media/CAM",$salida);
						
			$tok = strtok($salida[1]," ");
			$mm=0;
			while($tok != false)
			{
				$varios[$mm]=$tok;
				//echo "<br>".$varios[$mm]."<br>";
				$tok = strtok(" ");
				$mm++;
			}
		?>
		
			
		<div style="float:right;width:100%">
			<div style="float:left;width:45%">
				<p align="right"><strong>Capacidad:</strong></p>
			</div>
			<div style="border-width:thin;float:right;width:45%">
				<p align="left"><?echo $varios[1];?></p>
			</div>
		</div>
			
		<div style="float:right;width:100%">
			<div style="border-width:thin;float:left;width:45%">
				<p align="right"><strong>Usado:</strong></p>
			</div>
			<div style="border-width:thin;float:right;width:45%">
				<p align="left"><?echo $varios[4];?></p>
			</div>
		</div>
		</center>
	</div>
	

	
</form>
</body>
</html>
