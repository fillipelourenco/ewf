<?    
	$lumineConfig = array(
        'dialect' => 'PostgreSQL',
        'database' => 'ewf',
        'user' => 'postgres',
        'password' => '123456',
        'port' => '5432',
        'host' => 'localhost',
        'class_path' => dirname(dirname(__FILE__)),
        'package' => 'model',
     
           
        'options' => array(
            'schema_name' => 'public',
            'generate_files' => '1',
            'generate_zip' => '',
            'class_sufix' => '',
            'remove_count_chars_start' => '',
            'remove_count_chars_end' => '',
            'remove_prefix' => '',
            'create_entities_for_many_to_many' => '',
            'plural' => 's',
            'many_to_many_style' => '',
            'create_controls' => '',
            'xml_validation_path' => '/www/xml_validators',
            'php_validator_path' => '/www/custom_validators'
        )
    );
?>