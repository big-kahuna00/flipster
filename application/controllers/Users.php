<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User Management class created by CodexWorld
 */
class Users extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('user');
        require_once("vendor/autoload.php");
        $this->conn = new mysqli("localhost", "root", "autosharkSite1", "flipster");
// Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        $this->load->helper('rets');
    }

    public function index()
    {

        redirect('/users/home/');
    }

    public function admin()
    {
        $building_descriptions = array();
        $sql = "SELECT * FROM building_descriptions";
        $building_descriptions_result = $this->conn->query($sql);
        if ($building_descriptions_result->num_rows > 0){
            while ($description = $building_descriptions_result->fetch_assoc()){
                $building_descriptions[] = $description;
            }
        }
        $data["building_descriptions"] = $building_descriptions;
        $data['user'] = $this->user->getRows(array('id'=>$this->session->userdata('userId')));
        $data['users']=$this->user->get_all_end_users();
        $this->load->view('user_view',$data);
    }

    public function new_pass()
    {
        $data['user'] = $this->user->getRows(array('id'=>$this->session->userdata('userId')));
        $this->load->view('new_pass');
    }

    public function account(){
        $data = array();
        if($this->session->userdata('isUserLoggedIn')){
            $data['user'] = $this->user->getRows(array('id'=>$this->session->userdata('userId')));
            //load the view
            $this->load->view('users/account', $data);
        }else{
            redirect('users/login');
        }
    }

    public function home(){
        $data = array();
        if($this->session->userdata('isUserLoggedIn')){
            $data['user'] = $this->user->getRows(array('id'=>$this->session->userdata('userId')));
            //load the view
            $sql = "SELECT * FROM users";
            $data["users_result"] = $this->conn->query($sql);
            $this->load->view('users/home', $data);
        }else{
            redirect('users/login');
        }
    }

    public function details(){

        $internal_values = array("rehab"=>"20");

        $data = array();

        if($this->session->userdata('isUserLoggedIn')){
            $data['user'] = $this->user->getRows(array('id'=>$this->session->userdata('userId')));
            //load the view
        }else{
            redirect('users/login');
        }

        if($this->session->userdata('isUserLoggedIn')){
            $data['user'] = $this->user->getRows(array('id'=>$this->session->userdata('userId')));
            //load the view

            $config = new \PHRETS\Configuration;
            $config->setLoginUrl('http://rets.las.mlsmatrix.com/rets/login.ashx')
                ->setUsername('redwealth')
                ->setPassword('glvrets')
                ->setRetsVersion('1.5');

            $rets = new \PHRETS\Session($config);
            $connect = $rets->Login();

            $results = $rets->Search('Property', 'Listing', '(matrix_unique_id = '.$_GET["id"].')',['Limit' => 1]);
            $count = 0;

            foreach($results as $result){
                $current = $result->toArray();
            }


            $address = $current["StreetNumber"];
            if($current["StreetDirPrefix"]){
                $address .= " ".$current["StreetDirPrefix"];
            }
            if($current["StreetName"]){
                $address .= " ".$current["StreetName"];
            }
            if($current["StreetSuffix"]){
                $address .= " ".$current["StreetSuffix"];
            }
            if($current["StreetDirSuffix"]){
                $address .= " ".$current["StreetDirSuffix"];
            }

            $data["address"] = $address;

            $sql = "SELECT * FROM property WHERE Matrix_Unique_ID =".$_GET["id"];
            $property_result = $this->conn->query($sql);
            if ($property_result->num_rows > 0){
                $property = $property_result->fetch_assoc();
            }

            $address = urlencode($address." ".$current["City"]." NV ".$current["PostalCode"]);

            $lat = $property["lat"];
            $long = $property["lng"];


            $photos = $rets->GetObject("Property", "Photo", $_GET["id"], "*", 0);
            $large_photos = $rets->GetObject("Property", "LargePhoto", $_GET["id"], "*", 0);



            $photo_html = "";

            $first_photo = true;
            $photo_count = 0;
            foreach ($photos as $photo) {

                $number = $photo->getObjectId();

                $contentType = $photo->getContentType();
                $base64 = base64_encode($photo->getContent());
                $active_class = $first_photo?" active":"";

                $photo_html .= "<div class='carousel-item ".$active_class."'><img class='d-block w-100' src='data:{$contentType};base64,{$base64}' data-number = '".($number+1)."' alt = '".($number+1)." / ".count($photos)."'/><div class='carousel-caption d-none d-md-block'><p>".($number+1)." / ".count($photos)."</p></div></div>";
                $first_photo = false;
                $photo_count++;
            }

            $large_photo_html = "";

            $large_first_photo = true;
            $large_photo_count = 0;

            foreach ($large_photos as $photo) {
                $number = $photo->getObjectId();

                $contentType = $photo->getContentType();
                $base64 = base64_encode($photo->getContent());
                $active_class = $large_first_photo?" active":"";

                $large_photo_html .= "<div id='large-".($number+1)."' class='carousel-item ".$active_class."'><img class='d-block w-100' src='data:{$contentType};base64,{$base64}' alt = '".($number+1)." / ".count($large_photos)."'/><div class='carousel-caption d-none d-md-block'><p>".($number+1)." / ".count($large_photos)."</p></div></div>";

                $large_first_photo = false;
                $large_photo_count++;
            }

            if(empty($current["ApproxTotalLivArea"])){
                $current["ApproxTotalLivArea"] = $current["SqFtTotal"];
            }

            if($_POST && $_POST["distance"]){
                $distance = $_POST["distance"];
            } else {
                $distance = .5;
            }
            $sql = "SELECT * FROM users";
            $data["users_result"] = $this->conn->query($sql);

            $sql = "SELECT messages.time as time, messages.message as message, to_user.name as to_user_name, from_user.name as from_user_name  FROM messages LEFT JOIN users AS from_user on from_user.id = messages.user_id LEFT JOIN users AS to_user ON to_user.id = messages.to_user_id WHERE property_id =".$_GET["id"]." ORDER BY time DESC";

            $data["messages"] = $this->conn->query($sql);

            $comp_data = get_comps($lat,$long,$current,$this->conn,.5);

            $data["totals"] = calculate_comps($comp_data,$current["ApproxTotalLivArea"],$internal_values);

            $listing_date_array = explode("T",$current["ListingContractDate"]);

            $datetime1 = new DateTime("now");
            $datetime2 = new DateTime($listing_date_array[0]);
            $data["interval"] = $datetime1->diff($datetime2);
            $data["current"] = $current;
            $data["comp_data"] = $comp_data;
            $data["distance"] = $distance;
            $data["lat"] = $lat;
            $data["long"] = $long;
            $data["address"] = $address;
            $data["internal_values"] = $internal_values;
            $data["notes"] = $property["notes"];
            $data["property_owner"] = $property["user_id"];
            $data["photo"] = $photo_html;
            $data["large_photo"] = $large_photo_html;
            $data["internal_status"] = $property["internal_status"];


            $this->load->view('users/details', $data);
        }else{
            redirect('users/login');
        }
    }

    /*
     * User login
     */
    public function login(){
        $data = array();
        if($this->session->userdata('success_msg')){
            $data['success_msg'] = $this->session->userdata('success_msg');
            $this->session->unset_userdata('success_msg');
        }
        if($this->session->userdata('error_msg')){
            $data['error_msg'] = $this->session->userdata('error_msg');
            $this->session->unset_userdata('error_msg');
        }
        if($this->input->post('loginSubmit')){
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'password', 'required');
            if ($this->form_validation->run() == true) {
                $con['returnType'] = 'single';
                $con['conditions'] = array(
                    'email'=>$this->input->post('email'),
                    'password' => md5($this->input->post('password')),
                    'status' => '1'
                );
                $checkLogin = $this->user->getRows($con);
                if($checkLogin){
                //    $ga = new GoogleAuthenticator();
                  //  $secret = $ga->createSecret();

                    $this->session->set_userdata('isUserLoggedIn',TRUE);
                    $this->session->set_userdata('userId',$checkLogin['id']);
                    redirect('users/home/');
                }else{
                    $data['error_msg'] = 'Wrong email or password, please try again.';
                }
            }
        }

        //load the view
        $this->load->view('users/login', $data);
    }

    /*
     * User registration
     */
    public function registration(){
        $data = array();
        $userData = array();
        if($this->input->post('regisSubmit')){
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check');
            $this->form_validation->set_rules('password', 'password', 'required');
            $this->form_validation->set_rules('conf_password', 'confirm password', 'required|matches[password]');

            $userData = array(
                'name' => strip_tags($this->input->post('name')),
                'email' => strip_tags($this->input->post('email')),
                'password' => md5($this->input->post('password')),
                'phone' => strip_tags($this->input->post('phone')),
                'color' => strip_tags($this->input->post('color'))
            );

            if($this->form_validation->run() == true){
                $insert = $this->user->insert($userData);
                if($insert){
                    $this->session->set_userdata('success_msg', 'Your registration was successfully. Please login to your account.');
                    redirect('users/admin');
                }else{
                    $data['error_msg'] = 'Some problems occured, please try again.';
                }
            }
        }
        $data['user'] = $userData;

        //load the view
        $this->load->view('users/registration', $data);
    }

    /*
     * User logout
     */
    public function logout(){
        $this->session->unset_userdata('isUserLoggedIn');
        $this->session->unset_userdata('userId');
        $this->session->sess_destroy();
        redirect('users/login/');
    }

    /*
     * Existing email check during validation
     */
    public function email_check($str){
        $con['returnType'] = 'count';
        $con['conditions'] = array('email'=>$str);
        $checkEmail = $this->user->getRows($con);
        if($checkEmail > 0){
            $this->form_validation->set_message('email_check', 'The given email already exists.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function delete($id)
    {
        $this->user->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_edit($id)
    {
        $data = $this->user->get_by_id($id);
        echo json_encode($data);
    }

    public function user_update(){

        $data = array(
            'user_name' => $this->input->post('currency_name'),
            'user_email' => $this->input->post('currency_email'),
            'user_phone' => $this->input->post('currency_phone'),
        );
        $this->currency->user_update(array('user_id' => $this->input->post('user_id')), $data);
        echo json_encode(array("status" => TRUE));

    }
}