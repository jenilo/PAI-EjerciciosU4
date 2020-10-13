<?php
	include "controllers/userController.php";
	$userController = new UserController();

	$users = $userController->get();
	//echo $_SESSION['token'];
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Bootstrap</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link href="recursos/fontawesome/css/all.css" rel="stylesheet">
</head>
<body>
	<div class="container">

		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<a class="navbar-brand" href="#">Navbar</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active">
						<a class="nav-link" href="#">Inicio</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Link</a>
					</li>
				</ul>
				<form class="form-inline my-2 my-lg-0">
					<input class="form-control mr-sm-2" type="search" placeholder="Buscar..." aria-label="Search">
					<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
				</form>
			</div>
		</nav>

		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page">Inicio</li>
			</ol>
		</nav>

		<!-- notificacion -->
		<?php if (isset($_SESSION['status']) && $_SESSION['status']=="success"): ?>
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<strong>Correcto!</strong> <?= $_SESSION['message'] ?>.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		<?php unset($_SESSION['status']); ?>
		<?php endif ?>

		<?php if (isset($_SESSION['status']) && $_SESSION['status']=="error"): ?>
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Error!</strong> <?= $_SESSION['message'] ?>.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		<?php unset($_SESSION['status']); ?>
		<?php endif ?>


		<div class="row">
			<div class="col-12">
				<div class="card mb-4">
					<div class="card-header">
						Lista usuarios registrados

						<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#staticBackdrop">Añadir usuario</button>
					</div>
					<div class="card-body">
						<table class="table">
						  <thead class="thead-dark">
						    <tr>
						      <th scope="col">#</th>
						      <th scope="col">Nombre</th>
						      <th scope="col">Correo electrónico</th>
						      <th scope="col">Estatus</th>
						      <th scope="col">Acciones</th>
						    </tr>
						  </thead>
						  <tbody>
						  	<?php if(isset($users) && count($users)>0): ?>
						  	<?php foreach ($users as $user): ?>
							    <tr>
							      <th scope="row"><?= $user['id'] ?></th>
							      <td><?= $user['name'] ?></td>
							      <td><a href="mailto:<?= $user['email'] ?>"><?= $user['email'] ?></a></td>
							      <td>
							      	<?php if ($user['status']): ?>
							      		<span class="badge badge-success">Activo</span>
							      	<?php else: ?>
							      		<span class="badge badge-danger">Inactivo</span>
							      	<?php endif ?>
							      </td>
							      <td>
							      	<button data-toggle="modal" data-target="#staticBackdrop" data-info='<?= json_encode($user) ?>' type="button" class="btn btn-warning" onclick="editar(this)">
							      		<i class="fa fa-pencil-alt"></i>Editar
							    	</button>
							      	<button type="button" onclick="remove(<?= $user['id'] ?>,this)" class="btn btn-danger">
							      		<i class="fa fa-trash"></i>Eliminar
							    	</button>
							  	  </td>
							    </tr>
							<?php endforeach ?>
							<?php endif ?>
						  </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		
	</div>

	<!-- Modal -->
	<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg">

	    <div class="modal-content">
	    	<div class="modal-header">
	      		<h5 class="modal-title" id="staticBackdropLabel">Agregar Usuario</h5>
	        	<button onclick="add()" type="button" class="close" data-dismiss="modal" aria-label="Close">
	        		<span aria-hidden="true">&times;</span>
	        	</button>
	    	</div>
	    	<form id="form" method="POST" action="users" onsubmit="return validateRegister()">
	    		<div class="modal-body">
	    			<!-- NOMBRE COMPLETO -->
	    			<div class="form-group">
					    <label for="name">Nombre Completo</label>
					    <div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">
									<i class="fa fa-user"></i>
								</span>
							</div>
							<input type="text" class="form-control" id="name" name="name" placeholder="Juan Perez" aria-label="Nombre" aria-describedby="basic-addon1" required>
						</div>
					    <small id="emailHelp" class="form-text text-muted">No ingresar números.</small>
					</div>

					<!-- correo -->
					<div class="form-group">
					    <label for="email">Correo Electronico</label>
					    <div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">
									<i class="fa fa-envelope"></i>
								</span>
							</div>
							<input type="text" class="form-control" id="email" name="email" placeholder="Juanito@domain.com" aria-label="emailHelp" aria-describedby="basic-addon1" required>
						</div>
					</div>

					<!-- contraseña -->
					<div class="form-group">
					    <label for="email">Contraseña</label>
					    <div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">
									<i class="fa fa-lock"></i>
								</span>
							</div>
							<input type="password" id="password" name="password" class="form-control" placeholder="* * * * *" aria-label="email" aria-describedby="basic-addon1" minlength="4" required>
						</div>
					</div>

					<!-- contraseña verificar -->
					<div class="form-group">
					    <label for="email">Verificar contraseña</label>
					    <div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">
									<i class="fa fa-lock"></i>
								</span>
							</div>
							<input type="password" id="password2" class="form-control" placeholder="* * * * *" aria-label="email" aria-describedby="basic-addon1"  minlength="4" required>
						</div>
					</div>


		    	</div>
		    	<div class="modal-footer">
		    		<button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
		    		<button type="submit" class="btn btn-primary">Guardar</button>
		    		<input type="hidden" name="action" id="action" value="store">
		    		<input type="hidden" name="id" id="id" value="id">
		    		<input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
		    	</div>
	    	</form>
	    </div>

	  </div>
	</div>

	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script type="text/javascript">

		function editar(target){
			var info = $(target).data('info');
			$("#name").val(info.name);
			$("#email").val(info.email);
			$("#password").val(info.password);
			$("#password2").val(info.password);

			$("#id").val(info.id);
			$("#action").val('update');
		}

		function add(){
			$("#action").val("store");
			document.getElementById("form").reset();
		}

		function validateRegister(){
			if ($("#password").val() === $("#password2").val()) {
				return true;
			}
			else{
				$("#password").addClass('is-invalid');
				$("#password2").addClass('is-invalid');
				swal("", "Las contraseñas no coinciden.", "error");
				return false;
			}
			
		}
		function remove(id,target){

			swal({
				title: "",
				text: "¿Desea eliminar el usuario?",
				icon: "warning",
				buttons: true,
				dangerMode: true,
				buttons: ["Cancelar","Eliminar"]
			})
			.then((willDelete) => {

				if (willDelete) {
					$.ajax({
					    url : 'users',
					    data : {action: 'remove', id:id, token: "<?= $_SESSION['token'] ?>"},
					    type : 'POST',
					    dataType : 'json',
					    success : function(json) {
					        //console.log(json);
					        if(json.status == "success"){
						       swal("Usuario eliminado exitosamente.", {
									icon: "success",
								});
						       $(target).parent().parent().remove();
						       //console.log($(target).parent().parent());
						    } else {
						    	swal(json.message, {
									icon: "error",
								});
						    }
					    },
					    error : function(xhr, status) {
					        console.log(xhr);
					        console.log(status);
					    }
					});

				} else {
					//swal("Your imaginary file is safe!");
				}
			});

		}

	</script>
</body>
</html>