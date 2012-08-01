<?php
class Comentario extends Lumine_Base{
    protected $_tablename = 'comentarios';
    protected $_package   = 'model';
    protected function _initialize() {
        $this->_addField('id_comentario', 'id_comentario', 'tinyint', 4, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField('comentario', 'comentario', 'text', null, array());
		$this->_addField('momento_comentario', 'momento_comentario', 'timestamp', null, array());
		$this->_addField("id_tarefa", "id_tarefa", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_tarefa', 'class' => 'Tarefa'));
		$this->_addField("id_usuario", "id_usuario", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_usuario', 'class' => 'Usuario'));
    }
}
?>