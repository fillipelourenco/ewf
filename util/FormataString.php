<?
	function RetiraAcentos($var) {

		$a = '��������������������������������������������������������������Rr';
		$b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
		$var = strtolower($var);
		$var = strtr($var, $a, $b);
		$var = str_replace("�","c",$var);
		
		return $var;
	}
	
	function RetiraEspacos($var){
	
		return str_replace(" ", "-", $var);
		
	}
	
?>