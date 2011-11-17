<?php
//
//require_once 'class.xhttp.php';
//
//class Facebook_model extends CI_Model {
// 
//    public function __construct()
//    {
//        parent::__construct();
// 
//        $config = array(
//                        'appId'  => '121701154606843',
//                        'secret' => '9375f40fc20e1a2025adff48d9f5154c',
//                        'fileUpload' => false, // Indicates if the CURL based @ syntax for file uploads is enabled.
//                        );
//         $this->load->library('facebook', $config);
// 
//        $user = $this->facebook->getUser();
// 
//        // We may or may not have this data based on whether the user is logged in.
//        //
//        // If we have a $user id here, it means we know the user is logged into
//        // Facebook, but we don't know if the access token is valid. An access
//        // token is invalid if the user logged out of Facebook.
//        $profile = null;
//        if($user)
//        {
//            try {
//                // Proceed knowing you have a logged in user who's authenticated.
//                $profile = $this->facebook->api('/me?fields=id,name,link,email');
//            } catch (FacebookApiException $e) {
//                error_log($e);
//                $user = null;
//            }
//        }
// 
//        $client_id = '121701154606843';
//        $client_secret ='9375f40fc20e1a2025adff48d9f5154c';
//        $callbackURL = "http://localhost/CS130-Mashup/web/index.php/home";
//        $perms = "user_likes";
//        /*
//        if (isset($_GET['signin'])) {
//            # STEP 1: Redirect user to FB to obtain perm for our app
//            $url = "https://graph.facebook.com/oauth/authorize?" .xhttp::toQueryString(array(
//               'client_id' => $client_id,
//                'redirect_uri' => $callbackURL,
//                'scope' => $perms,
//            ));
//            header("Location: $url",true,303);
//            die();
//        }*/
//       $url = "https://graph.facebook.com/oauth/authorize?" . xhttp::toQueryString(array(
//           'client_id' => $client_id,
//            'redirect_uri' => $callbackURL,
//            'scope' => $perms,
//        ));
//         /*
//       if(isset($_GET['code'])) {
//           # STEP 2: Exchange the code that we have for an access token
//           $data = array();
//           $data['get'] = array (
//               'client_id'=> $client_id,
//               'client_secret' => $client_secret,
//               'code' => $_GET['code'],
//               'redirect_uri' => $callbackURL,
//           
//           );
//           $response = xhttp::fetch('https://graph.facebook.com/oauth/access_token', $data);
//           if ($response['successful']) {
//               $data = xhttp::toQueryArray($response['body']);
//               $_SESSION['access_token'] = $data['access_token'];
//               $_SESSION['loggedin'] = true;
//           } else {
//               echo($response['body']);
//           }
//       }
//       
//       if(isset($_GET['error']) and isset($_GET['error_reason']) and isset($_GET['error_description'])) {
//           # error_reason: user_denied
//           # error: access_denied
//           # error_description: The user denied your request.
//        }
//       
//        
//         # Get access tokens of user's news feed, and his/her pages
//           $data = array();
//           $data['get'] = array(
//              'access_token'  => $_SESSION['access_token'],
//              'fields' => 'id,name,accounts'
//              );
//           $response = xhttp::fetch('https://graph.facebook.com/me/likes', $data);
//
//           echo $response;
//        */
//        $fb_data = array(
//                        'me' => $profile,
//                        'uid' => $user,
//                        'loginUrl' => $url,
//                        'logoutUrl' => $this->facebook->getLogoutUrl(),
//        );
// 
//        $this->session->set_userdata('fb_data', $fb_data);
//    }
//}
?>