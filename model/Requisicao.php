<?php
class Requisicao extends Lumine_Base{
    protected $_tablename = 'requisicoes';
    protected $_package   = 'model';
    protected function _initialize() {
        $this->_addField('id_requisicao', 'id_requisicao', 'tinyint', 4, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
		
		$this->_addField('chave', 'chave', 'varchar', 20, array());
		$this->_addField('tipo', 'tipo', 'int', 10, array());
		$this->_addField('situacao', 'situacao', 'varchar', 100, array());
		$this->_addField('titulo', 'titulo', 'varchar', 100, array());
		$this->_addField('descricao', 'descricao', 'text', null, array());
		
		$this->_addField("id_projeto", "id_projeto", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_projeto', 'class' => 'Projeto'));
		$this->_addField("id_componente", "id_componente", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_componente', 'class' => 'Componente'));
		$this->_addField("id_usuario_cadastro", "id_usuario_cadastro", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_usuario', 'class' => 'Usuario'));
		$this->_addField("id_usuario_alteracao", "id_usuario_alteracao", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_usuario', 'class' => 'Usuario'));
		$this->_addField("id_cliente", "id_cliente", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_cliente', 'class' => 'Cliente'));
		
		$this->_addField('pasta', 'pasta', 'varchar', 100, array());
		
		$this->_addField('momento_cadastro', 'momento_cadastro', 'datetime', null , array());
		$this->_addField('momento_alteracao', 'momento_alteracao', 'datetime', null , array());
		
    }
}
?>