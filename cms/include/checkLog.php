					<?php 
					if(!session_id()){
						session_start();
					} 
					if(isset($_SESSION['log']) && $_SESSION['status'] == "adm") {
						// header("Location: login.php");
					}else{
						
					}
				?>