<?php
session_start();
include 'dbconnect.php';

if(isset($_SESSION['role'])){
	header("location:stock");
}

if(isset($_GET['pesan'])){
	if($_GET['pesan'] == "gagal"){
		echo "Username atau Password salah!";
	}else if($_GET['pesan'] == "logout"){
		echo "Anda berhasil keluar dari sistem";
	}else if($_GET['pesan'] == "belum_login"){
		echo "Anda harus Login";
	}else if($_GET['pesan'] == "noaccess"){
		echo "Akses Ditutup";
	}
}

if(isset($_POST['btn-login']))
{
	$uname = mysqli_real_escape_string($conn,$_POST['username']);
	$upass = mysqli_real_escape_string($conn,md5($_POST['password']));

	// menyeleksi data user dengan username dan password yang sesuai
	$login = mysqli_query($conn,"select * from slogin where username='$uname' and password='$upass';");
	// menghitung jumlah data yang ditemukan
	$cek = mysqli_num_rows($login);

	// cek apakah username dan password di temukan pada database
	if($cek > 0){

		$data = mysqli_fetch_assoc($login);

		if($data['role']=="stock"){
			// buat session login dan username
			$_SESSION['user'] = $data['nickname'];
			$_SESSION['user_login'] = $data['username'];
			$_SESSION['id'] = $data['id'];
			$_SESSION['role'] = "stock";
			header("location:stock");

		}else{
			header("location:index.php?pesan=gagal");
		}
	}
}

if(isset($_POST['btn-register'])){
    $username = mysqli_real_escape_string($conn,$_POST['reg-username']);
    $password = mysqli_real_escape_string($conn, md5($_POST['reg-password']));
    $confirmPassword = mysqli_real_escape_string($conn, md5($_POST['confirm-password']));
    $nickname = mysqli_real_escape_string($conn, $_POST['reg-nickname']);
    $role = mysqli_real_escape_string($conn, $_POST['reg-role']);

    // Periksa apakah password dan konfirmasi password sama
    if($password != $confirmPassword){
        echo "Password dan Konfirmasi Password tidak cocok";
    }else{
        // Periksa apakah username sudah ada di database
        $checkUsernameQuery = "SELECT * FROM slogin WHERE username='$username'";
        $checkUsernameResult = mysqli_query($conn, $checkUsernameQuery);
        if(mysqli_num_rows($checkUsernameResult) > 0){
            echo "Username sudah digunakan, silakan pilih username lain";
        }else{
            // Simpan data registrasi ke dalam database
            $registerQuery = "INSERT INTO slogin (username, password, nickname, role) VALUES ('$username', '$password', '$nickname', '$role')";
            if(mysqli_query($conn, $registerQuery)){
                echo "Registrasi berhasil";
                // Redirect ke halaman login setelah berhasil registrasi
                header("location: index.php");
            }else{
                echo "Terjadi kesalahan saat registrasi";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login Mega Storage</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-144808195-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-144808195-1');
	</script>
	<script src="jquery.min.js"></script>
	<style>
		body{background-image:url("bg.jpg");}
		@media screen and (max-width: 600px) {
			h4{font-size:85%;}
		}

		
	</style>
	<link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
	<div class="mt-2" align="center">
		<img src="logo.png" width="50%" style="margin-top:5%" />
		<br /><br />
		<div class="container  mt-4">
			<form method="post">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Username" name="username" autofocus>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" placeholder="Password" name="password">
				</div>
				<button type="submit" class="btn btn-primary" name="btn-login">Masuk</button>
			</form>
			<div class="mt-3" style="color:white">
				<p>Belum punya akun? <a href="#registrationModal" data-toggle="modal">Registrasi disini</a></p>
			</div>
			<br />
		</div>
	</div>

	<!-- Registration Modal -->
	<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="registrationModalLabel">Registrasi</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
				<form method="post">
					<div class="form-group">
						<label for="reg-username">Username</label>
						<input type="text" class="form-control" id="reg-username" name="reg-username" required>
					</div>
					<div class="form-group">
						<label for="reg-password">Password</label>
						<input type="password" class="form-control" id="reg-password" name="reg-password" required>
					</div>
					<div class="form-group">
						<label for="confirm-password">Konfirmasi Password</label>
						<input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
					</div>
					<div class="form-group">
						<label for="reg-nickname">Nickname</label>
						<input type="text" class="form-control" id="reg-nickname" name="reg-nickname" required>
					</div>
					<div class="form-group">
						<label for="reg-role">Role</label>
						<input type="text" class="form-control" id="reg-role" name="reg-role" value="stock" readonly>
					</div>
					<button type="submit" class="btn btn-primary" name="btn-register">Daftar</button>
				</form>

				</div>
			</div>
		</div>
	</div>
</body>
</html>
