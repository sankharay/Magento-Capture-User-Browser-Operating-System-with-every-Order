<?php
class SHAPP_Userstamp_Model_Observer {

    public function updateUserstamp(Varien_Event_Observer $observer) {
    	$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
        $order = $observer->getEvent()->getOrder();
        $increment_id = $order->getincrement_id();
		$user_os = $this->loadOS($user_agent);
        $user_browser = $this->loadBrowser($user_agent);
		$user_browser = $this->loadBrowser($user_agent)." ".$this->getVersion($user_browser,$user_agent);
		$orders = Mage::getModel('sales/order')->loadByIncrementId($increment_id);
		if(Mage::getSingleton('admin/session')->isLoggedIn()){
        Mage::getSingleton('admin/session')->getData();
		$user = Mage::getSingleton('admin/session');
		if($user->getUser()->getUsername()){
		$userId = $user->getUser()->getUsername();
		$orders->setData('ccare_username',"$userId");
		}}
		$orders->setData('user_operatingsystem',"$user_os");
		$orders->setData('user_browser',"$user_browser");
		$orders->save();
    }

    function loadOS($user_agent) {
    $os_platform    =   "Unknown OS Platform";
    $os_array       =   array(
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );
    foreach ($os_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }
    }
    return $os_platform;
}

function loadBrowser($user_agent) {
    $browser        =   "Unknown Browser";
    $browser_array  =   array(
                            '/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                            '/mobile/i'     =>  'Handheld Browser'
                        );
    foreach ($browser_array as $regex => $value) { 
        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }
    }
    return $browser;
}

function getVersion($ub,$u_agent){
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // no match continue
    }
    $i = count($matches['browser']);
    if ($i != 1) {
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
    if ($version==null || $version=="") {$version=" ";}

    return $version;
        
    }

}
?>