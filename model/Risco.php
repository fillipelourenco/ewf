<?php
class Risco extends Lumine_Base{
    protected $_tablename = 'riscos';
    protected $_package   = 'model';
    protected function _initialize() {
        $this->_addField('id_risco', 'id_risco', 'tinyint', 4, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
		$this->_addField('tipo', 'tipo', 'character varying', 20, array());
		$this->_addField('probabilidade', 'probabilidade', 'character varying', 20, array());
		$this->_addField('efeito', 'efeito', 'character varying', 20, array());
        $this->_addField('risco', 'risco', 'text', null, array());
        $this->_addField('estrategia', 'estrategia', 'text', null, array());
        $this->_addField("id_projeto", "id_projeto", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_projeto', 'class' => 'Usuario'));
    }
}
?>