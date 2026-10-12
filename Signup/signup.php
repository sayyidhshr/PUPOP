<?php
session_start();

if (isset($_SESSION['sudahlogin']) && $_SESSION['sudahlogin'] == true) {
  header("Location: ../Home/home.php");
  exit();
}

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  if ($name == "" || $email == "" || $password == "") {
    $pesan = "Harap lengkapi semua field pendaftaran!";
  } elseif (!isset($_POST['terms'])) {
    $pesan = "Anda harus menyetujui Terms & Conditions!";
  } else {
    $file = "../users.json";
    $users = json_decode(file_get_contents($file), true);
    $userSudahAda = false;

    foreach ($users as $user) {
      if ($user['email'] == $email) {
        $userSudahAda = true;
        break;
      }
    }

    if ($userSudahAda) {
      $pesan = "Email sudah terdaftar. Silakan gunakan email lain!";
    } else {
      $userBaru = [
        "name" => $name,
        "email" => $email,
        "password" => $password
      ];

      $users[] = $userBaru;
      file_put_contents(
        $file,
        json_encode($users, JSON_PRETTY_PRINT)
      );

      header("Location: ../Signin/signin.php");
      exit();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

  <meta charset="UTF-8">

  <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

  <title>Sign Up</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet">

  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


  <style>

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      width: 100%;
      height: 100%;
      background-color: #ffffff;
      font-family: 'Poppins', sans-serif;
      overflow: hidden;
      position: relative;
    }

    .cyan-bg {
      position: absolute;
      top: -65vh;
      left: -20vw;
      width: 140vw;
      height: 140vh;
      background-color: #31C2CD;
      border-radius: 50%;
      z-index: 1;
    }

    .wrapper {
      position: relative;
      z-index: 2;
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .register-card {
      background-color: #ffffff;
      width: 440px;
      padding: 40px 40px;
      border-radius: 24px;
      box-shadow: 0px 10px 40px rgba(0, 0, 0, 0.2);
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .header-img {
      width: 240px;
      object-fit: contain;
      margin-bottom: 25px;
    }

    .form-group {
      width: 100%;
      margin-bottom: 18px;
    }

    .form-group label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      color: #333333;
      margin-bottom: 6px;
    }

    .form-group input {
      width: 100%;
      padding: 13px 16px;
      background-color: #F8F9FA;
      border: none;
      border-radius: 12px;
      font-family: 'Poppins', sans-serif;
      font-size: 13px;
      color: #333;
      outline: none;
      transition: background-color 0.2s ease;
    }

    .form-group input::placeholder {
      color: #A0A0A0;
    }

    .form-group input:focus {
      background-color: #EFEFEF;
    }

    .checkbox-group {
      width: 100%;
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 22px;
    }

    .checkbox-group input[type="checkbox"] {
      width: 16px;
      height: 16px;
      accent-color: #31C2CD;
      cursor: pointer;
    }

    .checkbox-group label {
      font-size: 12px;
      color: #888888;
      user-select: none;
    }

    .checkbox-group label a {
      color: #31C2CD;
      text-decoration: none;
      font-weight: 500;
    }

    .checkbox-group label a:hover {
      text-decoration: underline;
    }

    .btn-signup {
      width: 100%;
      background-color: #31C2CD;
      color: #ffffff;
      font-size: 15px;
      font-weight: 600;
      padding: 14px;
      border: none;
      border-radius: 50px;
      cursor: pointer;
      font-family: 'Poppins', sans-serif;
      transition: transform 0.2s ease, background-color 0.2s ease;
      margin-bottom: 22px;
    }

    .btn-signup:hover {
      background-color: #2BAEBA;
    }

    .btn-signup:active {
      transform: scale(0.97);
    }

    .divider {
      font-size: 12px;
      color: #888888;
      margin-bottom: 18px;
    }

    .social-login {
      display: flex;
      gap: 15px;
      margin-bottom: 25px;
    }

    .social-btn {
      width: 45px;
      height: 45px;
      background-color: transparent;
      border: 1px solid #E0E0E0;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 20px;
      cursor: pointer;
      transition: border-color 0.2s ease, transform 0.2s ease;
    }

    .social-btn:hover {
      border-color: #31C2CD;
      transform: translateY(-2px);
    }

    .fa-apple {
      color: #000000;
    }

    .fa-google {
      background: conic-gradient(
        from -45deg,
        #ea4335 110deg,
        #4285f4 90deg 180deg,
        #34a853 180deg 270deg,
        #fbbc05 270deg
      ) 73% 55%/150% 150% no-repeat;

      -webkit-background-clip: text;
      background-clip: text;

      color: transparent;

      -webkit-text-fill-color: transparent;
    }

    .fa-facebook {
      color: #1877F2;
    }

    .signin-text {
      font-size: 12px;
      color: #888888;
    }

    .signin-text a {
      color: #31C2CD;
      text-decoration: none;
      font-weight: 600;
    }

    .signin-text a:hover {
      text-decoration: underline;
    }

  </style>

</head>

<body>

<div class="cyan-bg"></div>

<div class="wrapper">

  <div class="register-card">

    <img
      src="../Signup/img/Group 117.png"
      alt="Create Account Header"
      class="header-img"
      style="padding-left: 20px;"
    >


    <form action="signup.php" method="post" style="width: 100%;">

      <div class="form-group">

        <label for="name">
          Name
        </label>

        <input
          type="text"
          id="name"
          name="name"
          placeholder="Ahmad Zulfi"
          required
        >

      </div>


      <div class="form-group">

        <label for="email">
          Email
        </label>

        <input
          type="email"
          id="email"
          name="email"
          placeholder="example@gmail.com"
          required
        >

      </div>


      <div class="form-group">

        <label for="password">
          Password
        </label>

        <input
          type="password"
          id="password"
          name="password"
          placeholder="********"
          required
        >

      </div>


      <div class="checkbox-group">

        <input
          type="checkbox"
          id="terms"
          name="terms"
        >

        <label for="terms">

          Agree with

          <a
            href="#"
            onclick="bukaTerms(); return false;"
          >
            Terms & Conditions
          </a>

        </label>

      </div>


      <?php

      if ($pesan != "") {

        echo "
          <p style='
            color:red;
            font-size:12px;
            margin-bottom:15px;
            text-align:center;
          '>
            $pesan
          </p>";

      }

      ?>


      <button
        type="submit"
        class="btn-signup"
      >
        Sign Up
      </button>

    </form>


    <div class="divider">
      Or sign in with
    </div>


    <div class="social-login">

      <button
        class="social-btn"
        onclick="daftarSosmed('Apple')"
      >
        <i class="fab fa-apple"></i>
      </button>


      <button
        class="social-btn"
        onclick="daftarSosmed('Google')"
      >
        <i class="fab fa-google"></i>
      </button>


      <button
        class="social-btn"
        onclick="daftarSosmed('Facebook')"
      >
        <i class="fab fa-facebook"></i>
      </button>

    </div>


    <div class="signin-text">

      Already have an account?

      <a href="../Signin/signin.php">
        Sign In
      </a>

    </div>

  </div>

</div>


<script>

  function daftarSosmed(platform) {

    alert(
      "Mengarahkan pendaftaran via "
      + platform
      + "..."
    );

  }


  function bukaTerms() {

    alert(
      "Membuka halaman Syarat & Ketentuan..."
    );

  }

</script>

</body>
</html>
