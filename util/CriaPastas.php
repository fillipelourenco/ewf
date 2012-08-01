<?
	//include 'FormataString.php';
	
	function CriaPastaTarefa($titulo){
		$titulo = RetiraEspacos(RetiraAcentos($titulo));
		$diretorio = "../anexos/".$titulo;
		$count = 1;
		$dir = $diretorio;
		while(file_exists($dir)){
			$dir = $diretorio."-".$count;
			$count++;
		}
		mkdir($dir, 0777);
		return $dir;
	}

?>