
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

        .box-number {
            background-color: #e7e9eb;
            width: 4px;
            padding: 28px;
            margin: 20px;
            border-radius: 10px;
            flex-basis: 0;
            -webkit-box-flex: 2;
            -ms-flex-positive: 1;
            flex-grow: 2;
            max-width: 100%;
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
		.btn {
              display: inline-block;
              font-weight: 400;
              text-align: center;
              white-space: nowrap;
              vertical-align: middle;
              border: 1px solid transparent;
              padding: 0.375rem 0.75rem;
              font-size: 1rem;
              line-height: 1.5;
              border-radius: 0.25rem;
              cursor: pointer;
         }
         .btn-primary {
        	color: #fff;
			background-color: #3d98d1;
        	border-color: #3d98d1;
         }
  
      </style>
</head>
<body style="background-color: {{$cardColor}}; margin: 0 !important; padding: 0 !important;">
<!-- HIDDEN PREHEADER TEXT -->
<div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: 'Lato', Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">Confirmación de cuenta Ferias Virtuales</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#f4f4f4" align="center" style="background-color: {{$cardColor}} ,padding: 0px 10px 0px 10px;">
          <img src="{{$iconUrl}}" width="22%" height="22%" style="margin-top: 11px;" />
        </td>
    </tr>
    <tr>
        <td bgcolor="#f4f4f4" align="center" style="padding: 10px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td bgcolor="#ffffff" align="center" style="padding: 30px 30px 30px 30px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                        <h2  style="font-size: 25px; font-weight: 400; color: #005EB8; margin: 0;">Confirmación de registro</h2>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff" align="left" style="padding: 0px 20px 20px 20px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                        <p>Hola, ¡Bienvenido a e-logic feria virtual! </p>
                        <p>Para continuar con el proceso de registro por favor ingresa el siguiente código de validación:</p>
                    </td>
                </tr>
                <tr >
                    <td bgcolor="#ffffff" align="left" style="padding: 0px 20px 20px 20px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <div class="box-number">
                                        {{$array_code[5]}}
                                    </div>
                                </td>
                                <td>
                                    <div class="box-number">
                                        {{$array_code[4]}}
                                    </div>
                                </td>
                                <td>
                                    <div class="box-number">
                                        {{$array_code[3]}}
                                    </div>
                                </td>
                                <td>
                                    <div class="box-number">
                                        {{$array_code[2]}}
                                    </div>
                                </td>
                                <td>
                                    <div class="box-number">
                                        {{$array_code[1]}}
                                    </div>
                                </td>
                                <td>
                                    <div class="box-number">
                                        {{$array_code[0]}}
                                    </div>
                                </td>
                            </tr>
                        </table> 
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff" align="left" style="padding: 0px 20px 20px 20px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                        <p>Recuerda ingresar este código lo más pronto posible, para acceder fácilmente a toda la información de nuestra plataforma</p>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff" align="left" style="padding:20px 20px 20px 20px;border-radius:4px 4px 4px 4px;color:#666666;font-family:'Lato',Helvetica,Arial,sans-serif;font-size:18px;font-weight:400;line-height:25px;text-align:center;vertical-align:middle">
                      <a href="{{$btnLink}}" target="_blank">
						<button type="button" class="btn btn-primary" style="cursor: pointer;">Confirmar registro</button>
					   </a>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff" align="left" style="padding: 0px 20px 20px 20px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                        <p>Si tienes problemas para realizar el proceso comunícate al correo admin@e-logic.com.co</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

