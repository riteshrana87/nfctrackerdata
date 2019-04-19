<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MasteradminAzure extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->viewname = $this->uri->segment(1);
        $this->load->helper(array('form'));
        //This method will have the credentials validation
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->config('aad_auth');
    }

     public function getMeEntry(){
            // initiaze curl which is used to make the http request
            $ch = curl_init();
            
            // Add authorization and other headers. Also set some common settings.
            self::AddRequiredHeadersAndSettings($ch);
            
            // Create url for the entry based on the feedname and the key value
            $feedURL = "https://graph.windows.net/".$this->config->item('directory_identifier')."/me/";
            $feedURL = $feedURL."?".$this->config->item('apiVersion');
            curl_setopt($ch, CURLOPT_URL, $feedURL);             
            
            //Enable fiddler to capture request
            //curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');
            // $output contains the output string 
            $output = curl_exec($ch);
            
            // close curl resource to free up system resources 
            curl_close($ch);      
            $jsonOutput = json_decode($output);
            return $jsonOutput;
        }

        // Constructs a HTTP PATCH request for updating current user entry.
        public function updateMeEntry($entry){
            //initiaze curl which is used to make the http request
            $ch = curl_init();
            self::AddRequiredHeadersAndSettings($ch);
            // set url
            $feedURL = "https://graph.windows.net/me"."?".$this->config->item('apiVersion');
            curl_setopt($ch, CURLOPT_URL, $feedURL); 
            // Mark as Patch request
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            $data = json_encode($entry);
            // Set the data for the request
            curl_setopt($ch, CURLOPT_POSTFIELDS,  $data);
            // read the output from the request
            $output = curl_exec($ch); 
            // close curl resource to free up system resources
            curl_close($ch);      
            // decode the response json decoder
            $udpatedEntry = json_decode($output);
            return $udpatedEntry;
        }

        // Add required headers like Authorization, Accept, Content-Type etc.
        public function AddRequiredHeadersAndSettings($ch)
        {
            //Generate the authentication header
            $authHeader = 'Authorization:' . $_SESSION['token_type'].' '.$_SESSION['access_token'];
            curl_setopt($ch, CURLOPT_HTTPHEADER, array($authHeader, 'Accept:application/json;odata=minimalmetadata',
                                                       'Content-Type:application/json'));           
            // Set the option to recieve the response back as string.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            // By default https does not work for CURL.
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }


        public function getAuthorizatonURL(){

        $authUrl = "https://login.windows.net/common/oauth2/authorize". "?" .
                   "response_type=code" . "&" .
                   "client_id=" . $this->config->item('client_id') . "&" .
                   "resource=" . $this->config->item('resource_uri') . "&" .
                   "redirect_uri=" . $this->config->item('redirect_uri_segment');
        return $authUrl;
    }

    // Use the code retrieved from authorization URL to get the authentication token that will be used to talk to Graph
    public function HandleAuthorizeResponse(){
        $code = $_GET['code'];
       // Construct the body for the STS request
        $authenticationRequestBody = "grant_type=authorization_code" . "&".                                                                          
                                     "client_id=".urlencode($this->config->item('client_id')) . "&".
                                     "redirect_uri=".$this->config->item('redirect_uri_segment') . "&".
                                     "client_secret=".urlencode($this->config->item('client_secret')). "&".
                                     "code=".$code;

        //Using curl to post the information to STS and get back the authentication response    
        $ch = curl_init();
        // set url 
        $stsUrl = 'https://login.windows.net/common/oauth2/token';
        //curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');
        curl_setopt($ch, CURLOPT_URL, $stsUrl); 
        // Get the response back as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        // Mark as Post request
        curl_setopt($ch, CURLOPT_POST, 1);
        // Set the parameters for the request
        curl_setopt($ch, CURLOPT_POSTFIELDS,  $authenticationRequestBody);
        //curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');

        // By default, HTTPS does not work with curl.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // read the output from the post request
        $output = curl_exec($ch);     
        // close curl resource to free up system resources
        curl_close($ch); 
             
        //Decode the json response from STS
        $tokenOutput = json_decode($output);
        $tokenType = $tokenOutput->{'token_type'};
        $accessToken = $tokenOutput->{'access_token'};
        $tokenScope = $tokenOutput->{'scope'};
        
        //print("\t Token Type: ".$tokenType."\n AccessToken: ".$accessToken);
        // Add the token information to the session header so that we can use it to access Graph
        $this->session->set_userdata('token_type', $tokenType);   
        $this->session->set_userdata('access_token', $accessToken);   
        $this->session->set_userdata('tokenOutput', $tokenOutput);   

       redirect('Masteradmin/login');   
    }
}
