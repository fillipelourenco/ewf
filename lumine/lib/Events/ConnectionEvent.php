<?php

class Lumine_ConnectionEvent extends Lumine_Event {
	
	/**
	 * Objeto de conexao
	 * @var ILumine_Connection
	 */
	public $connection;
	/**
	 * mensagem de erro
	 * @var string
	 */
	public $msg;
	/**
	 * sql executada
	 * @var string
	 */
	public $sql;
	
	/**
	 * 
	 * @author Hugo Ferreira da Silva
	 * @link http://www.hufersil.com.br
	 * @param string $type
	 * @param ILumine_Connection $connection
	 * @param string $msg Mensagem
	 * @param string $sql Comando SQL
	 * @return Lumine_ConnectionEvent
	 */
	function __construct($type, ILumine_Connection $connection, $msg = null, $sql = ''){
		$this->type = $type;
		$this->connection = $connection;
		$this->msg = $msg;
		$this->sql = $sql;
		
	}
	
}

