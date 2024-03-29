<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">
        /* CLIENT-SPECIFIC STYLES */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        /* RESET STYLES */
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
        .btn:not(:disabled):not(.disabled) {
            cursor: pointer;
        }
        [type=reset], [type=submit], button, html [type=button] {
            -webkit-appearance: button;
        }
        .btn-primary {
            color: #fff;
            background-color: #3d98d1;
            border-color: #3d98d1;
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        button, select {
            text-transform: none;
        }
        button, input {
            overflow: visible;
        }
        button, input, optgroup, select, textarea {
            margin: 0;
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
        }

        /* MOBILE STYLES */
        @media screen and (max-width:600px) {
            h1 {
                font-size: 32px !important;
                line-height: 32px !important;
            }
        }

        /* ANDROID CENTER FIX */
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>
</head>
<body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
<!-- HIDDEN PREHEADER TEXT -->
<div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: 'Lato', Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">Notificación Registro conferencia</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#f4f4f4" align="center" style="background-color: #f4f4f4 ;padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td bgcolor="#3d98d1" align="center" valign="top" style="border-radius: 4px 4px 0px 0px; color: #00338d; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                        <!--<h1 style="font-size: 48px; font-weight: 400; margin: 2;">¡Notificación!</h1>-->
                        <img src="{{asset('notifications/banner-notificaciones.png')}}" width="100%" height="60%" style="display: block; border: 0px;" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#f4f4f4" align="center" style="padding: 10px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td bgcolor="#ffffff" align="center" style="padding: 30px 30px 30px 30px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                        <h2  style="font-size: 25px; font-weight: 400; color: #F15A24; margin: 0;">Ingreso a conferencia</h2>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff" align="left" style="padding: 20px 20px 20px 20px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                        <p>Te has registrado a <span style="color: #F15A24"> {{$agenda->title}} </span>.</p>
                        <!--<p>A continuación encontrarás los datos de acceso para el ingreso:</p>-->
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff" align="left" style="padding: 1px 20px 20px 20px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 20px;text-align: center; vertical-align: middle;">
                        <hr style="color:white; background-color: #F15A24;border: solid;">
                    </td>

                </tr>
                <!--
                <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 20px 20px 20px 20px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p>A continuación encontrarás los datos de acceso para el ingreso:</p>
                            <p><span style="font-size: 18px; font-weight: 400; color: #3d98d1; margin: 0;">Conferecia virtual:</span> </p>
                            <p><span style="font-size: 18px; font-weight: 400; color: #3d98d1; margin: 0;">Fecha:</span> {{date("d/m/Y H:i:s", ($agenda->start_at/ 1000))}} </p>
                            <p><span style="font-size: 18px; font-weight: 400; color: #3d98d1; margin: 0;">Duración:</span> {{$agenda->duration_time}}</p>
                            <p><span style="font-size: 18px; font-weight: 400; color: #3d98d1; margin: 0;">ID Reunión:</span> {{$agenda->zoom_code}}</p>
                            <p><span style="font-size: 18px; font-weight: 400; color: #3d98d1; margin: 0;">Código de acceso:</span> {{$agenda->zoom_password}}</p>
                        </td>
                    </tr>
                    -->
                    <!--
                    <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 1px 20px 20px 20px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 20px;text-align: center; vertical-align: middle;">
                            <button type="button" class="btn btn-primary">Unirse a la conferencia</button>
                        </td>
                    </tr>
                    -->
                    <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 1px 20px 20px 20px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 20px;text-align: center; vertical-align: middle;">
                            <hr style="color:#F15A24; background-color: #F15A24;border: solid;">
                        </td>
                    </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>



