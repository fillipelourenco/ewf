<?php
/**
 * Classe abstrata para validacao
 * 
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br
 * @package Lumine
 */

// carrega os validators
Lumine::load('Validator_XMLValidator');
Lumine::load('Validator_ClassValidator');
Lumine::load('Validator_PHPValidator');

/**
 * Classe abstrata para validacao
 * 
 * @package Lumine
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br
 */
abstract class Lumine_Validator
{
	/**
	 * Indica que e para validar uma string
	 * @var string
	 */
	const REQUIRED_STRING = 'requiredString';
	/**
	 * Indica que e para validar um numero
	 * @var string
	 */
	const REQUIRED_NUMBER = 'requiredNumber';
	/**
	 * Valida o campo como sendo um email
	 * @var string
	 */
	const REQUIRED_EMAIL = 'requiredEmail';
	/**
	 * Valida o valor como sendo um CPF
	 * @var string
	 */
	const REQUIRED_CPF = 'requiredCpf';
	/**
	 * Valida o valor como sendo um CNPJ
	 * @var string
	 */
	const REQUIRED_CNPJ = 'requiredCnpj';
	/**
	 * Indica que o campo deve ser unico na tabela, nao pode haver outros registros com o mesmo valor
	 * @var string
	 */
	const REQUIRED_UNIQUE = 'requiredUnique';
	/**
	 * Validacao para o tamanho de uma string 
	 * @var string
	 */
	const REQUIRED_LENGTH = 'requiredLength';
	/**
	 * utiliza um callback (funcao) como validador
	 * @var string
	 */
	const REQUIRED_FUNCTION = 'requiredFunction';
	/**
	 * valida se o valor esta em formato de data
	 * @var string
	 */
	const REQUIRED_DATE = 'requiredDate';
	/**
	 * valida se o valor esta em formato de data/hora
	 * @var string
	 */
	const REQUIRED_DATE_TIME = 'requiredDateTime';

	/**
	 * Tipos de validacoes registradas
	 * @var array
	 */
	protected static $registered_validators = array (
		'Lumine_Validator_XMLValidator',
		'Lumine_Validator_ClassValidator',
		'Lumine_Validator_PHPValidator'
	);

	/**
	 * Registra um novo validator
	 * 
	 * @author Hugo Ferreira da Silva
	 * @link http://www.hufersil.com.br/
	 * @param string $name Nome da classe a ser registrada 
	 * @return void
	 */
	public static function registerValidator( $name )
	{
		array_push( self::$registered_validators, $name );
		self::$registered_validators = array_unique(self::$registered_validators);
	}

	/**
	 * Efetua a validacao
	 * 
	 * @author Hugo Ferreira da Silva
	 * @link http://www.hufersil.com.br/
	 * @param Lumine_Base $obj Objeto a ser validado
	 * @return boolean|array True quando tudo for valido do contrario um array contendo os erros
	 */
	public static function validate(Lumine_Base $obj)
	{
		############################################################################
		## Aqui vamos checar todos os tipos padrao de validacao
		## e armazenar os resultados em um array
		## para que o objeto passe na validacao, todos os retornos devem ser TRUE
		## para isto, utilizaremos a interface de reflexao
		############################################################################
		// aqui armazenamos o resultado das validacoes
		$results = array();

		// primeiro, carrega as classes registradas
		foreach(self::$registered_validators as $classname)
		{
			$ref = new ReflectionMethod( $classname, 'validate' );
			$results[] = $ref->invoke( null, $obj );
		}
		
		############################################################################
		## vamos checar se todos retornar true ou se algum deu erro
		############################################################################
		
		$tudo_ok = true;
		$erros = array();
		
		foreach( $results as $item )
		{
			if( $item !== true )
			{
				$tudo_ok = false;
				if( is_array($item) )
				{
					$erros = array_merge( $erros, $item );
				}
			}
		}
		
		// se deu erro em algum
		if( $tudo_ok == false )
		{
			// colocaremos os erros no array $_REQUEST
			
			foreach( $results as $item )
			{
				if( is_array($item) )
				{
					foreach( $item as $chave => $erro )
					{
						if( $erro !== true )
						{
							$_REQUEST[ $chave . '_error' ] = $erro;
						}
					}
				}
			}
			
			// retorna os erros
			return $erros;
		}
		
		// certo, passou em tudo, retorna verdadeiro
		return true;
	}

}


?>