<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kode OTP Reset Kata Sandi - Buana Group</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f6f9;font-family:'Plus Jakarta Sans',Arial,sans-serif;color:#111c2d;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:32px 16px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.06);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#004429,#005e3a);padding:24px 28px;color:#ffffff;">
                            <div style="font-size:18px;font-weight:700;letter-spacing:0.3px;">BUANA GROUP</div>
                            <div style="font-size:12px;opacity:0.85;margin-top:4px;">PT Buana Sejahtera Semarang • Yayasan Wahid Hasyim</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 12px;font-size:15px;">Halo, <strong>{{ $user->name }}</strong></p>
                            <p style="margin:0 0 20px;font-size:14px;line-height:1.6;color:#3f4942;">
                                Kami menerima permintaan untuk mereset kata sandi akun Buana Group Anda. Gunakan kode OTP berikut untuk melanjutkan:
                            </p>
                            <div style="background-color:#f0f3ff;border:1px dashed #005e3a;border-radius:12px;padding:18px;text-align:center;margin:0 0 20px;">
                                <div style="font-size:11px;text-transform:uppercase;letter-spacing:1.2px;color:#006c49;font-weight:700;margin-bottom:6px;">Kode Verifikasi OTP</div>
                                <div style="font-size:32px;font-weight:800;letter-spacing:8px;color:#004429;">{{ $otp }}</div>
                            </div>
                            <p style="margin:0 0 12px;font-size:13px;line-height:1.5;color:#3f4942;">
                                Kode ini berlaku selama <strong>{{ $expiryMinutes }} menit</strong>. Demi keamanan akun Anda, <strong>jangan bagikan kode ini kepada siapa pun</strong>, termasuk pihak yang mengaku dari Buana Group.
                            </p>
                            <p style="margin:0;font-size:12px;color:#6f7a71;">
                                Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#f9f9ff;padding:16px 28px;border-top:1px solid #e7eeff;font-size:11px;color:#6f7a71;text-align:center;">
                            &copy; {{ date('Y') }} PT Buana Sejahtera Semarang. Hak Cipta Dilindungi.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
