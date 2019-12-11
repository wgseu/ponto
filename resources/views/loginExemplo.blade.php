<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- Configurações login facebook alterar o id na versão final -->

<!-- Configurações login Google alterar o content na versão final -->
<meta name="google-signin-client_id" content="523566032933-cvdv6kn4p9fm0b203j8ne8ahvlv7u9vt.apps.googleusercontent.com">
<script src="https://apis.google.com/js/platform.js" async defer></script>

<!-- Configurações token Laravel -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Login Google 
    Enviar um token contendo googleUser.getAuthResponse().id_token
    Return Refresh Token
-->
<script>
function onSignIn(googleUser) {
  //var response = googleUser.getBasicProfile();
  var token =  googleUser.getAuthResponse().id_token;
  if (googleUser.isSignedIn()) {
    $.ajax({
      type: "POST",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "login/google",
      data: {
        token
      },
      dataType: "text",
      success: function (data) {
        console.log(data);
      },
      error: function (data, textStatus, errorThrown) {
        console.log(data);
      },
    });
  }
}
</script>

<style>
  .g-signin2{
    margin-left:500px;
    margin-top:200px;
  }
  .fb-login-button{
    margin-left:500px;
  }
</style>
</head>
<body>
<!-- Login  Facebook 
    Enviar um token com response.authResponse.accessToken
    Return Refresh Token
-->
<script>
  function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
    if (response.status === 'connected') {   // Logged into your webpage and Facebook.
      login(response);  
    } else {                                 // Not logged into your webpage or we are unable to tell.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this webpage.';
    }
  }


  function checkLoginState() {               // Called when a person is finished with the Login Button.
    FB.getLoginStatus(function(response) {   // See the onlogin handler
      statusChangeCallback(response);
    });
  }


  window.fbAsyncInit = function() {
    FB.init({
      appId      : '544029493058058',
      cookie     : true,                     // Enable cookies to allow the server to access the session.
      xfbml      : true,                     // Parse social plugins on this webpage.
      version    : 'v5.0'           // Use this Graph API version for this call.
    });


    FB.getLoginStatus(function(response) {   // Called after the JS SDK has been initialized.
      statusChangeCallback(response);        // Returns the login status.
    });
  };

  
  (function(d, s, id) {                      // Load the SDK asynchronously
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

 
  function login(response) {                      // Testing Graph API after login.  See statusChangeCallback() for when this call is made.
    var token = response.authResponse.accessToken;
    $.ajax({
      type: "POST",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "login/facebook",
      data: {
        token
      },
      dataType: "text",
      success: function (data) {
        console.log(data);
      },
      error: function (data, textStatus, errorThrown) {
        console.log(data);
      },
    });
  }

</script>
<!-- Botão Google -->
<div class="g-signin2" data-onsuccess="onSignIn"></div>

<!-- Botão Facebook -->
<div class="fb-login-button" data-width="" data-size="large" data-button-type="continue_with" data-auto-logout-link="false" data-use-continue-as="false" scope="public_profile,email" onlogin="checkLoginState();"></div>

</body>
</html>