<?php
   	
   	class SubDatabase{

   		private $db;

   		function __construct(){
   			$this->db = mysqli_connect("localhost","root","","ushopper");


   		}

   		public function getDbConnection(){
   			return $this->db;
   		}

         public function closeDb(){
            mysqli_close($this->db);
         }
   	}
?>
