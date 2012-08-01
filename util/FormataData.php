<?

	function FormataData($data) {
	
		$date = explode("-", $data);

		switch ($date[1]) {
			case '01':
			$mes = "Janeiro";
			break;
			case '02':
			$mes = "Fevereiro";
			break;
			case '03':
			$mes = "Março";
			break;
			case '04':
			$mes = "Abril";
			break;
			case '05':
			$mes = "Maio";
			break;
			case '06':
			$mes = "Junho";
			break;
			case '07':
			$mes = "Julho";
			break;
			case '08':
			$mes = "Agosto";
			break;
			case '09':
			$mes = "Setembro";
			break;
			case '10':
			$mes = "Outubro";
			break;
			case '11':
			$mes = "Novembro";
			break;
			case '12':
			$mes = "Dezembro";
			break;
		}
		
		return $date[2]." de ".$mes." de ".$date[0];
	}
	
	function FormataDataNumeros($data) {
	
		$date = explode("-", $data);
		
		return $date[2]."/".$date[1]."/".$date[0];
	}
	
	function FormataDataBD($data) {
	
		$date = explode("/", $data);
		
		return $date[2]."-".$date[1]."-".$date[0];
	}
	
	function MesAtual($num_mes) {
	
		switch ($num_mes) {
			case '01':
			$mes = "Janeiro";
			break;
			case '02':
			$mes = "Fevereiro";
			break;
			case '03':
			$mes = "Março";
			break;
			case '04':
			$mes = "Abril";
			break;
			case '05':
			$mes = "Maio";
			break;
			case '06':
			$mes = "Junho";
			break;
			case '07':
			$mes = "Julho";
			break;
			case '08':
			$mes = "Agosto";
			break;
			case '09':
			$mes = "Setembro";
			break;
			case '10':
			$mes = "Outubro";
			break;
			case '11':
			$mes = "Novembro";
			break;
			case '12':
			$mes = "Dezembro";
			break;
		}
		return $mes;
	}
	
	function FormataDataHora($dt) { 
		$yr=strval(substr($dt,0,4)); 
		$mo=strval(substr($dt,5,2)); 
		$da=strval(substr($dt,8,2)); 
		$hr=strval(substr($dt,11,2)); 
		$mi=strval(substr($dt,14,2)); 
		$si=strval(substr($dt,17,2)); 
		return date("d/m/Y H:i:s", mktime ($hr,$mi,$si,$mo,$da,$yr)); 
	} 
	
	function FormataDataTs($dt) { 
		$yr=strval(substr($dt,0,4)); 
		$mo=strval(substr($dt,5,2)); 
		$da=strval(substr($dt,8,2)); 
		$hr=strval(substr($dt,11,2)); 
		$mi=strval(substr($dt,14,2)); 
		$si=strval(substr($dt,17,2)); 
		return date("d/m/Y", mktime ($hr,$mi,$si,$mo,$da,$yr)); 
	} 
	function FormataHora($dt) { 
		$yr=strval(substr($dt,0,4)); 
		$mo=strval(substr($dt,5,2)); 
		$da=strval(substr($dt,8,2)); 
		$hr=strval(substr($dt,11,2)); 
		$mi=strval(substr($dt,14,2)); 
		$si=strval(substr($dt,17,2)); 
		return date("H:i", mktime ($hr,$mi,$si,$mo,$da,$yr)); 
	}
	
	function difData($data) {
	
		$ano_banco = substr($data,0,4);
		$mes_banco = substr($data,5,2);
		$dia_banco = substr($data,8,2);
		$data = $dia_banco."-".$mes_banco."-".$ano_banco;

		$data_atual = date("Y-m-d");
		$ano_atual = substr($data_atual,0,4);
		$mes_atual = substr($data_atual,5,2);
		$dia_atual = substr($data_atual,8,2);
		$data_atual = $dia_atual."-".$mes_atual."-".$ano_atual;

		$data = mktime(0,0,0,$mes_banco,$dia_banco,$ano_banco);		
		$data_atual = mktime(0,0,0,$mes_atual,$dia_atual,$ano_atual);
		
		$dias = ($data - $data_atual)/86400;

		$dias = ceil($dias);
		
		return $dias;
	}

	
?>