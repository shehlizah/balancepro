<?php
/*** This is main file, all activities pass throug this file***/
/*** Author : Dhiraj Uphat ***/

//$user='balancepro';
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
   ob_start("ob_gzhandler");
   else ob_start();
   
session_start();

include_once('includes/config.php');
include_once('includes/functions.php');
include_once('includes/func_resource.php');

error_reporting(1);
$message = '';
$action = '';

$domain_slug = '';

//check if site is publish or not
if(checkSitePublish($domain_slug)==false)
{
 include '404.php';
 die();	
}	
$mainhtml = getHomePage($domain_slug);
//if(!isset($_SESSION['superadmin'])){	
getAdminEmailIds();
$adminmail = $_SESSION['siteadmin'];     
$superadminmail = $_SESSION['superadmin'];
//}
//print_r($_SESSION['siteadmin']);

//get action
if(isset($_REQUEST['action']) && $_REQUEST['action']!=''){
	$action=$_REQUEST['action'];
}

switch ($action) {
  case 'do_register':
    
	include  'php/registration.php';
		exit;
	
    break;
	
  case 'do_login':
  
  
    include  'php/login.php'; exit;
	
    break;
	
	case 'login':
		if(isset($_SESSION['loggedIn'])){
        $nextLoc= $_COOKIE['hrefVal'];
        header("location:$nextLoc");die;
		}
    $file = 'templates/mainlogin.php';
	
    break;
	
	case 'thankyou':
  
    $file = 'templates/thankyou.php';
	
    break;
	
	case 'error':
  
    $file = 'templates/error.php';
	
    break;
	
	case 'verify':
    if(verifyToken($_GET['token']))  { 
	$_SESSION['firstlogin']=1; header("location:$base_url");die;
	}
	else {
		$_SESSION['firstlogin_err']='Invalid confirmation code, please check your email or register a new account.';
		 header("location:$base_url");die;
	}
	
	
    break;
	
	
  case 'logout':
  
    logout();
	
    break;
	
   case 'contact':
	if(isset($_SESSION['accesstocontact']) && $_SESSION['accesstocontact']=='1'){
    $file = 'templates/contact.php';
	}
	else {
		include 'templates/noaccess.php';
		die();
	}
	
    break;	
	
	case 'sendmail_forgot':
		
		sendmailForgotPassword(trim($_POST['email']));
		if(isset($_POST['actiontype'])){
			$_SESSION['login_error']="Please check your mailbox to reset your password.";
			header("location:$base_url".'login');die;
		} else	exit;		
	
    break;	
	
	case 'forgot_password':
		
		$file = 'templates/resetpassword.php';		
	
    break;

	case 'changepassword':
		
/*		if(empty($_POST['g-recaptcha-response'])) {
			$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
			$responseData = json_decode($verifyResponse);
			if(!$responseData->success){*/
change_password($_POST);
                                        $_SESSION['message']='Your password is changed successfully!';
                                        header("location:$base_url");die;		
		/*		if(change_password($_POST)){
					$_SESSION['message']='Your password is changed successfully!';
					header("location:$base_url");die;
				}
				else{
					$referrer=$_SERVER['HTTP_REFERER'];
					$_SESSION['chpass_msg']="Something is wrong! Please try again.";
					header("location:$referrer");die;
				}
			}
			else{
				$_SESSION['chpass_msg']='Captcha doest not successfully selected.';
				header("location:$base_url".'login');exit;
			}
		}
		else{
			$_SESSION['chpass_msg']='Captcha doest not successfully selected.';
			header("location:$base_url".'login');exit;
		}*/
	
    break;	
	
	case 'check_password_exists':
		echo checkPasswordExists($_GET);exit;
	
    break;

	case 'chkuserexits':
		if(check_user_exist($_GET['email'])){
			echo 1;exit;
		}
		else{
			echo 2;exit;
		}
	
    break;	
	
	
	case 'account':
		if(isset($_SESSION['loggedIn'])){
			$data = getUserData();
			$file = 'templates/accountupdate.php';
		}
		else { header("location:$base_url");die;}
	
    break;
	
	case 'update_user':
		
		updateUser($_POST);
		$_SESSION['acc_msg']='Your account is updated';
		header("location:$base_url".'account');die;	
	
    break;

	case 'update_email':
		
		updateEmail($_POST);
		session_start();
		$_SESSION['newmailid']=$_POST['email'];
		$_SESSION['email_msg']='Your email address is updated.';
		header("location:$base_url".'login');die;
	
    break;

	case 'update_pass':
		
		$flag = updatePassword($_POST);	
		if($flag==1) $_SESSION['pass_msg']="Please don't enter same password, try another";	
		else $_SESSION['pass_msg']='Your password is updated';	
		header("location:$base_url".'account');die;
    break;

	case 'programs1':
		if(isset($_GET['article'])){
			$data = getProgram($_GET['article']);
			$file = 'templates/program.php';
		}
		else {
		$data = getPrograms();
		$file = 'templates/programs1.php';
		}
	
    break;
	
	case 'programs':
	
		$file = 'templates/programs.php';		
	
    break;
	
	case 'loginquiz':
	
		
	$file = 'templates/loginquiz.php';		
	
    break;
	
	case 'loginquiz1':
		if(isset($_SESSION['loggedIn'])){
			$file = 'templates/loginquiz1.php';	
		}
		else{
			header("location:$base_url".'login');die;
		}
		
	
    break;
	
	case 'submit_query':
	
		$id = submitQuiz();
//print_r("$$".$id."$$");die;
		if($id!='') {
			header("location:$base_url".'index.php?action=quizresult&rid='.$id);die;
		}	
		else {
			$_SESSION['message'] = 'You have recently taken this quiz. You must wait at least 24 hours to access it again.';
			$referrer=$_SERVER['HTTP_REFERER'];
			header("location:$referrer");die;
		}	
		
		exit;
	
    break;	
	
	
	case 'quizresult':
	
		if(isset($_SESSION['loggedIn'])){
			
			$dataq = getLatestQuizResult();
			$file = 'templates/quizresult.php';		
		} else if(isset($_SESSION['quizWithoutLogin'])){
             $file = 'templates/quizresult.php';
        }
		else{
			header("location:$base_url".'login');die;
		}
		
	
    break;
	
	case 'pastquiz':
	
		if(isset($_SESSION['loggedIn'])){
			$data = getPastQuizes();
			$file = 'templates/pastquiz.php';		
		} else if(isset($_SESSION['quizWithoutLogin'])){
                $file = 'templates/quizresult.php';
        }
		else{
			header("location:$base_url".'login');die;
		}
	
		
    break;

	case 'withoutloginquiz':
		
		$file = 'templates/quizwithoutLogin.php';		
	
    break;

	//page come from programs
	case 'resources1':
		if(isset($_GET['page'])) $page=$_GET['page']; else $page =1;
		$type = substr($_GET['type'],0,-1);
		$data =  getResource($type,$_GET['id'],$page);
		
		if(isset($data['posttype']) && $data['posttype']=='quiz'){
			$postid=$data['wp_post_id'];
			
			$datax = explode('<article',($data['resource']['html']));
			$datax1 = explode('<span',$datax[1]);
			$datax2 = explode('>',$datax1[1]);
			$copy = $datax2[1];
			$file = 'templates/loginquiz1.php';		
		}
		else
		$file = 'templates/articles.php';		
	
    break;


	case 'resources':
		
		$file = 'templates/render-search-main-design.php';		
	
    break;
    case 'lifestages':

        $file = 'templates/lifestage.php';

    break;
		
		case 'life-stages':

		case 'retry_quiz':

        retryQuiz($_GET['quiz']);
        $file = 'templates/loginquiz1.php';

    break;


                //$file = 'templates/lifestages.php';
        $file = 'templates/ls.php';
        break;

        case 'GettingStarted':

                //$file = 'templates/lifestages.php';
        $file = 'templates/ls.php';
			break;

    case 'NewBeginnings':

                //$file = 'templates/lifestages.php';
        $file = 'templates/ls.php';
    	break;

    case 'LifesCurveballs':

                //$file = 'templates/lifestages.php';
        $file = 'templates/ls.php';
			break;

    case 'ManagingDebt':

                //$file = 'templates/lifestages.php';
        $file = 'templates/ls.php';
    	break;

    case 'Retirement':

                //$file = 'templates/lifestages.php';
        $file = 'templates/ls.php';
      break;


	case 'webinars':
		
		if(isset($_SESSION['loggedIn'])){			
			$file = 'templates/webinars.php';		
		}
		else{
			$_SESSION['loginmessage']="Please log in to continue.";
			header("location:$base_url".'login');die;
		}
		
	
    break;	
	
	case 'programs-balancetrack':
		
		$file = 'templates/balance-track.php';		
	
    break;	
	
	
	case 'sendmailContact':
/*		if(!empty($_POST['g-recaptcha-response'])) {
			$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
			$responseData = json_decode($verifyResponse);
			if($responseData->success){*/
				sendmailContact();
/*			} else{
				$_SESSION['error_msg'] = 'Please select captch.';
				header("location:$base_url".'contact');die;
			}
		}
		else{
				$_SESSION['error_msg'] = 'Please select captch.';
				header("location:$base_url".'contact');die;
			}*/
	
    break;	
	
	
	default:
	
	$file = 'templates/main.php';
	
	break;
  
}

include 'main.php';

?>
