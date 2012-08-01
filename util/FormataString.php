<?
	function RetiraAcentos($var) {

		$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ‗אבגדהוזחטיךכלםמןנסעףפץצרשתû‎‎‏ÿRr';
		$b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
		$var = strtolower($var);
		$var = strtr($var, $a, $b);
		$var = str_replace("ח","c",$var);
		
		return $var;
	}
	
	function RetiraEspacos($var){
	
		return str_replace(" ", "-", $var);
		
	}
	
?>