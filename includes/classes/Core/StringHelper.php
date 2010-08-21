<?
class Core_StringHelper extends Base_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	static public function iSubstr($text,$length=64,$tail="...") {
		$text = trim($text);
		$txtl = strlen($text);
		if($txtl > $length) {
		for($i=1;$text[$length-$i]!=" ";$i++) {
			if($i == $length) {
				return substr($text,0,$length) . $tail;
			}
		}
		for(;$text[$length-$i]=="," || $text[$length-$i]=="." || $text[$length-$i]==" ";$i++) {;}
			$text = substr($text,0,$length-$i+1) . $tail;
		}
		return $text;
	}
}
?>