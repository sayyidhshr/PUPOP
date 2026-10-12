<?php
session_start();

if (isset($_SESSION['sudahlogin']) && $_SESSION['sudahlogin'] == true) {
  header("Location: ../Home/home.php");
  exit();
}

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  if ($email == "" || $password == "") {
    $pesan = "Harap isi Email dan Password terlebih dahulu!";
  } else {
    $file = "../users.json";

    if (file_exists($file)) {
      $data = file_get_contents($file);
      $users = json_decode($data, true);
      $userDitemukan = false;
      foreach ($users as $user) {
        if ($user['email'] == $email && $user['password'] == $password) {
          $_SESSION['sudahlogin'] = true;
          $_SESSION['username'] = $user['name'];
          $_SESSION['email'] = $user['email'];
          $userDitemukan = true;
          header("Location: ../Role/role.php");
          exit();
        }
      }

      if (!$userDitemukan) {
        $pesan = "Email atau Password salah atau tidak ditemukan!";
      }

    } else {
      $pesan = "Data pengguna belum tersedia!";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Sign In</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body,
    html {
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

    .login-card {
      background-color: #ffffff;
      width: 420px;
      padding: 45px 40px;
      border-radius: 24px;
      box-shadow: 0px 10px 40px rgba(0, 0, 0, 0.2);
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .header-img {
      width: 160px;
      object-fit: contain;
      margin-bottom: 30px;
    }

    .form-group {
      width: 100%;
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      color: #333333;
      margin-bottom: 8px;
    }

    .form-group input {
      width: 100%;
      padding: 14px 16px;
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

    .forgot-password {
      display: block;
      text-align: right;
      font-size: 12px;
      color: #31C2CD;
      text-decoration: none;
      margin-top: -10px;
      margin-bottom: 25px;
      font-weight: 500;
    }

    .forgot-password:hover {
      text-decoration: underline;
    }

    .btn-signin {
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
      margin-bottom: 25px;
    }

    .btn-signin:hover {
      background-color: #2BAEBA;
    }

    .btn-signin:active {
      transform: scale(0.97);
    }

    .divider {
      font-size: 12px;
      color: #888888;
      margin-bottom: 20px;
      position: relative;
    }

    .social-login {
      display: flex;
      gap: 15px;
      margin-bottom: 30px;
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

    .signup-text {
      font-size: 12px;
      color: #888888;
    }

    .signup-text a {
      color: #31C2CD;
      text-decoration: none;
      font-weight: 600;
    }

    .signup-text a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>

<div class="cyan-bg"></div>

<div class="wrapper">

  <div class="login-card">

    <img src="../Signin/img/Group%20116.png"
         alt="Sign In Header"
         class="header-img"
         style="padding-left: 20px;">

    <form action="signin.php" method="post" style="width: 100%;">

      <div class="form-group">
        <label for="email">Email</label>

        <input
          type="email"
          id="email"
          name="email"
          placeholder="example@gmail.com"
          required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>

        <input
          type="password"
          id="password"
          name="password"
          placeholder="********"
          required>
      </div>

      <a href="#"
         class="forgot-password"
         onclick="lupaPassword()">
        Forgot Password?
      </a>

      <button
        type="submit"
        class="btn-signin">
        Sign In
      </button>

    </form>

    <?php if ($pesan != "") { ?>
      <div style="
                    color: #e74c3c;
                    font-size: 12px;
                    text-align: center;
                    margin-top: -10px;
                    margin-bottom: 15px;
                ">
        <?php echo $pesan; ?>
      </div>
    <?php } ?>

    <div class="divider">
      Or sign in with
    </div>

    <div class="social-login">

      <button
        class="social-btn"
        onclick="loginSosmed('Apple')">

        <i class="fab fa-apple"></i>

      </button>

      <button
        class="social-btn"
        onclick="loginSosmed('Google')">

        <i class="fab fa-google"></i>

      </button>

      <button
        class="social-btn"
        onclick="loginSosmed('Facebook')">

        <i class="fab fa-facebook"></i>

      </button>

    </div>

    <div class="signup-text">

      Don't have an account?

      <a href="#" onclick="signUp()">
        Sign Up
      </a>

    </div>

  </div>

</div>

<script>

  function signUp() {
    window.location.href = '../Signup/signup.php';
  }

  function loginSosmed(platform) {
    alert("Mengarahkan ke halaman login " + platform + "...");
  }

  function lupaPassword() {
    alert("Mengarahkan ke halaman reset password...");
  }

</script>

</body>

</html>
