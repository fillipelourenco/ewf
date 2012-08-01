<?php
class Formulario extends Lumine_Base{
    protected $_tablename = 'formularios';
    protected $_package   = 'model';
    protected function _initialize() {
        $this->_addField('id_formulario', 'id_formulario', 'tinyint', 4, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
		$this->_addField('chave', 'chave', 'varchar', 20, array());
		$this->_addField('descricao', 'descricao', 'text', null, array());		
		$this->_addField('status', 'status', 'boolean', null, array());		
		$this->_addField("id_projeto", "id_projeto", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_projeto', 'class' => 'Projeto'));
		$this->_addField("id_versao", "id_versao", "int", 10, array('notnull' => false, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_versao', 'class' => 'Versao'));
    }
}
?>