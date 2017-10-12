<?php 
require_once('../includes/config.php');
if(!$user->is_logged_in()){ header('Location: ../login/'); }


	if(isset($_POST['submit'])){
		
		

		extract($_POST);

		//very basic validation
		if($memberID ==''){
			$error[] = 'Your change is not saved. Something went worng.';
		}

		if(!isset($error)){ 
			
			try {

				
				$hashedpassword = $user->password_hash($password, PASSWORD_BCRYPT);

				//insert into database
				$stmt = $db->prepare("UPDATE user SET username = :email, email = :email, password = :password WHERE memberID = :memberID");
				
				$stmt->execute(array(
					':email' 		=> $email, 
					':password' 		=> $hashedpassword,
					':memberID' 		=> $memberID					
 				));
 				
 				$message = "Account changes saved";
				header('Location: ../account.php?msg=ok');
				exit;


			
				//var_dump($_POST);

			} catch(PDOException $e) {
			    echo $e->getMessage();
			}


		}
	}
	
		
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}

	?>