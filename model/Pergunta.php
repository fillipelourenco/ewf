<?php
class Pergunta extends Lumine_Base{
    protected $_tablename = 'perguntas';
    protected $_package   = 'model';
    protected function _initialize() {
        $this->_addField('id_pergunta', 'id_pergunta', 'tinyint', 4, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
		$this->_addField('titulo', 'titulo', 'text', null, array());		
		$this->_addField('tipo', 'tipo', 'int', 10, array());
		$this->_addField("id_componente", "id_componente", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_componente', 'class' => 'Componente'));
		$this->_addField("id_formulario", "id_formulario", "int", 10, array('notnull' => false, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_formulario', 'class' => 'Formulario'));
    }
}
?>