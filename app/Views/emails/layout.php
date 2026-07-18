<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'AlumniNexus Notification') ?></title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f4f7;
            color: #51545e;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }
        .email-wrapper {
            width: 100%;
            margin: 0;
            padding: 0;
            background-color: #f4f4f7;
        }
        .email-content {
            width: 100%;
            margin: 0;
            padding: 0;
        }
        .email-masthead {
            padding: 25px 0;
            text-align: center;
            background-color: #ffffff;
            border-bottom: 1px solid #eaeaec;
        }
        .email-masthead_name {
            font-size: 24px;
            font-weight: bold;
            color: #333333;
            text-decoration: none;
        }
        .email-body {
            width: 100%;
            margin: 0;
            padding: 0;
            border-top: 1px solid #eaeaec;
            border-bottom: 1px solid #eaeaec;
            background-color: #ffffff;
        }
        .email-body_inner {
            width: 570px;
            margin: 0 auto;
            padding: 45px;
            background-color: #ffffff;
        }
        .email-footer {
            width: 570px;
            margin: 0 auto;
            padding: 0;
            text-align: center;
        }
        .email-footer p {
            color: #a8aaaf;
            font-size: 12px;
            padding: 20px 0;
            margin: 0;
        }
        h1 {
            margin-top: 0;
            color: #333333;
            font-size: 22px;
            font-weight: bold;
            text-align: left;
        }
        p {
            margin-top: 0;
            color: #51545e;
            font-size: 16px;
            line-height: 1.5em;
            text-align: left;
        }
        .btn {
            display: inline-block;
            background-color: #3869d4;
            color: #ffffff;
            text-decoration: none;
            border-radius: 3px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
            -webkit-text-size-adjust: none;
            box-sizing: border-box;
            padding: 10px 18px;
        }
        .otp-code {
            display: inline-block;
            background-color: #f4f4f7;
            padding: 15px 30px;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 5px;
            border-radius: 5px;
            margin: 20px 0;
            color: #333333;
        }
    </style>
</head>
<body>
    <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <!-- Email Header -->
                    <tr>
                        <td class="email-masthead">
                            <a href="<?= base_url() ?>" class="email-masthead_name">
                                AlumniNexus
                            </a>
                        </td>
                    </tr>
                    <!-- Email Body -->
                    <tr>
                        <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="content-cell">
                                        <?= $this->renderSection('content') ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Email Footer -->
                    <tr>
                        <td>
                            <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="content-cell" align="center">
                                        <p>&copy; <?= date('Y') ?> AlumniNexus. All rights reserved.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
