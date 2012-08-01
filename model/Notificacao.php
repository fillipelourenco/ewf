<?php
class Notificacao extends Lumine_Base{
    protected $_tablename = 'notificacoes';
    protected $_package   = 'model';
    protected function _initialize() {
        $this->_addField('id_notificacao', 'id_notificacao', 'tinyint', 4, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
		
		$this->_addField('titulo', 'titulo', 'varchar', 100, array());
		$this->_addField('mensagem', 'mensagem', 'text', null, array());
		$this->_addField('situacao', 'situacao', 'boolean', null, array());
		$this->_addField('momento_envio', 'momento_envio', 'timestamp', null, array());
		
		$this->_addField("id_usuario_remetente", "id_usuario_remetente", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_usuario', 'class' => 'Usuario'));
		$this->_addField("id_usuario_destinatario", "id_usuario_destinatario", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_usuario', 'class' => 'Usuario'));
		
    }
}
?>