<?php
class Usuario extends Lumine_Base{
    protected $_tablename = 'usuarios';
    protected $_package   = 'model';
    protected function _initialize() {
        $this->_addField('id_usuario', 'id_usuario', 'tinyint', 4, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField('nome', 'nome', 'varchar', 64, array());
        $this->_addField('tipo', 'tipo', 'varchar', 64, array());
        $this->_addField('email', 'email', 'varchar', 64, array());
        $this->_addField('login', 'login', 'varchar', 512, array());
        $this->_addField('senha', 'senha', 'varchar', 512, array());
		$this->_addField("id_empresa", "id_empresa", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_empresa', 'class' => 'Empresa'));
		$this->_addField("id_cliente", "id_cliente", "int", 10, array('notnull' => false, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_cliente', 'class' => 'Cliente'));
		
		$this->_addForeignRelation("usuarios_permissoes", self::ONE_TO_MANY, "Permissao", "id_usuario", null, null, null);
		$this->_addForeignRelation("usuarios_tarefas1", self::ONE_TO_MANY, "Tarefa", "id_usuario_requisitante", null, null, null);
		$this->_addForeignRelation("usuarios_tarefas2", self::ONE_TO_MANY, "Tarefa", "id_usuario_responsavel", null, null, null);
		$this->_addForeignRelation("usuarios_tarefas3", self::ONE_TO_MANY, "Tarefa", "id_usuario_cadastro", null, null, null);
    }
}
?>