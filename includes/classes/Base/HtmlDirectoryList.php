<?
class Base_HtmlDirectoryList extends Base_FileSystemScanner{
	private $ident = '';
	public function onBegin(){
		$this->outputStyles();
	}
	public function onFinalize($valor){
		?> 
<?=$this->ident?>	<b>finalizado <?=$valor?></b><?
	}
	public function onOpenDirectory(){
		?> 
<?=$this->ident?><span class='directorio'>
<?=$this->ident?>	<span class='dir_name'>
<?=$this->ident?>		<b><?=$this->getCurrentFileName()?></b>
<?=$this->ident?>	</span>
<?=$this->ident?>	<br><?
		$this->ident = str_repeat("\t", $this->getDeepLevel());
		return(true);
	}
	public function onCloseDirectory(){
		$this->ident = str_repeat("\t", $this->getDeepLevel());
		?> 
<?=$this->ident?></span><?
	}
	
	public function onScanner(){
		if(!$this->isDirectory()){
			?> 
<?=$this->ident?><?=$this->getCurrentDirectory(true)?> 
<?=$this->ident?><b>
<?=$this->ident?>	<?=$this->getCurrentFileName(false)?> 
<?=$this->ident?></b>
<?=$this->ident?>--file<br><?
		}
		return(true);//continuar escaneando o abortar
	}
	
	
	private function outputStyles(){
		?> 
<style>
.directorio{
	margin-top: 10px;
	margin-left: 50px;
	display:block;
}
.directorio b{
	color:rgb(20,100,20);
}
.dir_name b{
	color:rgb(20,20,150);
}

</style><?
	}
}

/** /
//	TESTEO
$x = new Base_HtmlDirectoryList();
echo "<hr>";
$x->Start(dirname(__FILE__).'/../..',1);

/**/