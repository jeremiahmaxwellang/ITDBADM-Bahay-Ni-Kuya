<!DOCTYPE html>
<html>
<head>
    <title>Register Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('../assets/images/pbb house.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
        }

        .top_left_title {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 40px;
            font-family: 'Times New Roman', serif;
            color: CornflowerBlue;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .top_left_title img {
            height: 40px;
            margin-right: 15px;
        }

        .register_container {
            background-color: white;
            padding: 30px;
            border-radius: 30px;
            width: 550px;
            margin: 150px auto;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .register_subtitle {
            text-align: center;
            font-size: 22px;
            margin-bottom: 10px;
        }

        .register_separator {
            height: 2px;
            background-color: #ccc;
            margin: 20px 0;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .register_name_row {
            display: flex;
            gap: 15px;
            width: 100%;
            margin-bottom: 15px;
        }

        .register_name_column {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .register_label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .register_input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .register_field_group {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .register_button {
            width: 200px;
            padding: 12px;
            margin-top: 15px;
            background-color: yellow;
            color: black;
            font-size: 16px;
            font-family: 'Times New Roman', serif;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .register_button:hover {
            background-color: gold;
        }

        .register_prompt {
            margin-top: 15px;
            text-align: center;
        }

        .register_prompt a {
            color: CornflowerBlue;
            text-decoration: none;
        }

        .register_prompt a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="top_left_title">
        <img src="../assets/images/pbb logo.png" alt="Logo">
        BAHAY NI KUYA
    </div>

    <div class="register_container">
        <h2 class="register_subtitle">REGISTER YOUR <span style="color: CornflowerBlue;">BAHAY NI KUYA</span> ACCOUNT</h2>
        <div class="register_separator"></div>

        <form method="post" action="">
            <div class="register_name_row">
                <div class="register_name_column">
                    <label for="first_name" class="register_label">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="register_input" required>
                </div>
                <div class="register_name_column">
                    <label for="last_name" class="register_label">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="register_input" required>
                </div>
            </div>

            <div class="register_field_group">
                <label for="email" class="register_label">Email</label>
                <input type="email" id="email" name="email" class="register_input" required>
            </div>

            <div class="register_field_group">
                <label for="password" class="register_label">Enter Password</label>
                <input type="password" id="password" name="password" class="register_input" required>
            </div>

            <div class="register_field_group">
                <label for="confirm_password" class="register_label">Re-enter Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="register_input" required>
            </div>

            <button type="submit" class="register_button">Register Account</button>

            <p class="register_prompt">
                Already have an account? <a href="login.php">Login</a>
            </p>
        </form>
    </div>

</body>
</html>
