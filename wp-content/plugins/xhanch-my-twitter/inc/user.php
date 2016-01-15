<?php	
	function xmt_get_role(){
		global $current_user;
		get_currentuserinfo();
		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);
		return strtolower($user_role);
	};
?>