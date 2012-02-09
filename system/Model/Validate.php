<?php
class Model_Validate extends Engine_Model {

    public function login($login) {
    	if (isset($this->registry["module_user"])) {
	        $err = null;
	
			if ( !preg_match( '/^[0-9A-Za-z_-]{4,31}$/', $login ) ) {
				$err = 'Логин должен состоять из символов английского алфавита, быть длиной не менее 4 и не более 31 символов! Из специальных символов разрешается использовать символы: "-", "_"';
			}
	        
	        if ($err == null) {
	            if ($this->registry["module_user"]->issetLogin($login)) {
	                $err = "Пользователь с таким логином уже зарегистрирован!";
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
			$err = 'Пароль должен состоять из символов английского алфавита, быть длиной не менее 6 и не более 31 символов! Из специальных символов разрешается использовать символы: "-", "_"';
		}
        
        if ($err != null) {
            return $err;
        }
    }
}    
?>