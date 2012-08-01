<?php
/**
 * Conexao com MySQL
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br
 * @package Lumine_Connection
 */

Lumine::load('Connection_IConnection');

/**
 * Conexao abstrata
 * 
 * @package Lumine_Connection
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br
 */
abstract class Lumine_Connection_AbstractConnection
	extends Lumine_EventListener
	implements ILumine_Connection {

	/**
	 * Estado fechado
	 * @var int
	 */
	const CLOSED           = 0;
	/**
	 * Estado aberto
	 * @var int
	 */
	const OPEN             = 1;

	/**
	 * Constante para versao do servidor
	 * @var int
	 */
	const SERVER_VERSION   = 10;
	/**
	 * Constante para versao do cliente
	 * @var int
	 */
	const CLIENT_VERSION   = 11;
	/**
	 * Constante para informacoes do host
	 * @var int
	 */
	const HOST_INFO        = 12;
	/**
	 * tipo de protocolo
	 * @var int
	 */
	const PROTOCOL_VERSION = 13;
	/**
	 * Tipos de eventos disparados pela classe
	 * @var array
	 */
	protected $_event_types = array(
		Lumine_Event::PRE_EXECUTE,
    	Lumine_Event::POS_EXECUTE,
    	Lumine_Event::PRE_CONNECT,
    	Lumine_Event::POS_CONNECT,
    	Lumine_Event::PRE_CLOSE,
    	Lumine_Event::POS_CLOSE,
    	Lumine_Event::EXECUTE_ERROR,
    	Lumine_Event::CONNECTION_ERROR
	);
	
	/**
	 * ID da conexao
	 * @var resource
	 */
	protected $conn_id;
	/**
	 * nome do banco de dados
	 * @var string
	 */
	protected $database;
	/**
	 * nome do usuario
	 * @var string
	 */
	protected $user;
	/**
	 * senha do usuario
	 * @var string
	 */
	protected $password;
	/**
	 * porta de conexao
	 * @var integer
	 */
	protected $port;
	/**
	 * host do banco de dados
	 * @var string
	 */
	protected $host;
	/**
	 * opcoes
	 * @var array
	 */
	protected $options;
	/**
	 * Estado atual
	 * @var int
	 */
	protected $state;
	/**
	 * charset da conexao 
	 * @var string
	 */
	protected $charset;
	/**
	 * caracter de escape
	 * @var string
	 */
	protected $escapeChar = '\\';
	/**
	 * string identificadora da funcao random
	 * @var string
	 */
	protected $randomFunction = '';
	/**
     * referencias de transacoes abertas
     * @var array
     */
    protected $transactions = array();
    /**
     * numero de transacoes abertas
     * @var int
     */
    protected $transactions_count = 0;
	
	
	/**
	 * @see ILumine_Connection::connect()
	 */
	public function connect(){}
	
	/**
	 * @see ILumine_Connection::close()
	 */
	public function close(){}
	
	/**
	 * @see ILumine_Connection::getState()
	 */
	public function getState()
	{
		return $this->state;
	}
	/**
	 * @see ILumine_Connection::setDatabase()
	 */
	public function setDatabase($database)
	{
		$this->database = $database;
	}
	/**
	 * @see ILumine_Connection::getDatabase()
	 */
	public function getDatabase()
	{
		return $this->database;
	}
	/**
	 * @see ILumine_Connection::setUser()
	 */
	public function setUser($user)
	{
		$this->user = $user;
	}
	/**
	 * @see ILumine_Connection::getUser()
	 */
	public function getUser()
	{
		return $this->user;
	}
	/**
	 * @see ILumine_Connection::setPassword()
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}
	/**
	 * @see ILumine_Connection::getPassword()
	 */
	public function getPassword()
	{
		return $this->password;
	}
	/**
	 * @see ILumine_Connection::setPort()
	 */
	public function setPort($port)
	{
		$this->port = $port;
	}
	/**
	 * @see ILumine_Connection::getPort()
	 */
	public function getPort()
	{
		return $this->port;
	}
	/**
	 * @see ILumine_Connection::setHost()
	 */
	public function setHost($host)
	{
		$this->host = $host;
	}
	/**
	 * @see ILumine_Connection::getHost()
	 */
	public function getHost()
	{
		return $this->host;
	}
	/**
	 * @see ILumine_Connection::setOptions()
	 */
	public function setOptions($options)
	{
		$this->options = $options;
	}
	/**
	 * @see ILumine_Connection::getOptions()
	 */
	public function getOptions()
	{
		return $this->options;
	}
	/**
	 * @see ILumine_Connection::setOption()
	 */
	public function setOption($name, $val)
	{
		$this->options[ $name ] = $val;
	}
	/**
	 * @see ILumine_Connection::getOption()
	 */
	public function getOption($name)
	{
		if(empty($this->options[$name]))
		{
			return null;
		}
		return $this->options[$name];
	}
	
	/**
	 * @see ILumine_Connection::setCharset()
	 */
	public function setCharset($charset){
		$this->charset = $charset;
	}
	
	/**
	 * @see ILumine_Connection::getCharset()
	 */
	public function getCharset(){
		return $this->charset;
	}
	
	/**
	 * @see ILumine_Connection::getErrorMsg()
	 */
	public function getErrorMsg(){}
	
	/**
	 * @see ILumine_Connection::getTables()
	 */
	public function getTables(){}
	
	/**
	 * @see ILumine_Connection::getForeignKeys()
	 */
	public function getForeignKeys($tablename){}

	/**
	 * @see ILumine_Connection::getServerInfo()
	 */
	public function getServerInfo($type = null){}
	
	/**
	 * @see ILumine_Connection::describe()
	 */
	public function describe($tablename){}
	
	/**
	 * @see ILumine_Connection::executeSQL()
	 */
	public function executeSQL($sql){}
	
	/**
	 * @see ILumine_Connection::setLimit()
	 */
	public function setLimit($offset = null, $limit = null){} 
	
	/**
	 * @see ILumine_Connection::escape()
	 */
	public function escape($str){} 
	
	/**
	 * @see ILumine_Connection::escapeBlob()
	 */
	public function escapeBlob($blob){}
	
	/**
	 * @see ILumine_Connection::affected_rows()
	 */	
	public function affected_rows(){}
	
	/**
	 * @see ILumine_Connection::num_rows()
	 */
	public function num_rows($rs){}
	
	/**
	 * @see ILumine_Connection::random()
	 */
	public function random(){
		return $this->randomFunction;
	}
	
	/**
	 * @see ILumine_Connection::getEscapeChar()
	 */
	public function getEscapeChar(){}
	
	
	/**
	 * @see ILumine_Connection::begin()
	 */
	public function begin($transactionID=null){}
	
	/**
	 * @see ILumine_Connection::commit()
	 */
	public function commit($transactionID=null){}

	/**
	 * @see ILumine_Connection::rollback()
	 */
	public function rollback($transactionID=null){}
	
	/**
	 * @see Lumine_EventListener::__destruct()
	 */
    function __destruct()
    {
        unset($this->conn_id);
        unset($this->database);
        unset($this->user);
        unset($this->password);
        unset($this->port);
        unset($this->host);
        unset($this->options);
        unset($this->state);
        unset($this->transactions);
        unset($this->transactions_count);
        //unset(self::$instance);
        
        parent::__destruct();
    }
}

