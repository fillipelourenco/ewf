<?php
class Resposta extends Lumine_Base{
    protected $_tablename = 'respostas';
    protected $_package   = 'model';
    protected function _initialize() {
        $this->_addField('id_resposta', 'id_resposta', 'tinyint', 4, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
		$this->_addField('resposta', 'resposta', 'text', null, array());		
		$this->_addField("id_pergunta", "id_pergunta", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_pergunta', 'class' => 'Pergunta'));
		$this->_addField("id_usuario", "id_usuario", "int", 10, array('notnull' => false, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_usuario', 'class' => 'Usuario'));
    }
}
?>