<?	
	if (isset($_GET['logout'])) {
		session_start();
		$_SESSION = array();
		session_destroy();
		echo "<meta http-equiv=\"Refresh\" content=\"1;URL=http://".$_SERVER['HTTP_HOST']."/login.php\">";
		exit("Saindo...");
	}
?>