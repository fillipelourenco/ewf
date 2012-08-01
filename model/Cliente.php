<?php
class Cliente extends Lumine_Base{
    protected $_tablename = 'clientes';
    protected $_package   = 'model';
    protected function _initialize() {
        $this->_addField('id_cliente', 'id_cliente', 'tinyint', 4, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField('razao_social', 'razao_social', 'varchar', 50, array());
        $this->_addField('nome_curto', 'nome_curto', 'varchar', 20, array());
        $this->_addField('responsavel', 'responsavel', 'varchar', 25, array());
		$this->_addField("id_empresa", "id_empresa", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_empresa', 'class' => 'Empresa'));
		
		$this->_addForeignRelation("usuarios_fk_cliente", self::ONE_TO_MANY, "Usuario", "id_cliente", null, null, null);
    }
}
?>