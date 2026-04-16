<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reset Your Password</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased;">

    <!-- Wrapper -->
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f1f5f9;">
        <tr>
            <td align="center" style="padding: 40px 16px;">

                <!-- Main Card -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 520px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06);">

                    <!-- Header Banner -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #020617 0%, #0f172a 50%, #083344 100%); padding: 40px 40px 36px; text-align: center;">
                            <!-- Logo -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto 20px;">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #06b6d4, #0e7490); width: 44px; height: 44px; border-radius: 12px; text-align: center; vertical-align: middle;">
                                        <span style="color: #ffffff; font-weight: 800; font-size: 20px; line-height: 44px;">E</span>
                                    </td>
                                    <td style="padding-left: 12px;">
                                        <span style="color: #ffffff; font-size: 22px; font-weight: 700;">Easy </span>
                                        <span style="color: #22d3ee; font-size: 22px; font-weight: 700;">IT</span>
                                    </td>
                                </tr>
                            </table>
                            <!-- Lock Icon -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto 16px;">
                                <tr>
                                    <td style="background-color: rgba(6, 182, 212, 0.15); width: 64px; height: 64px; border-radius: 50%; text-align: center; vertical-align: middle;">
                                        <img src="https://img.icons8.com/ios-filled/50/22d3ee/lock-2.png" alt="Lock" width="28" height="28" style="display: inline-block; vertical-align: middle;" />
                                    </td>
                                </tr>
                            </table>
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700; letter-spacing: -0.3px;">
                                Password Reset Request
                            </h1>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 40px 12px;">
                            <p style="margin: 0 0 6px; color: #0f172a; font-size: 16px; font-weight: 600;">
                                Hi {{ $name ?? 'there' }},
                            </p>
                            <p style="margin: 0; color: #64748b; font-size: 14px; line-height: 22px;">
                                We received a request to reset the password for your Easy IT account. Click the button below to choose a new password.
                            </p>
                        </td>
                    </tr>

                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 28px 40px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                                <tr>
                                    <td style="border-radius: 12px; background: linear-gradient(135deg, #06b6d4, #0891b2); text-align: center;">
                                        <a href="{{ $url }}" target="_blank" style="display: inline-block; padding: 14px 40px; color: #ffffff; font-size: 14px; font-weight: 700; text-decoration: none; letter-spacing: 0.3px;">
                                            Reset My Password
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Expiry Notice -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f0fdfa; border-radius: 12px; border: 1px solid #ccfbf1;">
                                <tr>
                                    <td style="padding: 14px 20px;">
                                        <p style="margin: 0; color: #0d9488; font-size: 13px; line-height: 20px; text-align: center;">
                                            ⏱ This link expires in <strong>{{ $expireMinutes ?? 60 }} minutes</strong>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Security Note -->
                    <tr>
                        <td style="padding: 24px 40px 0;">
                            <p style="margin: 0; color: #94a3b8; font-size: 13px; line-height: 20px;">
                                If you didn't request this, you can safely ignore this email — your password will remain unchanged.
                            </p>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 28px 40px 0;">
                            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 0;" />
                        </td>
                    </tr>

                    <!-- Fallback URL -->
                    <tr>
                        <td style="padding: 20px 40px 32px;">
                            <p style="margin: 0 0 8px; color: #94a3b8; font-size: 12px;">
                                Having trouble with the button? Copy and paste this URL into your browser:
                            </p>
                            <p style="margin: 0; word-break: break-all;">
                                <a href="{{ $url }}" style="color: #0891b2; font-size: 12px; text-decoration: underline;">{{ $url }}</a>
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- End Main Card -->

                <!-- Footer -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 520px;">
                    <tr>
                        <td style="padding: 24px 16px; text-align: center;">
                            <p style="margin: 0 0 4px; color: #94a3b8; font-size: 12px;">
                                © {{ date('Y') }} Easy IT. All rights reserved.
                            </p>
                            <p style="margin: 0; color: #cbd5e1; font-size: 11px;">
                                This is an automated message — please do not reply.
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>