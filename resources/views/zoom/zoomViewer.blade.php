<!DOCTYPE html>
  <head>
    <title>Zoom WebSDK</title>
    <meta charset="utf-8" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.9.0/css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.9.0/css/react-select.css" />
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="origin-trial" content="">
    <style>
        .top { top: 25em !important;}
        .ReactModal__Body--open {

        }
        .gallery-video-container__wrap,
        #wc-container-left {
          background-image: url(https://res.cloudinary.com/deueufyac/image/upload/v1667586586/CONGRESO%20NACIONAL%20DE%20BIBLIOTECAS/DISE%C3%91O%20INTERNAS/pattner_fondo_zi9drp.png);
        }
        .ion-back {
            cursor: pointer !important;
            position: absolute;
            right: 62px;
            top: 8px;
            z-index: 101;
            border-bottom: none;
        }
        .ion-back-widget {
            display: inline-block;
            width: 189px;
            height: 32px;
            vertical-align: middle;
            background-color: orange;
            border-radius: 18px;
            cursor: pointer;
            color: rgb(76, 37, 5);
            font-weight: bold;
        }
        .ion-back-widget:hover {
            color: rgb(240, 240, 240);
            background-color: rgb(241, 90, 36);
        }
    </style>
  </head>
  <body>
    <script src="https://source.zoom.us/1.9.0/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/1.9.0/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/1.9.0/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/1.9.0/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/1.9.0/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-1.9.0.min.js"></script>
    <script src="/zoom-client-js/tool-min.js"></script>
    <script src="/zoom-client-js/vconsole.min.js"></script>
    <script src="/zoom-client-js/meeting-min.js"></script>
    <script>
       const simd = async () => WebAssembly.validate(new Uint8Array([0, 97, 115, 109, 1, 0, 0, 0, 1, 4, 1, 96, 0, 0, 3, 2, 1, 0, 10, 9, 1, 7, 0, 65, 0, 253, 15, 26, 11]))
       simd().then((res) => {
          console.log("simd check", res);
       });
    </script>
    
    
    <script>
      var data = {
      "name": {!! json_encode($name) !!},
      "mn": {!! json_encode($mn) !!},
      "email": {!! json_encode($email) !!},
      "pwd": {!! json_encode($pwd) !!},
      "role": {!! json_encode($role) !!},
      "lang": {!! json_encode($lang) !!},
      "signature": {!! json_encode($signature) !!},
      "china": {!! json_encode($china) !!},
      "apiKey": {!! json_encode($apiKey) !!}
      };

      console.log(data);

      window.addEventListener('DOMContentLoaded', function(event) {
        console.log('DOM fully loaded and parsed');
        
        @if(isset($saveResgisterUrl) && $saveResgisterUrl)
          websdkready(data, '{!!$saveResgisterUrl!!}');
        @else 
          websdkready(data, null);
        @endif;
        
 
        var interval =  setInterval(() => {
          var div1 = document.querySelector('.meeting-info-icon__icon-wrap');
          console.log('internal');
          if(div1) { 
            div1.remove();          
            clearInterval(interval);
          }

        }, 1000);

        var interval1 = setInterval(() => {
          console.log('internal1');
          var iconFullScreen = document.querySelector(".full-screen-icon");
          if(iconFullScreen) {
             var ionBack = document.querySelector(".ion-back");
             if(!ionBack) {
              let button = document.createElement('div');
              button.innerHTML = '<div class="ion-back"><button type="button" class="ion-back-widget">Volver al congreso</button></div>';
              iconFullScreen.parentElement.appendChild(button);
              button.onclick = function () {
                console.log('click' + document.referrer );
                //if(document.referrer && ( document.referrer.indexOf('e-logic') || document.referrer.indexOf('localhost')) ) {
                if(true) {
                  const windowReference = window.open();
                  if(windowReference) windowReference.location.href = window.document.referrer;
                  //history.back();
                }
                else {
                  window.location.href = '{!!$url_redirect!!}';
                }
              }

              clearInterval(interval1);
             }
          }
        }, 1000);


        var interval2 = setInterval(() => {  
          console.log('internal2');        
          var div = document.querySelector(".participants-section-container__participants-footer-bottom .ax-outline-blue-important");
          if(div) {
            div.remove();
            clearInterval(interval2);
          }
        }, 10000);

      });
    </script>

  </body>
</html>