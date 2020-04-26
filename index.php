<!DOCTYPE html>
<html>
    <head>
        <title>Poker Odds Calculator</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    	<link rel="stylesheet" type="text/css" href="styles/index.css">
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico"/>
    </head>
    <body>
        <nav class="navbar navbar-dark bg-dark navbar-expand-sm">
            <a href="index.php" class="navbar-brand">Poker Odds Calculator</a>
        </nav>

        <div class="container">
		<div class="row justify-content-center">
			<div class="col-6 mt-5 mx-5">
				<div class="card align-text-center">
					<div class="card-header">
						<h3 class="text-center"><b>Log In</b></h3>
					</div>
					<div class="card-body">
						<form action="includes/login.inc.php" method="post">
							<div class="form-row">
								<div class="form-group col">
									<label for="username">Username</label>
									<input type="text" class="form-control" name="username" placeholder="Username">
								</div>
							</div>
							<div class="form-row mt-4">
								<div class="form-group col">
									<label for="pwd">Password</label>
									<input type="password" class="form-control" name="pwd" placeholder="Password">
								</div>
							</div>
							<div class="form-row mt-4">
								<div class="form-group col align-self-center my-2">
									<button type="submit" class="btn btn-success btn-block" name="login-submit">Login</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col mt-5 mx-5">
				<div class="errors">
					<?php
						if(isset($_GET['error'])){
							if($_GET['error'] == 1){
								echo "<h3>You must fill in all fields</h3>";
							}
							if($_GET['error'] == 2){
								echo "<h3>Incorrect Login Details</h3>";
							}
							if($_GET['error'] == 3){
								echo "<h3>User Not Found</h3>";
							}
						}
					?>
				</div>
			</div>
		</div>
		<div class="row justify-content-center my-2">
			<a href="includes/register.inc.php" class="btn btn-info btn-sm" role="button">New? Create an account!</a>
		</div>
		<div class="row justify-content-center mt-1">
			<a href="includes/forgot.inc.php" class="btn btn-danger btn-sm" role="button">Forgot your Password?</a>
		</div>
	</div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>