<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Bus Arrival Alert</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f9; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f9; padding:20px;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; overflow:hidden;">

                    <!-- Header -->
                    <tr>
                        <td style="background:#2563eb; padding:20px; text-align:center; color:white;">
                            <h2 style="margin:0;">🚌 Bus Arrival Alert</h2>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px; color:#333;">
                            <p>Hello Parent,</p>

                            <p>
                                The bus for <strong>{{ $child->name ?? 'your child' }}</strong>
                                is approaching the stop:
                            </p>

                            <p style="font-size:18px; font-weight:bold; color:#2563eb;">
                                {{ $stop->name }}
                            </p>

                            <p>
                                Estimated arrival time:
                                <strong>{{ round($eta) }} minutes</strong>
                            </p>

                            <p>
                                Please prepare your child for pickup.
                            </p>

                            <div style="text-align:center; margin-top:25px;">
                                <a href="{{ url('/trips/'.$trip->id.'/map') }}"
                                    style="background:#2563eb; color:white; padding:12px 25px;
                   text-decoration:none; border-radius:5px; display:inline-block;">
                                    Track Bus Live
                                </a>
                            </div>

                            <p style="margin-top:30px; font-size:12px; color:#777;">
                                This is an automated message from the School Transport System.
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