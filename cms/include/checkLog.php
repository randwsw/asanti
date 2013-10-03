					
					
					<?php 
					if(!session_id())
					{
					    session_start();
					} 
					if(isset($_SESSION['login']) && $_SESSION['status'] == "adm") {

						// $login = $_SESSION['login'];
// 						
						// $conn=mysqli_connect("serwer1309748.home.pl","serwer1309748_04","9!c3Q9","serwer1309748_04");
						// // ENCODING TO UTF8
						// $sql = "SET NAMES 'utf8'";
						// !mysqli_query($conn,$sql);		
// 																				
						// if (mysqli_connect_errno())
							// {
								// echo "Failed to connect to MySQL: " . mysqli_connect_error();
							// }
// 																				
// 																				
// 																				
						// $result = mysqli_query($conn,"SELECT log FROM control WHERE log = '$login'");
// 																					
						// while($row1 = mysqli_fetch_array($result))
							// {
								// $log = $row1['log'];
							// }
						// mysqli_close($conn);
// 						
						// if($log == $login){
							// echo("CONFIRMED");
						// }else{
							// echo("BREAKING BAD");
						// }
					}else{
						// echo($_SESSION['login']);
						header("Location: login.php");
					}
				?>