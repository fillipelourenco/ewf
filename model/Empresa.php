<?php
class Empresa extends Lumine_Base{
    protected $_tablename = 'empresas';
    protected $_package   = 'model';
    protected function _initialize() {
        $this->_addField('id_empresa', 'id_empresa', 'tinyint', 4, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField('nome', 'nome', 'varchar', 64, array());
        $this->_addField('cidade', 'cidade', 'varchar', 64, array());
        $this->_addField('uf', 'uf', 'varchar', 64, array());
        $this->_addField('site', 'site', 'varchar', 512, array());
        $this->_addField('login', 'login', 'varchar', 512, array());
		$this->_addField('sequencia_tarefa', 'sequencia_tarefa', 'int', 10, array());
		$this->_addField('sequencia_requisicao', 'sequencia_requisicao', 'int', 10, array());
		
		$this->_addForeignRelation("projetos", self::ONE_TO_MANY, "Projeto", "id_empresa", null, null, null);
		$this->_addForeignRelation("fk_usuario_empresa", self::ONE_TO_MANY, "Usuario", "id_empresa", null, null, null);
    }
}
?>