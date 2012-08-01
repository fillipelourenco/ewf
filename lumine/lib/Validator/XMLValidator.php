<?php
/**
 * Classe de validacao por XML
 * 
 * @package Lumine_Validator
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br
 */

/**
 * Classe de validacao por XML
 * 
 * @package Lumine_Validator
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br
 */
class Lumine_Validator_XMLValidator
{
	/**
	 * lista de erros
	 * @var array
	 */
	private $errors    = array();
	/**
	 * Objeto que sera validado
	 * @var Lumine_Base
	 */
	private $obj       = null;
	/**
	 * Objeto XML
	 * @var resource
	 */
	private $xml       = null;
	
	/**
	 * 
	 * @author Hugo Ferreira da Silva
	 * @link http://www.hufersil.com.br/
	 * @param Lumine_Base $obj Instancia da Classe para ser validada
	 * @return Lumine_Validator_XMLValidator
	 */
	function __construct (Lumine_Base $obj)
	{
		$this->checkForValidationFile( $obj );
		
		libxml_use_internal_errors( true );
		libxml_clear_errors();
		
		$this->obj = $obj;
		// $this->xml = $xml;
	}
	
	/**
	 * Procura pelo arquivo XML para efetuar a validacao
	 * 
	 * @author Hugo Ferreira da Silva
	 * @link http://www.hufersil.com.br/
	 * @param Lumine_Base $obj Instancia da Classe para ser validada
	 * @return void
	 */
	protected function checkForValidationFile(Lumine_Base $obj)
	{
		if(!class_exists('DomDocument'))
		{
			Lumine_Log::warning('Classe de validacao por XML "DomDocument" nao definida"');
		} else {
			$cfg = $obj->_getConfiguration();

			$xml_validation_path = $cfg->getOption('xml_validation_path');
			$classes_path        = $cfg->getProperty('class_path') .
					DIRECTORY_SEPARATOR .
					str_replace('.', DIRECTORY_SEPARATOR, $cfg->getProperty('package'));
			
			
			$filename = $obj->_getName() . '-validation.xml';
			$file_list = array();
			$file_list[] = $cfg->getProperty('class_path') . DIRECTORY_SEPARATOR . $filename;
			$file_list[] = $classes_path . DIRECTORY_SEPARATOR . $filename;
			$file_list[] = $classes_path . DIRECTORY_SEPARATOR . 'validators' . DIRECTORY_SEPARATOR . $filename;
			
			if( !empty($xml_validation_path))
			{
				$file_list[] = $xml_validation_path . DIRECTORY_SEPARATOR . $filename;
			}
			
			$file = '';
			foreach($file_list as $filename)
			{
				if(file_exists($filename))
				{
					Lumine_Log::debug('Arquivo '.$filename.' encontrado, iniciando validacao.');
					$file = $filename;
					break;
				} else {
					Lumine_Log::debug('Arquivo '.$filename.' nao encontrado.');
				}
			}
			
			if($file != '')
			{
				$this->xml = $file;
			} else {
				Lumine_Log::warning('Nenhum arquivo de validacao em XML encontrado para "'.$obj->_getName().'"',__FILE__, __LINE__);
			}
		}
	}
	
	/**
	 * Chama o metodo que efetua a validacao
	 * 
	 * @author Hugo Ferreira da Silva
	 * @link http://www.hufersil.com.br/
	 * @param Lumine_Base $obj Objeto a ser validado
	 * @return array Array contendo os erros encontrados
	 */
	public static function validate(Lumine_Base $obj)
	{
		$instance = new Lumine_Validator_XMLValidator( $obj );
		return $instance->doValidation();
	}
	
	/**
	 * Efetua a validacao
	 * 
	 * @author Hugo Ferreira da Silva
	 * @link http://www.hufersil.com.br/
	 * @param Lumine_Base $obj Objeto a ser validado
	 * @return array Array contendo os erros encontrados
	 */
	public function doValidation()
	{
		// nao possui arquivo XML para validacao, sempre retorna true
		if( empty($this->xml) )
		{
			return true;
		}
		
		$xml = new DomDocument();
		$xml->validateOnParse = true;
		$xml->load( $this->xml );
		
		$errors = libxml_get_errors();
		libxml_clear_errors();
		
		if( !empty($errors))
		{
			Lumine_Log::error('A validacao de "'.$this->obj->_getName().'" nao pode ser executada por erros na formacao do XML. Analise o retorno do metodo "validate" para ver os erros');
			
			foreach($errors as $error)
			{
				$this->errors[] = trim($error->message);
			}
			return false;
		}
		
		// ok, o XML nao contem erros
		// vamos pegar os campos da validacao
		$xpath  = new DOMXPath( $xml );
		$DOMFieldList = $xpath->query('//lumine-validator/field');
		$errors = array();
		
		foreach($DOMFieldList as $DOMField)
		{
			// verifica se o campo existe
			try
			{
				$field = $this->obj->_getField( $DOMField->getAttribute('name') );
				$fieldname = $field['name'];

				// recupera a lista de validator para este campo
				$query = "//lumine-validator/field[@name='$fieldname']/validator";
				$DOMValidatorList = $xpath->query($query);
				
				// para cada validator
				foreach($DOMValidatorList as $DOMvalidator)
				{
					// se ja tiver validado o campo, houver outro validator
					// e nao passou no anterior, passa para proximo campo
					if( isset($errors[ $fieldname ]) && $errors[ $fieldname ] !== true)
					{
						break;
					}
				
					// pega os valores dos atributos
					$minlength     = sprintf('%d', $DOMvalidator->getAttribute('minlength'));
					$maxlength     = sprintf('%d', $DOMvalidator->getAttribute('maxlength'));
					$minvalue      = $DOMvalidator->getAttribute('minvalue');
					$maxvalue      = $DOMvalidator->getAttribute('maxvalue');
					$classname     = $DOMvalidator->getAttribute('classname');
					$msg           = $DOMvalidator->getAttribute('msg');
					$rule          = $DOMvalidator->getAttribute('rule');
					$method        = $DOMvalidator->getAttribute('method');
					$val           = $this->obj->$fieldname;
					$res           = false;
					
					if(empty($classname))
					{
						$classname = $DOMvalidator->getAttribute('name');
					}
					
					if($minvalue != '')
					{
						$minvalue = (float)$minvalue;
					} else {
						$minvalue = null;
					}

					if($maxvalue != '')
					{
						$maxvalue = (float)$maxvalue;
					} else {
						$maxvalue = null;
					}
					
					// ve o tipo
					switch($DOMvalidator->getAttribute('type'))
					{
						case 'requiredString':
							$res = $this->validateRequiredString( $val, $minlength, $maxlength );
						break;
						case 'requiredNumber':
							$res = $this->validateRequiredNumber( $val, $minvalue, $maxvalue );
						break;
						case 'requiredEmail':
							$res = Lumine_Util::validateEmail( $val );
						break;
						
						//Verifica se e uma data
						case 'requiredDate':
							if( ! preg_match('@^((\d{2}\/\d{2}\/\d{4})|(\d{4}-\d{2}-\d{2}))$@', $val, $reg)  ) {
								$res = false;
							
							// se digitou no formato com barras
							} else if( !empty($reg[2]) ) {
								list($dia,$mes,$ano) = explode('/', $reg[2]);
								
								// se nao for formato brasileiro e norte-americano
								if( !checkdate($mes,$dia,$ano) && !checkdate($dia,$mes,$ano) ) {
									$res = false;
								}
							// se digitou no formato ISO
							} else if( !empty($reg[3]) ) {
								list($ano,$mes,$dia) = explode('-', $reg[3]);
								
								// se for uma data valida
								if( !checkdate($mes,$dia,$ano) ) {
									$res = false;
								}
							} else {
								$res = true;
							}
							
						break;
						
						//Verifica se e CPF
						case 'requiredCpf':
							$res = ValidateCPF::execute($val);
						break;
							
						//Verifica se e CNPJ
						case 'requiredCnpj':
							$res = ValidateCNPJ::execute($val);
						break;
						
						
						case 'unique':
							$res = $this->validateUnique( $val, $fieldname );
						break;
						case 'class':
							$res = $this->validateByClass($val, $fieldname, $classname, $method);
						break;						
						case 'rule':
							$res = $this->validateRule($val, $rule);
						break;
						
						default:
							throw new Lumine_Validator_Exception('Tipo de validator desconhecido: '. $DOMValidator->getAttribute('type'));
					}
					
					if($res === false)
					{
						$errors[ $fieldname ] = utf8_decode($msg);
					} else {
						$errors[ $fieldname ] = $res;
					}
				}
				
			} catch(Exception $e) {
				Lumine_Log::warning($e->getMessage());
			}
		}
		
		// depois de todas as validacoes, vamos ver se deu erro em algum campo
		$tudo_ok = true;
		foreach($errors as $chave => $erro)
		{
			if($erro !== true)
			{
				$tudo_ok = false;
				$this->errors = $errors;
				break;
			}
		}
		
		// se realmente estiver tudo ok
		if( $tudo_ok === true )
		{
			return array();
		} else {
			return $this->errors;
		}
	}
	
	/**
	 * Recupera os erros
	 * @author Hugo Ferreira da Silva
	 * @link http://www.hufersil.com.br/
	 * @return boolean|array True se nao tiver erros ou um array de mensagens em caso de erros
	 */
	public function getErrors()
	{
		if(empty($this->errors))
		{
			return true;
		}
		return $this->errors;
	}
	
	
	#######################################################
	# Validators padroes
	#######################################################
	/**
	 * Valida se e uma string e nao esta vazia
	 * 
	 * @author Hugo Ferreira da Silva
	 * @link http://www.hufersil.com.br/
	 * @param mixed $val       Valor a ser validado
	 * @param int   $minlength Tamanho minimo
	 * @param int   $maxlength Tamanho maximo
	 * @return boolean|string True se nao tiver erros
	 */
	private function validateRequiredString( $val, $minlength, $maxlength)
	{
		if($val == '')
		{
			return false;
		}
		if($minlength > 0 && strlen($val) < $minlength)
		{
			return false;
		}
		if($maxlength > 0 && strlen($val) > $maxlength)
		{
			return false;
		}
		return true;
	}
	
	private function validateRequiredNumber( $val, $minvalue = null, $maxvalue = null)
	{
		if(!is_numeric($val))
		{
			return false;
		}
		if(!is_null($minvalue) && $val < $minvalue)
		{
			return false;
		}
		if(!is_null($maxvalue) && $val > $maxvalue)
		{
			return false;
		}
		return true;
	}
	
	private function validateRule($val, $rule)
	{
		if( !empty($rule))
		{
			$rule = preg_replace('@#(\w+)#@', '$this->obj->$1', $rule);
			$res = @eval('if('.$rule.') return true;');
			
			if(empty($res))
			{
				return false;
			}
			return true;
		}
		Lumine_Log::warning('Nenhuma regra definida para validar o campo');
		return false;
	}
	
	private function validateUnique( $val, $fieldname )
	{
		$classname = get_class($this->obj);
		$tester = new $classname;
		$tester->$fieldname = $val;
		
		$pode = true;
		if($tester->find( true ) > 0)
		{
			$pks = $tester->_getPrimaryKeys();
			foreach($pks as $def)
			{
				// se uma chave n�o bater, ent�o est� tentando inserir
				// daeh n�o pode
				if($tester->$def['name'] != $this->obj->$def['name'])
				{
					$pode = false;
					break;
				}
			}
		}
		
		unset($tester);
		
		return $pode;
	}
	
	private function validateByClass($val, $fieldname, $classname, $method)
	{
		if( empty($classname))
		{
			Lumine_Log::warning('Classe para validacao nao informada no XML. Use "classname" para informar o nome da classe');
			return false;
		}
		$ds = DIRECTORY_SEPARATOR;
		
		$cfg = $this->obj->_getConfiguration();
		$classpath   = $cfg->getProperty('class_path');
		$classespath = $classpath .
		               $ds . 
					   str_replace('.', '/', $cfg->getProperty('package')) . 
					   $ds . 
					   'validators' .
					   $ds;
		
		
		$classfile = str_replace('.','/', $classname) . '.php';
		$classdef  = array_pop(explode('.', $classname));
		$php_validator_path = $cfg->getOption('php_validator_path');

		$possibilidades = array();
		
		if( !empty($php_validator_path))
		{
			$possibilidades[] = $php_validator_path . $ds . $classfile;
		}
		
		$possibilidades[] = LUMINE_INCLUDE_PATH . $ds . 'lib' . $ds . 'Validator' . $ds . 'Custom' . $ds . $classfile;
		$possibilidades[] = $classpath . $ds . $classfile;
		$possibilidades[] = $classespath . $classfile;
		$use = '';
		
		foreach($possibilidades as $file)
		{
			if(file_exists($file))
			{
				$use = $file;
			}
		}
		
		if( empty($use))
		{
			Lumine_Log::error('Classe para validacao "'.$classname.'" nao encontrada');
			return false;
		}
		
		require_once $use;
		if( !class_exists($classdef))
		{
			Lumine_Log::error('Definicao para a classe de validacao "'.$classdef.'" nao encontrada');
			return false;
		}
		
		$tester = new $classdef;
		if( method_exists($tester, $method) && $method != '')
		{
			return $tester->$method( $val );
		} else if( method_exists($tester, 'execute'))
		{
			return $tester->execute( $val );
		} else {
			Lumine_Log::error('Metodo "'.$method.'" nao encontrado na classe "'.$classdef.'" e a classe nao possui o metodo "execute"');
			return false;
		}
	}
}


