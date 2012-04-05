<?php
class Model_Validate extends Engine_Model {

    public function login($login) {
    	if (isset($this->registry["module_user"])) {
	        $err = null;
	
			if ( !preg_match( '/^[0-9A-Za-z_-]{4,31}$/', $login ) ) {
				$err = 'Login should consist of symbols of the English alphabet, to be length not less than 4 and no more than 31 symbols! From special symbols it is authorized to use symbols: "-", "_"';
			}
	        
	        if ($err == null) {
	            if ($this->registry["module_user"]->issetLogin($login)) {
	                $err = "Such user is already registered!";
	            }
	        }
	
	        if ($err != null) {
	            return $err;
	        }
    	}
    }
    
    public function password($password) {
        $err = null;
        
        if ( !preg_match( '/^[0-9A-Za-z_-]{6,31}$/', $password ) ) {
			$err = 'Password should consist of symbols of the English alphabet, to be length not less than 6 and no more than 31 symbols! From special symbols it is authorized to use symbols: "-", "_"';
		}
        
        if ($err != null) {
            return $err;
        }
    }

    public function quota_val($quota_val) {
        $err = null;
        

		if ( preg_match( '/^0$/', $quota_val ) ) {
			$err = 'Quota value should be a positive value';
		}
        elseif ( !preg_match( '/^[0-9]+$/', $quota_val ) ) {
			$err = 'Quota value should consist of numeric characters';
		}
        
        if ($err != null) {
            return $err;
        }
    }

}    
?>