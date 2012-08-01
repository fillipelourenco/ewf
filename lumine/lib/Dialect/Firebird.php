<?php
/**
 * Classe de conexao com o firebird
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br
 * @package Lumine_Dialect
 */

Lumine::load('Dialect_Exception');
Lumine::load('Dialect_IDialect');

/**
 * Classe de conexao com o firebird
 * @package Lumine_Dialect
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br
 */
class Lumine_Dialect_Firebird extends Lumine_EventListener implements ILumine_Dialect
{

	/**
	 * Conexao ativa
	 * @var ILumine_Connection
	 */
	private $connection = null;
	
	/**
	 * Resultset atual
	 * @var resource
	 */
	private $result_set = null;
	
	/**
	 * Objeto que requisitou a "ponte"
	 * @var Lumine_Base
	 */
	private $obj        = null;
	
	/**
	 * Dataset do registro atual
	 * @var array
	 */
	private $dataset    = array();
	
	/**
	 * Ponteiro atual
	 * @var integer
	 */
	private $pointer    = 0;
	
	/**
	 * Modo de recuperacao dos nomes das colunas
	 * @var string
	 */
	private $fetchMode  = '';
	
	/**
	 * 
	 * @var array
	 */
	private $datasetList = array();
	/**
	 * 
	 * @var array
	 */
	private $resultList = array();
	/**
	 * 
	 * @var int
	 */
	private $objectID    = 0;
	
	/**
	 * 
	 * @var array
	 */
	private $pointerList = array();

	/**
	 * Construtor
	 * @author Hugo Ferreira da Silva
	 * @link http://www.hufersil.com.br/
	 * @param Lumine_Base $obj
	 * @return ILumine_Dialect
	 */
	function __construct(Lumine_Base $obj = null)
	{
		//$this->obj = $obj;
		if( !is_null($obj) ){
			$this->setConnection( $obj->_getConnection() );
			$this->setFetchMode( $obj->fetchMode() );
			$this->setTablename($obj->tablename());
		}
	}

	/**
	 * @see ILumine_Dialect::getFetchMode()
	 */
	public function getFetchMode()
	{
		return $this->fetchMode;
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::getObjectId()
	 */
	public function getObjectId(){
		return $this->objectID;
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::setConnection()
	 */
	public function setConnection(ILumine_Connection $cnn)
	{
		$this->connection = $cnn;
	}

	/**
	 * 
	 * @see ILumine_Dialect::getConnection()
	 */
	public function getConnection()
	{
		return $this->connection;
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::setFetchMode()
	 */
	public function setFetchMode( $mode )
	{
		$this->fetchMode = $mode;
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::getTablename()
	 */
	public function getTablename()
	{
	    return $this->tablename;
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::setTablename()
	 */
	public function setTablename( $tablename )
	{
	    $this->tablename = $tablename;
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::setObjectId($objectID)
	 */
	public function setObjectId($objectID){
		$this->objectID = $objectID;
	}

	/**
	 * 
	 * @see ILumine_Dialect::execute()
	 */
	public function execute($sql)
	{
		$cn = $this->getConnection();
		if( $cn == null )
		{
			throw new Lumine_Dialect_Exception('Conexao nao setada');
		}

		$cn->connect();		
		$this->setConnection($cn);
		
		try
		{
			
			Lumine_Log::debug( 'Executando consulta: ' . $sql);
			$mode = $this->getFetchMode();
			$rs = $cn->executeSQL($sql);
			
		
			//$this->pointer = 0;
			if( is_resource($rs) )
			{
				// limpa o resultado anterior
				$this->freeResult($this->getObjectId());
				
				Lumine_Log::debug('Armazenando resultset');
				$this->setResultset($rs);
				
				$data = array();
				
				Lumine_Log::debug('Iterando pelos resultados');
				while($row = ibase_fetch_assoc($this->getResultset(), IBASE_FETCH_BLOBS))
				{
					$data[] = $row;
				}
				
				Lumine_Log::debug('Alterando o dataset');
				$this->setDataset($data);
				
				Lumine_Log::debug('Gravando pointer list');
				$this->pointerList[$this->getObjectId()] = 0;
				
				return true;
			} else {
				return $rs;
			}
			
		} catch (Exception $e) {
			Lumine_Log::warning( 'Falha na consulta: ' . $cn->getErrorMsg());
			throw new Lumine_SQLException($cn, $sql, $cn->getErrorMsg());
			return false;
		}
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::num_rows()
	 */
	public function num_rows()
	{
		// por algum motivo, depois de iterar pelos itens
		// o resultset nao volta ao inicio, por isso nao podemos
		// chamar o count pelo result set, e nao encontrei na documentacao
		// do PHP uma funcao equivalente a mysql_num_rows para firebird
		// por isso contamos os resultados pelo numero de itens
		// no array do dataset atual
		return count($this->getDataset());
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::affected_rows()
	 */
	public function affected_rows()
	{
		$cn = $this->getConnection();
		if( empty($cn) )
		{
			throw new Lumine_Dialect_Exception('Conexao nao setada');
		}
		return $cn->affected_rows();
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::moveNext()
	 */
	public function moveNext()
	{
		$this->pointerList[$this->getObjectId()]++;
		if($this->pointerList[$this->getObjectId()] >= $this->num_rows())
		{
			$this->pointerList[$this->getObjectId()] = $this->num_rows() - 1;
		}
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::movePrev()
	 */
	public function movePrev()
	{
		$this->pointerList[$this->getObjectId()]--;
		if($this->pointerList[$this->getObjectId()] < 0)
		{
			$this->pointerList[$this->getObjectId()] = 0;
		}
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::moveFirst()
	 */
	public function moveFirst()
	{
		$this->pointerList[$this->getObjectId()] = 0;
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::moveLast()
	 */
	public function moveLast()
	{
		$this->pointerList[$this->getObjectId()] = $this->num_rows() - 1;
		if($this->pointerList[$this->getObjectId()] < 0)
		{
			$this->pointerList[$this->getObjectId()] = 0;
		}
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::fetch_row()
	 */
	public function fetch_row($rowNumber)
	{
		$list = &$this->datasetList[$this->getObjectId()];
		
		if( empty($list[ $rowNumber ]))
		{
			return false;
		}
		$this->setPointer($rowNumber);
		
		return $list[ $rowNumber ] ;
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::fetch()
	 */
	public function fetch()
	{
		$list = &$this->datasetList[$this->getObjectId()];
		if( ! empty($list[ $this->pointerList[$this->getObjectId()] ]))
		{
			Lumine_Log::debug( 'Retornando linha: '.$this->pointerList[$this->getObjectId()]);
			$row = $list[ $this->pointerList[$this->getObjectId()]];
			$this->pointerList[$this->getObjectId()]++;
			
			return $row;
		}

		Lumine_Log::debug( 'Nenhum resultado para o cursor '.$this->pointerList[$this->getObjectId()]);
		$this->moveFirst();
		return false;
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::getErrorMsg()
	 */
	public function getErrorMsg()
	{
		if($this->getConnection() == null)
		{
			throw new Lumine_Dialect_Exception('Conex�o n�o setada');
		}
		return $this->getConnection()->getErrorMsg();
	}

	/**
	 * 
	 * @see ILumine_Dialect::getDataset()
	 */
	public function getDataset()
	{
		$data = empty($this->datasetList[$this->getObjectId()]) ? array() : $this->datasetList[$this->getObjectId()];
		return $data;
	}
	/**
	 * 
	 * @see ILumine_Dialect::setDataset()
	 */
	public function setDataset(array $dataset)
	{
		$this->datasetList[$this->getObjectId()] = $dataset;
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::getPointer()
	 */
	public function getPointer()
	{
		return $this->pointerList[$this->getObjectId()];
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::setPointer()
	 */
	public function setPointer($pointer)
	{
		$this->pointerList[$this->getObjectId()] = $pointer;
	}
	
	/**
	 * 
	 * @see ILumine_Dialect::getLumineType()
	 */
	public function getLumineType($nativeType)
	{
		// inteiros
		if(preg_match('@^(int|integer|longint|mediumint)$@i', $nativeType))
		{
			return 'int';
		}
		// textos longos
		if(preg_match('@^(text|mediumtext|tinytext|longtext|enum)$@i', $nativeType))
		{
			return 'text';
		}
		// booleanos
		if(preg_match('@^(tinyint|boolean|bool)$@i', $nativeType))
		{
			return 'boolean';
		}
		return $nativeType;
	}
	
	/**
	 * Retorna o ultimo ID da tabela para campos auto-increment
	 * @author Hugo Ferreira da Silva
	 * @param string $campo Nome do campo da tabela de auto-increment
	 * @return int Valor da ultima insercao
	 */
	public function getLastId( $campo )
	{
		//////////////////////////////////////////////////////////////////
		// GAMBIARRA FORTE!
		// Aqui pegamos as triggers relacionadas a tabela
		// e procuramos no corpo da trigger uma linha semelhante a
		// new.nome_campo = gen_id(nome_sequencia, 1)
		// para pegarmos o nome da sequencia e consequentemente
		// podermos recuperar o ultimo valor
		//////////////////////////////////////////////////////////////////
		
		$cn = $this->getConnection();
		
		$sql = "SELECT RDB\$TRIGGER_SOURCE AS triggers FROM RDB\$TRIGGERS
				 WHERE (RDB\$SYSTEM_FLAG IS NULL
					OR RDB\$SYSTEM_FLAG = 0)
				   AND RDB\$RELATION_NAME='".$this->getTablename()."'";
		
		$rs = $cn->executeSQL($sql);
		
		while( $row = ibase_fetch_assoc($rs, IBASE_FETCH_BLOBS) )
		{
			// oba! achamos o lance
			$exp = '@new\.'.$campo.'\s+=\s+gen_id\((\w+)@is';
			$res = preg_match($exp, trim($row['TRIGGERS']), $reg);
			
			if( $res )
			{
				ibase_free_result($rs);
				$sql = "SELECT GEN_ID(".$reg[1].", 0) as lastid FROM RDB\$DATABASE";
				$rs = $cn->executeSQL($sql);
				
				$row = ibase_fetch_row($rs);
				ibase_free_result($rs);
				
				return $row[0];
			}
		}
		
		ibase_free_result($rs);
		return 0;
	}
	
	
	/**
	 * Limpa o resultado armazenado para o objeto atual
	 * 
	 * @author Hugo Ferreira da Silva
	 * @return void
	 */
	public function freeResult($resultID){
		if( isset($this->resultList[ $resultID ]) && is_resource($this->resultList[ $resultID ]) ){
			Lumine_Log::debug('Liberando o registro #' . $resultID);
			ibase_free_result($this->resultList[ $resultID ]);
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see lib/Dialect/ILumine_Dialect#freeAllResults()
	 */
	public function freeAllResults(){
		foreach($this->resultList as $id => $result) {
			$this->freeResult($id);
		}
	}
	
	protected function setResultset($rs){
		$this->resultList[$this->getObjectId()] = $rs;
	}
	
	protected function getResultset(){
		return $this->resultList[$this->getObjectId()];
	}
	
}

