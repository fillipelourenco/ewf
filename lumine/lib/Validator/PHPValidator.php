<?php
/**
* Classe para fazer validacao em PHP
* @author Cairo Lincoln de Morais Noleto
* @link http://caironoleto.wordpress.com
* @author Hugo Ferreira da Silva
* @link http://www.hufersil.com.br
* @package Lumine_Validator
*/

require_once LUMINE_INCLUDE_PATH . '/lib/Validator/Custom/ValidateCPF.php';
require_once LUMINE_INCLUDE_PATH . '/lib/Validator/Custom/ValidateCNPJ.php';

/**
* Classe para fazer validacao em PHP
* @author Cairo Lincoln de Morais Noleto
* @link http://caironoleto.wordpress.com
* @author Hugo Ferreira da Silva
* @link http://www.hufersil.com.br
* @package Lumine_Validator
*/
class Lumine_Validator_PHPValidator
{
	function __construct(Lumine_Base $obj)
	{
		$this->obj = $obj;
	}
	
	/**
	 * Objeto para manter os membros que terao que ser validados
	 * @author Hugo Ferreira da Silva
	 */
	protected static $validateList = array();

	/**
	 * Adiciona um membro para a validacao
	 * Metodo para adicionar os campos para validacao
	 * @param Lumine_Base $obj Objeto que tera um membro validado
	 * @param $campo - Nome do campo para validacao
	 * @param $tipoValidacao - Metodo de validacao
	 * @param $message - Mensagem a ser retornada caso encontre algo invalido
	 * @return boolean - Retorna verdadeiro caso validacao inserida
	 * @author Cairo Lincoln de Morais Noleto
	 * @link http://caironoleto.wordpress.com
	 * @author Hugo Ferreira da Silva
	 * @link http://www.hufersil.com.br
	 **/
	public static function addValidation(Lumine_Base $obj, $campo, $tipoValidacao, $message, $minimo = null, $maximo = null) {
			self::$validateList[ $obj->_getName() ][$campo][] = array("campo" => $campo, "tipoValidacao" => $tipoValidacao, "message" => $message, "minimo" => $minimo, "maximo" => $maximo);
	}
	
	/**
	 * Limpa a lista de validacoes de uma determinada entidade
	 *
	 * @param Lumine_Base $obj Objeto que tera seus validators limpos
	 */
	public static function clearValidations(Lumine_Base $obj)
	{
	    self::$validateList[ $obj->_getName() ] = array();
	}

	
	/**
	 * @param Lumine_Base $obj Objeto a ser validado
	 * @return array - Retorna array contendo erros caso validacao invalida
	 * @author Cairo Lincoln de Morais Noleto
	 * @link http://caironoleto.wordpress.com
	 * @author Hugo Ferreira da Silva
	 * @link http://www.hufersil.com.br
	 **/
	public static function validate( Lumine_Base $obj ) {

		$fieldList = !empty(self::$validateList[ $obj->_getName() ]) ? self::$validateList[ $obj->_getName() ] : array();
		$errors = array();
		
		foreach ($fieldList as $fieldName => $validators)
		{
			// se ja houver um erro para o campo atual
			if( self::checkStackError($errors, $fieldName) == true )
			{
				// passa para o proximo campo
				continue;
			}
			foreach( $validators as $array )
			{
				// se ja houver um erro para o campo atual
				if( self::checkStackError($errors, $fieldName) == true )
				{
					// passa para o proximo campo
					break;
				}
				switch ($array["tipoValidacao"]) {
					//Verifica se e String
					case 'requiredString':
						if ( ! is_string($obj->$array["campo"]) || (strlen($obj->$array["campo"]) == 0) )
						{
							self::stackError( $errors, $fieldName, $array['message']);
						}
						
						if(isset($array["minimo"]) && strlen($obj->$array['campo']) < $array['minimo']){
							self::stackError( $errors, $fieldName, $array['message']);
						}
						
						// se foi informado o tamanho maximo
						if( isset($array['maximo']) ){
							// pega o campo
							$field = $obj->_getField($fieldName);
							// se o tamanho informado for maior que o comprimento
							if( isset($field['length']) && $array['maximo'] > $field['length'] ){
								throw new Lumine_Exception('Tamanho invalido para o campo '.$fieldName, Lumine_Exception::WARNING);
							}
							
						// alterado para se o usuario
						// informou um tamanho minimo, mas nao o maximo,
						// o maximo passa a ser o do campo
						} else if( !isset($array['maximo']) && isset($array['minimo']) ){
							$field = $obj->_getField($fieldName);
							if( isset($field['length']) ){
								$array['maximo'] = $field['length'];
							}
						}
						
						if(isset($array["maximo"]) && strlen($obj->$array['campo']) > $array['maximo']){
							self::stackError( $errors, $fieldName, $array['message']);
						}
						
						break;
					
					//Verifica se e Numero
					case 'requiredNumber':
						if ( ! is_numeric($obj->$array["campo"]))
						{
							self::stackError( $errors, $fieldName, $array['message']);
						
						} else if( is_numeric($obj->$array['campo']) ) {
						    
						    if( !is_null($array['minimo']) && $obj->$array['campo'] < $array['minimo'] )
						    {
						        self::stackError( $errors, $fieldName, $array['message']);
						        
						    } else if( !is_null($array['maximo']) && $obj->$array['campo'] > $array['maximo'] ) {
						        self::stackError( $errors, $fieldName, $array['message']);
						    }
						    
						}
						break;
						
					//Verifica se Tamanho invalido
					case 'requiredLength':
						if( isset($array["minimo"]) )
						{
							if( strlen($obj->$array["campo"]) < $array["minimo"] )
							{
								self::stackError( $errors, $fieldName, $array['message']);
							}
						}
							
						if( isset($array["maximo"]) )
						{
							if( strlen($obj->$array["campo"]) > $array["maximo"] )
							{
								self::stackError( $errors, $fieldName, $array['message']);
							}
						}
						break;
	
					//Verifica se e email
					case 'requiredEmail':
						//Lumine_Util::validateEmail( $val );
						$res = Lumine_Util::validateEmail( $obj->$array["campo"] );
						if ($res === false)
						{
							self::stackError( $errors, $fieldName, $array['message']);
						}
						break;
					
					//Verifica se e uma data
					case 'requiredDate':
						$val = $obj->$array["campo"];
						
						if( ! preg_match('@^((\d{2}\/\d{2}\/\d{4})|(\d{4}-\d{2}-\d{2}))$@', $val, $reg)  ) {
							self::stackError( $errors, $fieldName, $array['message']);
						
						// se digitou no formato com barras
						} else if( !empty($reg[2]) ) {
							list($dia,$mes,$ano) = explode('/', $reg[2]);
							
							// se nao for formato brasileiro e norte-americano
							if( !checkdate($mes,$dia,$ano) && !checkdate($dia,$mes,$ano) ) {
								self::stackError( $errors, $fieldName, $array['message']);
							}
						// se digitou no formato ISO
						} else if( !empty($reg[3]) ) {
							list($ano,$mes,$dia) = explode('-', $reg[3]);
							
							// se for uma data valida
							if( !checkdate($mes,$dia,$ano) ) {
								self::stackError( $errors, $fieldName, $array['message']);
							}
						}
						
						break;
						
					//Verifica se e uma data/hora
					case 'requiredDateTime':
						$val = $obj->$array["campo"];
						
						if( ! preg_match('@^((\d{2}\/\d{2}\/\d{4})|(\d{4}-\d{2}-\d{2})) (\d{2}:\d{2}(:\d{2})?)$@', $val, $reg)  ) {
							self::stackError( $errors, $fieldName, $array['message']);
						
						// se digitou no formato com barras
						} else if( !empty($reg[2]) ) {
							list($dia,$mes,$ano) = explode('/', $reg[2]);
							
							// se nao for formato brasileiro e norte-americano
							if( !checkdate($mes,$dia,$ano) && !checkdate($dia,$mes,$ano) ) {
								self::stackError( $errors, $fieldName, $array['message']);
							}
						// se digitou no formato ISO
						} else if( !empty($reg[3]) ) {
							list($ano,$mes,$dia) = explode('-', $reg[3]);
							
							// se for uma data valida
							if( !checkdate($mes,$dia,$ano) ) {
								self::stackError( $errors, $fieldName, $array['message']);
							}
						}
						
						break;
						
					//Verifica uniquidade
					// - Alteracao por Hugo: Aqui fiz uma mudanca, porque
					//   se fosse feita um update, daria erro. por isso, checamos as chaves primarias
					case 'requiredUnique':
						$reflection = new ReflectionClass( $obj->_getName() );
	
						$objeto = $reflection->newInstance();
						$objeto->$fieldName = $obj->$fieldName;
						$objeto->find();
						
						$todas = true;
						
						while ($objeto->fetch())
						{
							$pks = $objeto->_getPrimaryKeys();
							foreach( $pks as $def )
							{
								if( $objeto->$def['name'] != $obj->$def['name'])
								{
									$todas = false;
									self::stackError( $errors, $fieldName, $array['message']);
									break;
								}
								
								if( $todas == false )
								{
									break;
								}
							}
						}
						
						unset($objeto, $reflection);
						break;
						
					//Verifica uma funcao
					case 'requiredFunction':
						// se for um array
						if(is_array($array['message'])){
							$result = call_user_func_array($array['message'],array($obj, $fieldName, $obj->$fieldName));
							if($result !== true){
								self::stackError($errors,$fieldName,$result);
								break;
							}
						}
						
						if(is_string($array['message'])){
							$function = new ReflectionFunction( $array['message'] );
							$result = $function->invoke( $obj, $fieldName, $obj->$fieldName );
							
							if ($result !== true)
							{
								//$errors[] = $result;
								self::stackError( $errors, $fieldName, $result);
							}
							
							unset($function);
						}
						
					break;
						
					//Verifica se e CPF
					case 'requiredCpf':
						$res = ValidateCPF::execute($obj->$array["campo"]);
						if ($res === false)
						{
							self::stackError( $errors, $fieldName, $array['message']);
						}
						break;
						
					//Verifica se e CNPJ
					case 'requiredCnpj':
						$res = ValidateCNPJ::execute($obj->$array["campo"]);
						if ($res === false)
						{
							self::stackError( $errors, $fieldName, $array['message']);
						}
						break;
					
					default:
						return true;
					break;
				}
			}
		}

		return $errors;
	}
	
	/**
	 * Metodo auxiliar somente para colocar o nome do campo relacionado ao erro
	 * @param array $stack Pilha (array) de erros
	 * @param string $field Nome do campo
	 * @param string $value Valor a ser inserido no campo
	 * @author Hugo Ferreira da Silva
	 * @link http://www.hufersil.com.br
	 */
	protected static function stackError(array &$stack, $field, $value )
	{
		if( !isset($stack[ $field ]) )
		{
			$stack[ $field ] = $value;
		}
	}
	
	/**
	 * Verifica se ja nao existe um erro para o campo relacionado
	 * @param array $stack Pilha de erros
	 * @param string $field Nome do campo a ser checado
	 * @author Hugo Ferreira da Silva
	 * @link http://www.hufersil.com.br
	 * @return Boolean true se houver algum erro, false se n�o houver
	 */
	protected static function checkStackError(array &$stack, $field)
	{
		return isset($stack[ $field ]);
	}
}

