if(window.trim == null){
	window.trim = function (str){
	   return str.replace(/^\s*|\s*$/g,"");
	}
}