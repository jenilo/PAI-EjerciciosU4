<?php
	include_once "config.php";
	include_once "conection.php";

	if (isset($_POST) && isset($_POST['action'])) {
		$UserController = new UserController();

		switch ($_POST['action']) {
			case 'store':
				$name = strip_tags($_POST['name']);
				$email = strip_tags($_POST['email']);
				$password = strip_tags($_POST['password']);

				$UserController->store($name,$email,$password);
				break;
		}
	}

	Class UserController{
		function get(){
			$conn = connect();
			if (!$conn->connect_error) {
				$query = "SELECT * FROM users";
				$prepared_query = $conn->prepare($query);
				$prepared_query->execute();

				$results = $prepared_query->get_result();
				$users = $results->fetch_all(MYSQLI_ASSOC);

				if($users)
					return $users;
				else 
					return array();
			}
			else{
				return array();
			}
		}
		public function store($name,$email,$password){
			$conn = connect();
			if (!$conn->connect_error) {
				if($name!="" && $email!="" && $password!=""){
					$query = "INSERT INTO users (name,email,password) values(?,?,?)";
					$prepared_query = $conn->prepare($query);
					$prepared_query->bind_param('sss',$name,$email,$password);
					if ($prepared_query->execute()) {
						$_SESSION['status'] = "success";
						$_SESSION['message'] = "El registro se ha guardado correctamente.";
						header('Location: '.$_SERVER['HTTP_REFERER']);
					}
					else{
						$_SESSION['status'] = "error";
						$_SESSION['message'] = "El registro no se ha guardado.";
						header('Location: '.$_SERVER['HTTP_REFERER']);
					}
				}
				else{
					$_SESSION['status'] = "error";
					$_SESSION['message'] = "Verifique la información enviada.";
					header('Location: '.$_SERVER['HTTP_REFERER']);	
				}
			}
			else{
				$_SESSION['status'] = "error";
				$_SESSION['message'] = "Error durante la conexión.";
				header('Location: '.$_SERVER['HTTP_REFERER']);
			}
		}
	}

?>