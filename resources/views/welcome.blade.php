<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KostWangi</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #080808;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 15px;
        }

        .container {
            width: 100%;
            max-width: 470px;
            border: 1px solid #333;
            border-radius: 15px;
            overflow: hidden;
            padding-bottom: 20px;
        }

        .banner {
            height: 245px;
            background-image: url('/images/backgroundkost.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .banner h1 {
            font-size: 30px;
            line-height: 1.4;
            text-transform: uppercase;
        }

        .content {
            padding: 10px 28px;
        }

        .content h2 {
            font-size: 19px;
            margin-bottom: 8px;
        }

        .description {
            font-size: 12px;
            color: #bbb;
            margin-bottom: 22px;
        }

        .label {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 13px;
            text-align: center;
            text-decoration: none;
            color: white;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .login {
            background: #2563eb;
        }

        .register {
            background: transparent;
            border: 1px solid #333;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="banner">
           
        </div>

        <div class="content">

            <h2>Selamat Datang di KostWangi</h2>

            <p class="description">
                Platform pengelolaan kost yang memudahkan
                pengelola dan penghuni.
            </p>

            <p class="label">Sudah memiliki akun?</p>

            <a href="{{ route('login') }}" class="btn login">
                Masuk / Login
            </a>
            <p class="label">Belum memiliki akun?</p>

            <a href="{{ route('register') }}" class="btn register">
                Daftar / Register
            </a>
        </div>

    </div>

</body>

</html>
