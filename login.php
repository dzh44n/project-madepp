
<?php
require 'function.php';

// Cek login
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $cekdatabase = mysqli_query(
        $conn,
        "SELECT * FROM login
         WHERE email='$email' AND password='$password'"
    );

    if (mysqli_num_rows($cekdatabase) > 0) {
        $_SESSION['log'] = 'True';
        header('location:index.php');
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}

if (isset($_SESSION['log'])) {
    header('location:index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login | StockBarang</title>

    <link href="css/styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Arial, sans-serif;
            background: linear-gradient(135deg, #eaf2ff, #f8fbff);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
        }

        .login-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: 100%;
            max-width: 1000px;
            min-height: 570px;
            background: white;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(28, 65, 130, 0.15);
        }

        .welcome-panel {
            background: linear-gradient(145deg, #183b78, #10254e);
            color: white;
            padding: 55px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand {
            font-size: 25px;
            font-weight: 700;
            margin-bottom: 45px;
        }

        .brand i {
            margin-right: 10px;
            color: #7eb6ff;
        }

        .welcome-panel h1 {
            font-size: 34px;
            line-height: 1.3;
            margin: 0 0 18px;
        }

        .welcome-panel h1 span {
            color: #8fc2ff;
        }

        .welcome-panel > p {
            color: #d7e5ff;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 14px 0;
        }

        .feature-icon {
            width: 45px;
            height: 45px;
            flex-shrink: 0;
            border-radius: 12px;
            background: rgba(91, 155, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
            color: #a9d0ff;
        }

        .feature strong {
            display: block;
            margin-bottom: 5px;
        }

        .feature small {
            color: #d0def5;
        }

        .form-panel {
            padding: 55px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
            border-radius: 20px;
            background: #eaf2ff;
            color: #2468d8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }

        .form-panel h2 {
            text-align: center;
            font-size: 34px;
            color: #142d55;
            margin: 0 0 10px;
        }

        .subtitle {
            text-align: center;
            color: #7b8ba7;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            color: #203656;
            font-weight: 600;
            margin-bottom: 9px;
        }

        .input-wrapper {
            display: flex;
            align-items: center;
            border: 1px solid #cedcf0;
            border-radius: 10px;
            padding: 0 15px;
            transition: 0.2s;
        }

        .input-wrapper:focus-within {
            border-color: #3278e5;
            box-shadow: 0 0 0 3px rgba(50, 120, 229, 0.12);
        }

        .input-wrapper i {
            color: #8092ae;
        }

        .input-wrapper input {
            width: 100%;
            border: none;
            outline: none;
            padding: 16px 12px;
            font-size: 15px;
            background: transparent;
            color: #183052;
        }

        .input-wrapper input:focus {
            box-shadow: none;
        }

        .toggle-password {
            border: none;
            background: transparent;
            color: #8092ae;
            cursor: pointer;
            padding: 8px;
        }

        .login-button {
            width: 100%;
            border: none;
            border-radius: 10px;
            padding: 16px;
            margin-top: 5px;
            background: linear-gradient(90deg, #2465d5, #347fe9);
            color: white;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(36, 101, 213, 0.25);
        }

        .error-message {
            background: #fff0f0;
            color: #c62828;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .footer {
            margin-top: 35px;
            text-align: center;
            color: #8392aa;
            font-size: 12px;
        }

        @media (max-width: 700px) {
            body {
                padding: 15px;
            }

            .login-container {
                grid-template-columns: 1fr;
            }

            .welcome-panel {
                padding: 30px;
            }

            .brand {
                margin-bottom: 25px;
            }

            .welcome-panel h1 {
                font-size: 27px;
            }

            .form-panel {
                padding: 35px 25px;
            }
        }
    </style>
</head>

<body>

<div class="login-container">

    <section class="welcome-panel">

        <div class="brand">
            <i class="fa-solid fa-box-open"></i>
            StockBarang
        </div>

        <h1>
            Selamat Datang<br>
            di <span>StockBarang</span>
        </h1>

        <p>
            Kelola stok barang dengan mudah,
            cepat, dan efisien.
        </p>

        <div class="feature">
            <div class="feature-icon">
                <i class="fa-solid fa-cubes"></i>
            </div>
            <div>
                <strong>Pantau Stok</strong>
                <small>Cek jumlah barang dengan mudah</small>
            </div>
        </div>

        <div class="feature">
            <div class="feature-icon">
                <i class="fa-solid fa-chart-column"></i>
            </div>
            <div>
                <strong>Catat Transaksi</strong>
                <small>Barang masuk dan keluar</small>
            </div>
        </div>

        <div class="feature">
            <div class="feature-icon">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <strong>Akses Aman</strong>
                <small>Untuk pengguna terdaftar</small>
            </div>
        </div>

    </section>

    <section class="form-panel">

        <div class="form-icon">
            <i class="fa-solid fa-box"></i>
        </div>

        <h2>Login</h2>

        <p class="subtitle">
            Masukkan email dan password Anda<br>
            untuk melanjutkan.
        </p>

        <?php if (isset($error)) { ?>
            <div class="error-message">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php } ?>

        <form method="post" action="">

            <div class="form-group">
                <label for="inputEmail">Email address</label>

                <div class="input-wrapper">
                    <i class="fa-regular fa-envelope"></i>

                    <input
                        type="email"
                        name="email"
                        id="inputEmail"
                        placeholder="name@example.com"
                        autocomplete="username"
                        required>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword">Password</label>

                <div class="input-wrapper">
                    <i class="fa-solid fa-lock"></i>

                    <input
                        type="password"
                        name="password"
                        id="inputPassword"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required>

                    <button
                        type="button"
                        class="toggle-password"
                        onclick="togglePassword()"
                        aria-label="Tampilkan atau sembunyikan password">
                        <i class="fa-regular fa-eye"
                           id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button
                type="submit"
                name="login"
                class="login-button">
                <i class="fa-solid fa-right-to-bracket"></i>
                &nbsp; Login
            </button>

        </form>

        <div class="footer">
            Sistem Informasi Stock Barang
        </div>

    </section>

</div>

<script>
function togglePassword() {
    const password = document.getElementById('inputPassword');
    const eyeIcon = document.getElementById('eyeIcon');

    if (password.type === 'password') {
        password.type = 'text';
        eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        password.type = 'password';
        eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>

</body>
</html>