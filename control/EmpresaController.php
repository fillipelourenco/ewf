<?

	class EmpresaController {
	
		function EmpresaController() {}
		
		/**
		* Empresa Selecionada
		* Retorno: Objeto
		*/
		function get(){
			$empresa = new Empresa;
			if(!empty($_SESSION["id_empresa_logada"])){
				$empresa->get($_SESSION["id_empresa_logada"]);
				$e = $empresa->toArray();
			} else {
				$e = $empresa->toArray();
			}
			return $e;
		}
		
	}
	
?>