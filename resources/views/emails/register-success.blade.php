<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Account Registration Successful</title>

    <style>
        body {
            background: #f4f4f7;
            font-family: Arial, sans-serif;
            padding: 0;
            margin: 0;
        }

        .email-container {
            max-width: 600px;
            background: #ffffff;
            margin: 30px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            color: #4e73df;
            margin-bottom: 10px;
        }

        p {
            font-size: 15px;
            color: #444444;
            line-height: 1.6;
        }

        .info-box {
            background: #f1f5ff;
            padding: 18px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #4e73df;
        }

        .info-box strong {
            display: inline-block;
            width: 130px;
            color: #333;
        }

        .warning {
            margin-top: 20px;
            padding: 15px;
            background: #fff8e6;
            border-left: 4px solid #ffb300;
            border-radius: 8px;
            font-size: 14px;
            color: #8a6d3b;
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="email-container">

        <h2>🎉 Welcome to PlayFullOutings!</h2>

        <p>Hello <strong>{{ $username }}</strong>,</p>

        <p>Your account has been successfully created. Below are your login details:</p>

        <div class="info-box">
            <p><strong>Username:</strong> {{ $username }}</p>
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Password:</strong> {{ $password }}</p>
        </div>

        <div class="warning">
            ⚠️ <strong>Important Security Notice:</strong>
            Please do not share your login information with anyone.
            Keep your email and password confidential to protect your account.
        </div>

        <p>Thank you for joining PlayFullOutings.
            We hope you have a wonderful experience!</p>

        <div class="footer">
            — PlayFullOutings Team —
        </div>

    </div>

</body>

</html>