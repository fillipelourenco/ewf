<?php
	
	function MultiUpload($arquivos, $diretorio){		
		
		$contador = count($arquivos['name']);
				
		for($i = 0 ; $i < $contador ; $i++){
			$dir = "../anexos/".$diretorio."/".$arquivos["name"][$i];
			move_uploaded_file($arquivos["tmp_name"][$i], $dir);
		}
	}
	
	function ValidaAnexos($arquivos,$pasta){		
		
		$erro = '';
		$contador = count($arquivos['name']);
				
		for($i = 0 ; $i < $contador ; $i++){
			if ($arquivos['size'][$i] > 5242880) {
				$erro .= 'Anexo muito grande ('.$arquivos['name'][$i].')\n';
			}
			else if (file_exists('../anexos/'.$pasta.'/'.$arquivos['name'][$i])) {
				$erro .= 'Anexo já existe ('.$arquivos['name'][$i].')\n';
			} 
		}
		
		if ($erro != '') {
			echo "<script language=Javascript>alert('".$erro."');</script>";
		}
		else
			return true;
	}
	
	function ExisteAnexo($arquivos){
	
		$contador = count($arquivos['name']);
				
		for($i = 0 ; $i <= $contador ; $i++){
			if ($arquivos['size'][$i] > 0) return true;
		}
		return false;
	}
	
?>