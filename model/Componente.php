<?php
class Componente extends Lumine_Base{
    protected $_tablename = 'componentes';
    protected $_package   = 'model';
    protected function _initialize() {
        $this->_addField('id_componente', 'id_componente', 'tinyint', 4, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField('nome', 'nome', 'varchar', 64, array());
        $this->_addField('descricao', 'descricao', 'varchar', 64, array());
        $this->_addField("id_projeto", "id_projeto", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_projeto', 'class' => 'Projeto'));
		
		$this->_addForeignRelation("fk_tarefa_componente", self::ONE_TO_MANY, "Tarefa", "id_componente", null, null, null);
    }
}
?>