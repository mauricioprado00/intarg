<?php 
class Core_Serial_Helper extends Core_Singleton{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	static function GenerateAlphanumeric($nuevo,$loop=1){		
		static $suma = array();
		static $suma2 = array();
		//var_dump($nuevo,$loop);
		if($loop<1)
			return(false);
		if(count($suma)==0){
			$abcd    = "0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz";
			$abcdinc = "123456789AAAAAAAABCDEFGHIJKLMNOPQRSTUVWXYZaaaaaaabcdefghijklmnopqrstuvwxyz0";
			$len = strlen($abcd);
			for($i=0;$i<$len;$i++)
				$suma[$abcd[$i]] = $abcdinc[$i];
	
			$abcdn    = "0123456789";
			$abcdninc = "1234567890";
			$len = strlen($abcdn);
			for($i=0;$i<$len;$i++)
				$suma2[$abcdn[$i]] = $abcdninc[$i];
		}
	
		if($nuevo==""){
			$nuevo = "100000000000";
		}
	
		$carry = false;
		$len = strlen($nuevo);
		for($i=0;$i<$len;$i++){
			$nuevo[$len-1-$i] = ($i<8?$suma[$nuevo[$len-1-$i]]:$suma2[$nuevo[$len-1-$i]]);
			if($nuevo[$len-1-$i]!=='0')break;
		}
		if($loop==1)
			return($nuevo);
		else return(self::GenerateAlphanumeric($nuevo,--$loop));
	}
}
?>