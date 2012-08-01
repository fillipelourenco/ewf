<?php
/**
 * Geracao de formularios basicos para Lumine
 * 
 * @package Lumine_Form
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br
 */

// carrega a interface
Lumine::load('Form_IForm');

/**
 * Geracao de formularios basicos para Lumine
 * 
 * @package Lumine_Form
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br
 */
class Lumine_Form_BasicForm extends Lumine_EventListener implements ILumine_Form
{
	/**
	 * Objeto a ser usado
	 * @var Lumine_Base
	 */
    private $obj;
    /**
     * flag para saber se eh para remover as contra-barras dos valores
     * @var boolean
     */
    private $strip_slashes;
    /**
     * Pasta onde estao os arquivos de template
     * @var string
     */
    private $template = '/lib/Templates/basic/';
    // estas variaveis deverao ser alteradas no template, conforme o gosto
    /**
     * Label de auto-incrementavel
     * @var string
     */
    private $autoincrement_string   = '[ Campo auto-incrementavel ]';
    /**
     * Label do calendario
     * @var string
     */
    private $calendar_string        = '[ Calendario ]';

    /**
     * Construtor
     *
     * @param Lumine_Base $obj Objeto Lumine_Base para montar o formulario
     * @author Hugo Ferreira da Silva
     * @return ILumine_Form
     */
    function __construct(Lumine_Base $obj = null)
    {
        $this->obj = $obj;
        $this->strip_slashes = get_magic_quotes_gpc();
        if(function_exists('date_default_timezone_set')){
        	date_default_timezone_set('America/Sao_paulo');
        }
    }

    /**
     * 
     * @see ILumine_Form::createForm()
     */
    public function createForm( $action = null )
    {
        if(empty($action))
        {
            $action = $_SERVER['PHP_SELF'];
        }
        
        $def = $this->obj->_getObjectPart('_definition');
        foreach($def as $name => $prop)
        {
            if(empty($prop['options']['label']))
            {
                $prop['options']['label'] = ucfirst($name);
            }
            $def[ $name ] = $prop;
        }
        
        ob_start();
        require_once LUMINE_INCLUDE_PATH . $this->template . 'edit_form.php';
        
        $form = ob_get_contents();
        ob_end_clean();
        
        return $form;
    }

    /**
     * 
     * @see ILumine_Form::getInputFor()
     */
    public function getInputFor($nome)
    {
        $def = $this->obj->_getField($nome);
        if(empty($def['options']['foreign']))
        {
            switch( $def['type'] )
            {
                case 'int':
                case 'float':
                case 'decimal':
                case 'integer':
                    if( !empty($def['options']['autoincrement']))
                    {
                        $field = $this->autoincrement_string;
                        $field .= '<input type="hidden" name="'.$def['name'].'" value="'.@$_POST[ $def['name'] ].'" />';
                        
                        return $field;
                    } else {
                        $field = '<input type="text" name="'.$def['name'].'" value="'.@$_POST[ $def['name'] ].'" />';
                        return $field;
                    }
                break;
                
                case 'text':
                case 'mediumtext':
                case 'longtext':
                    $field = '<textarea name="'.$def['name'].'" cols="30" rows="4">'.@$_POST[ $def['name'] ].'</textarea>';
                    return $field;
                break;
                
                case 'boolean':
                    $field = '<input type="radio" name="'.$def['name'].'" value="1"';
                    if( !empty($_POST[$def['name']]))
                    {
                        $field .= ' checked="checked"';
                    }
                    $field .= ' /> Sim ';
                    
                    $field .= '<input type="radio" name="'.$def['name'].'" value="0"';
                    if( isset($_POST[$def['name']]) && $_POST[ $def['name'] ] == '0')
                    {
                        $field .= ' checked="checked"';
                    }
                    $field .= ' /> N�o ';
                    
                    return $field;
                break;
                
                case 'date':
                case 'datetime':
                    $field = '<input id="'.$def['name'].'" type="text" name="'.$def['name'].'" value="'.@$_POST[$def['name']].'"';
                    $field .= ' size="10"';
                    $field .= ' /> ';
                    $field .= $this->getCalendarFor($nome);
                    return $field;
                break;
                
                case 'varchar':
                case 'char':
                default:
                    $field = '<input type="text" name="'.$def['name'].'" value="'.@$_POST[$def['name']].'"';
                    if( !empty($def['length']))
                    {
                         $length = $def['length'];
                         if($length > 50)
                         {
                            $length = 50;
                         }
                        $field .= ' size="'.$length.'" maxlength="'.$def['length'].'"';
                    }
                    $field .= ' />';
                    return $field;
                break;
            }
        } else {
            $this->obj->_getConfiguration()->import( $def['options']['class'] );

            $cls = new $def['options']['class'];
            $cls->_setAlias('cls');
            
            $pklist = $cls->_getPrimaryKeys();
            $first = array_shift($pklist);
            
            $label = $first['name'];
            if( !empty($def['options']['displayField']))
            {
                $label = $def['options']['displayField'];
            }
            
            $cls->order('cls.' . $label . ' ASC');
            $cls->find();
            
            $combo = '<select name="'.$def['name'].'" id="'.$def['name'].'">';
            $combo .= '<option value=""></option>';
            
            while($cls->fetch())
            {
                $combo .= '<option value="'.$cls->fieldValue($first['name']).'"';
                if(@$_POST[ $def['name'] ] == $cls->fieldValue($first['name']))
                {
                    $combo .= ' selected="selected"';
                }
                $combo .= '>' . $cls->$label;
                $combo .= '</option>'.PHP_EOL;
            }
            $combo .= '</select>';
            
            return $combo;
        }
    }
    
    /**
     * 
     * @see ILumine_Form::getCalendarFor()
     */
     public function getCalendarFor($name)
     {
        return str_replace('{name}', $name, $this->calendar_string);
     }
     
     /**
      * 
      * @see ILumine_Form::showList()
      */
    public function showList($offset, $limit, $formAction = null, $fieldSort = null, $order = null)
    {
        if(is_null($formAction))
        {
            $formAction = $_SERVER['PHP_SELF'];
        }
        
        $def = $this->obj->_getObjectPart('_definition');
        foreach($def as $name => $prop)
        {
            if(empty($prop['options']['label']))
            {
                $prop['options']['label'] = ucfirst($name);
            }
            $def[ $name ] = $prop;
        }
        
        $obj = $this->obj;
        $obj->reset();
        $obj->alias('o');
        
        // aplicando os filtros (podemos filtrar por qualquer campo)
        reset($def);
        $rel = 0;
        $pre = 'r';
        
        foreach($def as $name => $prop)
        {
            if( !empty($prop['options']['foreign']) && !empty($prop['options']['displayField']))
            {
                $class = new $prop['options']['class'];
                $obj->join($class->alias($pre.($rel++)),'LEFT');
                $obj->select($class->_getAlias().'.' . $prop['options']['displayField'] .' as '.$name.'_'.$prop['options']['displayField']);
                $obj->selectAs();
                
                if(!empty($_GET[$name.'_filter_']))
                {
                    $obj->where($class->_getAlias().'.'.$prop['options']['displayField'].' like ?', $_GET[$name.'_filter_']);
                }
                
            } else {
                $obj->select('o.'.$name);
                if(array_key_exists($name.'_filter_', $_GET) && $_GET[$name.'_filter_'] !== '')
                {
                    $obj->where('o.'.$name.' like ?', $_GET[$name.'_filter_']);
                }
            }
        }

        $total = $obj->count();
        $obj->limit($offset, $limit);
        $obj->find();
        
        $list = $obj->allToArray();
        
        ob_start();
        require_once LUMINE_INCLUDE_PATH . $this->template . 'edit_list.php';
        
        $form = ob_get_contents();
        ob_end_clean();
        
        return $form;
    }
    
    /**
     * 
     * @see ILumine_Form::handleAction()
     */
    public function handleAction($actionName, array $values)
    {
        switch($actionName)
        {
            case 'save':
                return $this->save( $values );
            break;
            
            case 'insert':
                return $this->insert( $values );
            break;
            
            case 'delete':
                return $this->delete( $values );
            break;
            
            case 'edit':
                $obj = $this->obj;
                $pks = $obj->_getPrimaryKeys();
                $obj->reset();
                //$obj->_setAlias('o');
                
                foreach($pks as $pk)
                {
                    $obj->where('{'.$pk['name'].'} = ?', !isset($values['_pk_' . $pk['name']]) ? '' : $values['_pk_' . $pk['name']]);
                }
                
				if($obj->find( true ) > 0) {
					$_POST = $obj->toArray();

					$list = $obj->_getObjectPart('_foreign');
					
					foreach($list as $name => $item){
						$itens = $obj->getLink($name);
						
						foreach($itens as $ref){
							$pk = $ref->_getPrimaryKeys();
							if(!empty($pk)){
								$_POST[$name][] = $ref->$pk[0]['name'];
							}
						}
					}
                }
                
            break;
        }
        return false;
    }
    
   /**
    * 
    * @see ILumine_Form::getControlTemplate()
    */
    public function getControlTemplate(Lumine_Configuration $cfg, $className )
    {
        $file = LUMINE_INCLUDE_PATH . $this->template . 'control.txt';
        if( !file_exists($file))
        {
            Lumine_Log::error('O arquivo "'.$file.'" n�o existe!');
            exit;
        }
        
        $content = file_get_contents($file);
        $content = str_replace('{class_path}', str_replace('\\','/',$cfg->getProperty('class_path')), $content);
        $content = str_replace('{entity_name}', $className, $content);
        $content = str_replace('{LUMINE_PATH}', LUMINE_INCLUDE_PATH, $content);
        
        return $content;
    }
    
    /**
     * 
     * @see ILumine_Form::getTop()
     */
    public function getTop()
    {
        include_once LUMINE_INCLUDE_PATH . $this->template . 'topo.php';
    }
    
    /**
     * 
     * @see ILumine_Form::getFooter()
     */
    public function getFooter()
    {
    }
    
    /**
     * Atualiza os dados do objeto no banco de dados
     *
     * @author Hugo Ferreira da Silva
     * @link http://www.hufersil.com.br/
     * @param array $values Valores vindos do banco de dados
     * @return boolean 
     */
    private function save( $values )
    {
        // pega a lista de chaves primarias e seus valores originais
        // porque em alguns casos a pessoa podera mudar as chaves primarias
        $obj = $this->obj;
        $obj->reset();
        //$obj->_setAlias('o');
        
        $pk_list = $obj->_getPrimaryKeys();
        foreach($pk_list as $pk)
        {
            // condicao para atualizar
            $obj->where('{'.$pk['name'] .'} = ?', $values['_pk_' . $pk['name'] ]);
        }
        
        // pega os valores da matriz
        $def = $obj->_getObjectPart('_definition');
        foreach($def as $name => $prop)
        {
            if( !empty($prop['options']['foreign']) && empty($values[ $name ]))
            {
                $obj->setFieldValue($name, null);
            } else {
                if($this->strip_slashes)
                {
	                $obj->setFieldValue($name, stripcslashes($values[$name]));
                } else {
	                $obj->setFieldValue($name, $values[$name]);
                }
            }
        }
        
        // atualiza as referencias MTM
        $def = $obj->_getObjectPart('_foreign');
        
        foreach($def as $name => $prop){
        	if($prop['type'] == Lumine_Base::MANY_TO_MANY){
        		$obj->removeAll($name);
        		if(!empty($values[$name])){
        			$obj->$name = $values[$name];
        		}
        	}
        }
        
        // atualiza (pelo menos tenta)
        $obj->save( true );
        return true;
    }
    
    /**
     * Remove registros 
     * 
     * @author Luiz Fernando M. de Carvalho
     * @param array $values
     * @return boolean
     */
    private function delete( $values )
    {
        // pega a lista de chaves primarias e seus valores originais
        // porque em alguns casos a pessoa podera mudar as chaves primarias
        $obj = $this->obj;
        $obj->reset();
        //$obj->_setAlias('o');

        $pk_list = $obj->_getPrimaryKeys();
        foreach($pk_list as $pk)
        {
            // condicao para atualizar
            $obj->where('{'.$pk['name'] .'} = ?', $values['_pk_' . $pk['name'] ]);
        }

        // pega os valores da matriz
        $def = $obj->_getObjectPart('_definition');
        foreach($def as $name => $prop)
        {
            if( !empty($prop['options']['foreign']) && empty($values[ $name ]))
            {
                $obj->setFieldValue($name, null);
            } else {
                if($this->strip_slashes)
                {
                    $obj->setFieldValue($name, stripslashes(@$values[ $name ]));
                } else {
                    $obj->setFieldValue($name, @$values[ $name ]);
                }
            }
        }

        // deleta
        $obj->delete( true );
        return true;
    }
    
    /**
     * Insere o registro enviado no banco de dados
     *
     * @author Hugo Ferreira da Silva
     * @link http://www.hufersil.com.br/
     * @param array $values valores do formulario
     * @return array resultado da validacao
     */
    private function insert( $values )
    {
        $def = $this->obj->_getObjectPart('_definition');
        foreach($def as $name => $prop)
        {
            if( !empty($prop['options']['foreign']) && empty($values[ $name ]))
            {
                $this->obj->setFieldValue($name,null);
            } else {
                if($this->strip_slashes)
                {
	                $this->obj->setFieldValue($name, stripslashes(@$values[$name]));
                } else {
	                $this->obj->setFieldValue($name, @$values[$name]);
                }
            }
        }
        
        // limpamos as pk's que sao auto-incrementaveis
        $obj = &$this->obj;
        $pks = $obj->_getPrimaryKeys();
        
        foreach($pks as $name => $item){
        	if(!empty($item['options']['autoincrement'])){
        		$obj->$item['name'] = null;
        	}
        }
        
    	// atualiza as referencias MTM
        $def = $obj->_getObjectPart('_foreign');
        foreach($def as $name => $prop){
        	if($prop['type'] == Lumine_Base::MANY_TO_MANY){
        		$obj->removeAll($name);
        		if(!empty($values[$name])){
        			foreach($values[$name] as $id){
        				$obj->{$name}[] = $id;
        			}
        		}
        	}
        }
        
        // $this->obj->setFrom($values);
        $res = $this->obj->validate();
        
        if($res === true)
        {
            $this->obj->insert();
            return true;
        }
        return $res;
    }
    
	public function copyFiles($destination){
    	
    }
    
}



?>