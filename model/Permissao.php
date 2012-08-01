<?php
class Permissao extends Lumine_Base{
    protected $_tablename = 'permissoes';
    protected $_package   = 'model';
    protected function _initialize() {
        $this->_addField('id_permissao', 'id_permissao', 'tinyint', 4, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField("id_usuario", "id_usuario", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_usuario', 'class' => 'Usuario'));
		$this->_addField("id_projeto", "id_projeto", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_projeto', 'class' => 'Projeto'));		
		$this->_addField('integra', 'integra', 'boolean', null, array());
    }
}
?>