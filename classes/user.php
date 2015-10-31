<?php

class User{
	const ID = "ID";
	const NAME = "NAME";
	const CODE = "CODE";
	const HASH = "HASH";
	const SALT = "SALT";
	const ROLE = "ROLE";
	const DESCRIPTION = "DESCRIPTION";
	const FIRST_LOGIN = "FIRST_LOGIN";
	const CREATION_DATE = "CREATION_DATE";
	const ALLOWED_COMP = "ALLOWED_COMP";
	const CHANGE_AGENT = "CHANGE_AGENT";
	const ACTIVE = "ACTIVE";
	
	const FIRST_LOGIN_FLAG = 1;
	
	const USER_NOT_FOUND = -1;
	const WRONG_PASS = 0;
	const ADMIN = 1;
	const PERSONEL = 2;	//Teknik
	const BRANCH = 3;	//Acente
	const FINANCE = 4;
	
	const ACTIVE_USER = 0;
	const PASSIVE_USER = 1;
}

?>