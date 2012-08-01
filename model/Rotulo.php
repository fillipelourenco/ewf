<?php
	class Rotulo extends Lumine_Base{
		protected $_tablename = 'rotulos';
		protected $_package   = 'model';
		protected function _initialize() {
		
			$this->_addField(
							'id_rotulo', 
							'id_rotulo', 
							'tinyint', 
							4, 
							array(
								'primary' => true, 
								'notnull' => true, 
								'autoincrement' => true
								)
							);
			$this->_addField('nome', 'nome', 'varchar', 64, array());
			$this->_addField("id_empresa", "id_empresa", "int", 10, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'id_empresa', 'class' => 'Empresa'));
			
			$this->_addForeignRelation("fk_tarefa_rotulo", self::ONE_TO_MANY, "Tarefa", "id_rotulo", null, null, null);
		}
	}
?>