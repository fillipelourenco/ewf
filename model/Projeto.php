<?php
class Projeto extends Lumine_Base{
    protected $_tablename = 'projetos';
    protected $_package   = 'model';
    protected function _initialize() {
        $this->_addField('id_projeto', 'id_projeto', 'tinyint', 4, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField('nome', 'nome', 'varchar', 64, array());
        $this->_addField('descricao', 'descricao', 'text', null, array());
        $this->_addField('login', 'login', 'varchar', 64, array());
        $this->_addField("id_empresa", "id_empresa", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_empresa', 'class' => 'Empresa'));
		
		$this->_addForeignRelation("fk_componente_projeto", self::ONE_TO_MANY, "Componente", "id_projeto", null, null, null);
		$this->_addForeignRelation("permissoes_id_projeto_fkey", self::ONE_TO_MANY, "Permissao", "id_projeto", null, null, null);
		$this->_addForeignRelation("fk_tarefa_projeto", self::ONE_TO_MANY, "Tarefa", "id_projeto", null, null, null);
		$this->_addForeignRelation("fk_risco_projeto", self::ONE_TO_MANY, "Risco", "id_projeto", null, null, null);
				
    }
}
?>