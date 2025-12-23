<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Kata Sandi</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f3f4f6;
            color: #374151;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 24px 0;
        }
        .logo {
            height: 64px;
            width: auto;
        }
        .card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 32px;
            margin-bottom: 24px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #111827;
        }
        .button-wrapper {
            text-align: center;
            margin: 32px 0;
        }
        .button {
            display: inline-block;
            background-color: #1f2937; /* gray-800 */
            color: white !important;
            font-weight: 600;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: background-color 0.2s;
        }
        .button:hover {
            background-color: #111827; /* gray-900 */
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            margin-top: 24px;
        }
        .link-break {
            word-break: break-all;
            color: #059669;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path('images/logo-rs.png')) }}" alt="Logo RS" class="logo">
            <!-- If embedding fails, we can try asset but email clients block external images often. Embedding is better for local files. -->
            <!-- Note: message->embed needs the $message variable available in the view. -->
            <!-- If using MailMessage->view(), $message is usually passed automatically. -->
        </div>

        <div class="card">
            <h1 class="greeting">Yth. Pengguna SIM SOP,</h1>
            
            <p>Anda menerima email ini karena kami menerima permintaan atur ulang kata sandi untuk akun Anda.</p>
            
            <div class="button-wrapper">
                <a href="{{ $url }}" class="button">Atur Ulang Kata Sandi</a>
            </div>
            
            <p>Tautan atur ulang kata sandi ini akan kedaluwarsa dalam 60 menit.</p>
            
            <p>Jika Anda tidak meminta atur ulang kata sandi, tidak ada tindakan lebih lanjut yang diperlukan.</p>
            
            <p style="margin-top: 24px;">
                Hormat kami,<br>
                <strong>Tim IT RSUP Ngoerah</strong>
            </p>
        </div>

        <div class="footer">
            <p>Jika Anda mengalami kendala saat menekan tombol "Atur Ulang Kata Sandi", salin dan tempel tautan di bawah ini ke peramban web Anda:</p>
            <p><a href="{{ $url }}" class="link-break">{{ $url }}</a></p>
            
            <p style="margin-top: 24px;">&copy; {{ date('Y') }} RSUP Prof. I.G.N.G. Ngoerah. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</body>
</html>
