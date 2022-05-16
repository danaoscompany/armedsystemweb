<?php
require ('fcm.php');

class Test extends CI_Controller {
	
	public function test2() {
		FCM::send_message("cWe4eljySXq3X5DTEw0QCY:APA91bGYJVe_jTYaJozAODDrQslCKoKfNseH3m6_Fx-9nUIM3FSxDamX0jLAJ67v_ualwCVgK-IP1xP_QhmXW548_PsXDUISfN_HGMtifsB2FES6DB6u_ZtwKrUNz-ff36Qtmz4hS8EB", 1, 1, "Hello", "world", array(
			'alarm_type' => 1,
			'alarm_on' => 1,
			'receive_alerts' => 1
		));
	}
	
	public function a() {
		echo base_url();
	}
	
	public function fcm() {
		FCM::send_message("dF4y-3b7Qnef4dvBBFNEw2:APA91bFypwLQnLpUhWQB7JzllP8DPo5G8rUmz_Otn4aYuwInIcO1vljPpD5iM1rdI-Fx5DTcWD4sKzraj6WZrZ2gxmOI6wsszLs9oc19SZl-nCsnP6p6AjDoHyCgEPaTZyCAsJ0WBXBX", 1, 1, 'Pesan baru',
        	        	"Sedang ada pelaksanaan alarm dari komandan",
        	        	array(
        	        	    'alarm_on' => 1
        	        	)
        	        );
	}
}
