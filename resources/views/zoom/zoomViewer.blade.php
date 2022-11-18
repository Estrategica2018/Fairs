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
    
    @if(isset($url_redirect)) 
    <script>
       window.location.href = $url_redirect;
    </script> 
    @endif 
    
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

      window.addEventListener('DOMContentLoaded', function(event) {
        console.log('DOM fully loaded and parsed');
        
        @if(isset($saveResgisterUrl) && $saveResgisterUrl)
          websdkready(data, '{!!$saveResgisterUrl!!}');
        @else 
          websdkready(data, null);
        @endif;
        

        setTimeout(() => {
          document.querySelector('.meeting-info-icon__icon-wrap').remove();
          //var elem = document.createElement("img");
          //elem.setAttribute("src", "https://res.cloudinary.com/deueufyac/image/upload/v1668187745/CONGRESO%20NACIONAL%20DE%20BIBLIOTECAS/FORMULARIOS/banner_ijt47d.png");
          //document.querySelector("body").appendChild(elem);
          //elem.classList.add("top");



          //var div = document.querySelector('#zmmtg-root');
          //div.classList.add("top");

        }, 10000);
      });
    </script>

  </body>
</html>