<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Trip Delay Notification</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f9; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f9; padding:20px;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; overflow:hidden;">

                    <!-- Header -->
                    <tr>
                        <td style="background:#dc2626; padding:20px; text-align:center; color:white;">
                            <h2 style="margin:0;">⚠ Trip Delay Notice</h2>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px; color:#333;">
                            <p>Hello Parent,</p>

                            <p>
                                We would like to inform you that the bus trip:
                            </p>

                            <p style="font-size:18px; font-weight:bold; color:#dc2626;">
                                {{ $trip->name }}
                            </p>

                            <p>
                                has been delayed due to:
                            </p>

                            <p style="background:#fef2f2; padding:15px; border-left:4px solid #dc2626;">
                                {{ $reason ?? 'traffic or operational reasons' }}
                            </p>

                            <p>
                                Our team is working to resolve the issue as quickly as possible.
                            </p>

                            <div style="text-align:center; margin-top:25px;">
                                <a href="{{ url('/trips/'.$trip->id.'/map') }}"
                                    style="background:#dc2626; color:white; padding:12px 25px;
                   text-decoration:none; border-radius:5px; display:inline-block;">
                                    View Trip Status
                                </a>
                            </div>

                            <p style="margin-top:30px; font-size:12px; color:#777;">
                                Thank you for your patience and understanding.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f1f1f1; padding:15px; text-align:center; font-size:12px; color:#888;">
                            © {{ date('Y') }} School Transport System. All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>