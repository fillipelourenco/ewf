<?php
class Tarefa extends Lumine_Base{
    protected $_tablename = 'tarefas';
    protected $_package   = 'model';
    protected function _initialize() {
        $this->_addField('id_tarefa', 'id_tarefa', 'tinyint', 4, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
		
		$this->_addField('situacao', 'situacao', 'int', 10, array());
		$this->_addField('chave', 'chave', 'varchar', 100, array());

		$this->_addField("id_rotulo", "id_rotulo", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_rotulo', 'class' => 'Rotulo'));
		$this->_addField("id_componente", "id_componente", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_componente', 'class' => 'Componente'));
		$this->_addField('titulo', 'titulo', 'varchar', 100, array());
		$this->_addField('descricao', 'descricao', 'text', null, array());
		$this->_addField('prazo', 'prazo', 'date', null , array());
		$this->_addField("id_usuario_requisitante", "id_usuario_requisitante", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_usuario', 'class' => 'Usuario'));
		$this->_addField("id_usuario_responsavel", "id_usuario_responsavel", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_usuario', 'class' => 'Usuario'));
		$this->_addField('estimativa', 'estimativa', 'varchar', 100, array());
		$this->_addField('dependencia', 'dependencia', 'varchar', 100, array());
		$this->_addField('pai', 'pai', 'varchar', 100, array());
		
		$this->_addField('pasta', 'pasta', 'varchar', 100, array());
				
		$this->_addField('momento_cadastro', 'momento_cadastro', 'datetime', null , array());
		$this->_addField("id_projeto", "id_projeto", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_projeto', 'class' => 'Projeto'));
		$this->_addField("id_usuario_cadastro", "id_usuario_cadastro", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_usuario', 'class' => 'Usuario'));
		
		$this->_addField("id_empresa", "id_empresa", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_empresa', 'class' => 'Empresa'));
		
		$this->_addForeignRelation('fk_vt_versao', self::MANY_TO_MANY, 'Versao', 'id_tarefa', 'versoes_tarefas', 'id_tarefa', null);
    }
}
?>