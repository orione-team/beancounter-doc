<?php
    /**
     * agt Send Mail Class
     * 
     */
	class agtSendMail {
		
		/** 
		 * @var your domain
		 */
		public $yDomain  = 'yourdomain.com';
		
		/**
		 * @var Your Email
		 */
		public $adminMail = 'yourmail@domain.com';
		
		/**
		 * @var Message Subject || Title
		 */
		public $subject = 'Contact Form ("%s")';
		
		/**
		 * @var message body
		 */
		public $mBody = '<div> <p>Name : %1$s </p> <p>Last Name : %2$s </p> <p> Email : <a href="mailto:%3$s">%3$s</a> </p> <p> Message : %4$s </p> </div>';
		
		/**
		 * @var Mail Header
		 */
		public $mHeader = '';
						
		
		public function __construct(){
			
			$_POST = array_map('strip_tags',  $_POST );
			$_POST = array_map('stripslashes',  $_POST );
			
			
			if( self::CheckValue( $_POST ) ):
				
				self::SendMail();
				
			endif;
			
		}
		
		public function CheckValue( $value ){
			
			$ve = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
			$rn = "\r\n";
	
			if( ereg( $ve, $value['email']) ):
			
				$this-> mBody = sprintf( $this->mBody , $value['firstname'], $value['lastname'],$value['email'],$value['y_comments'] );								
				$this->subject = sprintf( $this->subject, $value['firstname'] .' '. $value['lastname']);
								 
				$this->mHeader  = 'From: '.$value['firstname'] .' '. $value['lastname'].' <'. $value['email'] .'> '.$rn; 				
				$this->mHeader .= 'MIME-Version: 1.0'. $rn; 
				$this->mHeader .= 'Content-type: text/html; charset=utf-8';
				 
				return true;
				
			else:
				
				return false;
				
			endif;
			
		}
		
		public function SendMail(){
			
			try{
				
				if( !mail($this->adminMail, $this->subject, $this->mBody, $this->mHeader) ){
					
					throw new Exception('There was a problem and the message was probably not sent.');
					
				}else{
					
					sleep(2); // send animation 
					
					echo json_encode(array('sent' => true));
				}
			
			}catch(Exception $e){
				
				echo json_encode(array('error' => $e->getMessage()));				
				
			}
			
		}
	}
	
	
	/* * *************   ACTION  ***************** */
	if( isset( $_POST ) ): new agtSendMail; endif;
	/* * ***************************************** */
	
?>
