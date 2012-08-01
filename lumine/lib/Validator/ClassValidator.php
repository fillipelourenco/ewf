<?php
/**
 * Classe de validacao, que invoca uma classe para realizar a validacao
 * Esta classe ira procurar todos os metodos que inicia com validate no objeto principal
 * 
 * @package Lumine_Validator
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br
 */

/**
 * Classe de validacao, que invoca uma classe para realizar a validacao
 * @package Lumine_Validator
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br
 */
class Lumine_Validator_ClassValidator
{

	/**
	 * Chama a validacao de todos os campos
	 * 
	 * Ira procurar por todos os metodos que comecem com "validate" do objeto e executar.
	 * O retorno da funcao, quando nao houver erros sempre deve ser boolean TRUE.
	 * 
	 * Qualquer outro valor sera considerado como erro e entrara na lista de erros.
	 * 
	 * @author Hugo Ferreira da Silva
	 * @link http://www.hufersil.com.br/
	 * @param Lumine_Base $obj objeto a ser validado
	 * @return array
	 */
	public static function validate(Lumine_Base $obj)
	{
		$erros = array();
		
		$metodos = get_class_methods( $obj );
		
		foreach( $metodos as $metodo ) 
		{
			$ref = new ReflectionMethod( $obj, $metodo );
			if( preg_match('@^validate(\w+)@', $metodo) && $ref->isPublic() == true )
			{
				$result = $ref->invoke( $obj );
				
				if( $result !== true )
				{
					$erros[] = $result;
				}
			}
			
			unset($ref, $result);
		}
		
		return $erros;
	}

}


