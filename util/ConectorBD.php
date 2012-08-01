<? 
	$db = pg_connect('host=localhost port=5432 dbname=ewf user=postgres password=123456');
	pg_set_client_encoding($db, LATIN1);
?>