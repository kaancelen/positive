<?php

include_once (__DIR__.'/Util/init.php');

$userService = new UserService();
$agentList = $userService->allTypeOfUsers(User::BRANCH);

$agentService = new AgentService();

foreach ($agentList as $agent){
	$agentService->upsertRelation($agent[User::ID], $agent[User::KOMISYON_RATE], 0,0,0,0);
}

?>