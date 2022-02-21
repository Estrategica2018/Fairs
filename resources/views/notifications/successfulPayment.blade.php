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
<div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: 'Lato', Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">Notificación pago Ferias Virtuales</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td bgcolor="#3d98d1" align="center" valign="top" style="border-radius: 4px 4px 0px 0px; color: #00338d; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                        <!--<h1 style="font-size: 48px; font-weight: 400; margin: 2;">¡Notificación!</h1>-->
                        <img src="https://upload.wikimedia.org/wikipedia/commons/f/f4/WOM_logo.png" width="15%" height="15%" style="display: block; border: 0px;" />
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
                        <h2  style="font-size: 25px; font-weight: 400; color: #005EB8; margin: 0;">Tu pago fue confirmado</h2>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff" align="left" style="padding: 20px 20px 20px 20px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                        <p>Hola!</p>
                        <p>El pago por tu compra en e-logic feria virtual,con número de pedido #, realizado por {{$transaction['payment_method']}}}, se encuentra <span style="color: #4CAF50">Aprobado</span></p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>

        <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">

            <table style="border-radius: 5px; width: 100%; height: 77px;max-width: 600px;" >
                @foreach($shoppingCart as $data)
                    <tbody bgcolor="#ffffff">
                    <tr bgcolor="#ffffff" style="height: 29px; min-width: 12em;">
                        <td bgcolor="#ffffff" style="width: 33.3333%; min-width: 12em; height: 77px;" rowspan="100"><img style="display: block; margin-left: auto; margin-right: auto;" src="https://res.cloudinary.com/deueufyac/image/upload/v1631835506/sublimacion/productos/cachuchas/productos-07_hunktp.png" width="99" height="98" /></td>
                        <td bgcolor="#ffffff" style="font-family: 'Lato', Helvetica, Arial, sans-serif;width: 90.5011%; min-width: 12em; height: 33px; font-size: 20.3333px; font-family: YoutubeSansMedium; color: #004782; font-weight: 600; top: 8.84056px; left: 60.9998px;" colspan="3">&nbsp; &nbsp; Prodasdfasdfucto #1</td>
                    </tr>
                    <tr bgcolor="#ffffff" style="height: 10px;">
                        <td bgcolor="#ffffff" style="font-family: 'Lato', Helvetica, Arial, sans-serif; width: 35.0759%; min-width: 12em; height: 10px;">
                            <div>      <div>marca : &nbsp; Ford</div> <div>modelo : &nbsp; 2016      </div>      <div>kilometraje : &nbsp; 42000      </div></div>
                        </td>
                        <td bgcolor="#ffffff" style="font-family: 'Lato', Helvetica, Arial, sans-serif; display: block; width: 53.8252%; min-width: 12em; height: 10px;">&nbsp;
                            <span style="font-weight: bold;">Cantidad:</span> 1 <br /><br />
                            <div style="background-color:#ffffff;" bgcolor="#ffffff">
                                <span style="font-weight: bold; font-family: 'Lato', Helvetica, Arial, sans-serif;">&nbsp; Precio:</span> $1250
                            </div>
                        </td>
                    </tr>
                    </tbody>
                @endforeach
                <tfoot class="card-footer">
                <tr bgcolor="#ffffff">
                    <td colspan="2" align="right" style="font-family: 'Lato', Helvetica, Arial, sans-serif;"><strong>Sub Total:</strong></td>
                    <td align="right" style="font-family: 'Lato', Helvetica, Arial, sans-serif;">$2150.00</td>
                </tr>
                <tr bgcolor="#ffffff">
                    <td colspan="2" align="right" style="font-family: 'Lato', Helvetica, Arial, sans-serif;"><strong>Impuestos:</strong></td>
                    <td align="right" style="font-family: 'Lato', Helvetica, Arial, sans-serif;">$2150.00</td>
                </tr>
                <tr bgcolor="#ffffff">
                    <td colspan="2" align="right" style="font-family: 'Lato', Helvetica, Arial, sans-serif;"><strong>Total:</strong></td>
                    <td align="right" style="font-family: 'Lato', Helvetica, Arial, sans-serif;">{{$totalPrice}}</td>
                </tr>
                </tfoot>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
