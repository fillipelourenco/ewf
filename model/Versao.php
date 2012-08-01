<?php
class Versao extends Lumine_Base{
    protected $_tablename = 'versoes';
    protected $_package   = 'model';
    protected function _initialize() {
        $this->_addField('id_versao', 'id_versao', 'serial', null, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
		$this->_addField('master_version', 'master_version', 'integer', null, array());
		$this->_addField('great_version', 'great_version', 'integer', null, array());
		$this->_addField('average_version', 'average_version', 'integer', null, array());
        $this->_addField('small_version', 'small_version', 'integer', null, array());
        $this->_addField('descricao', 'descricao', 'text', null, array());
        $this->_addField('data_cadastro', 'data_cadastro', 'date', null, array());
		$this->_addField("id_projeto", "id_projeto", "serial", null, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_projeto', 'class' => 'Projeto'));
		
		$this->_addForeignRelation('fk_vt_tarefa', self::MANY_TO_MANY, 'Tarefa', 'id_versao', 'versoes_tarefas', 'id_versao', null);
		
    }
}
?>