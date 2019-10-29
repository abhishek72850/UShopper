<?php

	   define('DB_SERVER', 'localhost');
   	define('DB_USERNAME', 'root');
   	define('DB_PASSWORD', '');
   	define('DB_DATABASE', 'ushopper');
   	
   	class Database{

   		private $db;

   		function __construct(){
   			$this->db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
   		}

   		public function getDbConnection(){
   			return $this->db;
   		}

         public function closeDb(){
            mysqli_close($this->db);
         }
   	}
?>
