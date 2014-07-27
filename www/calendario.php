
	<?php 
		session_start(); 
		if (!isset($_SESSION['year']))
		{
			$_SESSION['year']=date("Y");
		}
		if (!isset($_SESSION['month']))
		{
			$_SESSION['month']=date("m");
		}
		if (!isset($_SESSION['monthl']))
		{
			$_SESSION['monthl']=date("M");
		}
	?>
	<html>
	<head>
	</head>
	<body>
	<?
		function yearsubtract()
		{
			if(isset($_SESSION['year']))
			$_SESSION['year']=$_SESSION['year']-1;
		}

		function yearadd()
		{
			if(isset($_SESSION['year']))
			$_SESSION['year']=$_SESSION['year']+1;
		}

		function monthadd()
		{
			if(isset($_SESSION['month']))
			{
				if($_SESSION['month'] == 12)
				{
					$_SESSION['month']=1;
				}
				else
				{
					$_SESSION['month']++;
				}
				$_SESSION['monthl'] = date("M",mktime(0,0,0,$_SESSION['month'],1,$_SESSION['year']));
				//echo "<a href=index.php?var=echo $monthl/>";

			}
		}

		function monthsubtract()
		{
			if(isset($_SESSION['month']))
			{
				if($_SESSION['month'] == 1)
				{
					$_SESSION['month']=12;
				}
				else
				{
					$_SESSION['month']--;
				}
				$_SESSION['monthl'] = date("M",mktime(0,0,0,$_SESSION['month'],1,$_SESSION['year']));
				//echo "<a href=index.php?var=echo $monthl/>";
			}
		}

	?>

	<?
		$pos=date("w",mktime(0,0,0,$_SESSION['month'],1,$_SESSION['year']));
		$last=date("t",mktime(0,0,0,$_SESSION['month'],1,$_SESSION['year']));
	?>
	<?
		if ($_GET['accion']=="quita")
		{
			yearsubtract();
		}
		if ($_GET['accion']=="suma")
		{
			yearadd();
		}
		if ($_GET['accion']=="messuma")
		{
			monthadd();
		}
		if ($_GET['accion']=="mesquita")
		{
			monthsubtract();
		}
	?>

	<form name="formulario" method="post" action="index.php">

	<table>
	<tr>

		<td align=center><a href=index.php?accion=quita><</a></td>
		<?
			echo "<td colspan=5 align=center><strong>".$_SESSION['year']."</strong></td>";
		?>
		<td align=center><a href=index.php?accion=suma>></a></td>

	</tr>
	<tr>
		<td align=center><a href=index.php?accion=mesquita><</a></td>
		<?
			echo "<td colspan=5 align=center><strong>".$_SESSION['monthl']."<strong></td>";
		?>
		<td align=center><a href=index.php?accion=messuma>></a></td>

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
			$m=0;
			while($k<$last)
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
						echo "<td align=center><a href=index.php?dia=".$k."&mese=5&anito=5>".$k."</a></td>";
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
	</form>
	</body>
	</html>
