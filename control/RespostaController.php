<?

	class RespostaController {
	
		function RespostaController() {}
		
		/**
		* Coment�rio Selecionado
		* Retorno: Objeto
		*/
		function getResumo(){
			$pergunta = new Pergunta;
			$pergunta
				->where('id_formulario='.$_SESSION['read_formulario'].' and tipo=1')
				->find();
			$qtd = $pergunta->allToArray();
			if (count($qtd) < 1)
				echo "<script language=Javascript>alert('N�o h� nenhuma pergunta Aberta para este Formul�rio.');</script>";
			$resposta = new Resposta;
			
			$result .= '<div>';
			
			while($pergunta->fetch()) {
				$result .= '<p><label for="titulo" style="width:700px;">'.$pergunta->titulo.'</label><br>';
				$resposta->reset();
				$resposta
					->where('id_pergunta='.$pergunta->id_pergunta.'')
					->find();
				while($resposta->fetch()) {	
					$result .= '<p><textarea cols="90" rows="5">'.$resposta->resposta.'</textarea>';
					$result .= '</p><br>';
				}
			}
			
			return $result;
		}
		
	}

?>