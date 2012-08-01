<?php
class LogTarefa extends Lumine_Base{
    protected $_tablename = 'logs_tarefas';
    protected $_package   = 'model';
    protected function _initialize() {
        $this->_addField('id_log_tarefa', 'id_log_tarefa', 'tinyint', 4, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
		$this->_addField('chave', 'chave', 'varchar', 64, array());
		$this->_addField("id_usuario", "id_usuario", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_usuario', 'class' => 'Usuario'));
		$this->_addField("id_empresa", "id_empresa", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_empresa', 'class' => 'Empresa'));
		$this->_addField('situacao_anterior', 'situacao_anterior', 'int', 10, array());
		$this->_addField('situacao_atual', 'situacao_atual', 'int', 10, array());
		$this->_addField('momento_alteracao', 'momento_alteracao', 'timestamp', null, array());
    }
}
?>