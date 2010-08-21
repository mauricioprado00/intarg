<form id="<?=$id=strtr($this->getNameInLayout(),array('.'=>'','#'=>''))?>" 
	<?if($this->hasEnctype()):?>
	enctype="<?=$this->getEnctype()?>"<?
	endif?>
	method="<?=$this->getMethod()?>" 
	action="<?=$this->getUrl($this->getActionUrl())?>"><?=$this->getChildHtml()?> 
	<input type='hidden' name='isajaxrequest' id='isajaxrequest' value='0' />
</form><?

if($this->hasValidadorJs()||Admin_App::getInstance()->getModoAjax()){
	$options = array(
		'target'=>$this->getAjaxTarget(),
	);
	if($this->hasAjaxReplaceWith()){
		$options['replaceWith'] = true;//esto es una modificacion del plugin jquery.form.js en linea 225
	}
?> 
<script>
	jQuery(document).ready(function(){
		try{
		<?
		if($this->hasValidadorJs()){
			$validadores = explode(',', $this->getValidadorJs());
			foreach($validadores as $validator){?> 
		jQuery('#<?php print $id; ?>').multivalidator(<?php print $validator; ?>);<?
			}
		}
		if($this->getAjaxMethod()=='ajaxSubmit'):?> 
		jQuery('#<?=$id?>').submit(function(){<?
		if(Admin_App::getInstance()->getModoAjax()){?> 
			if(!jQuery('#<?php print $id; ?>').multivalidate())
				return false;
			var options = <?=json_encode($options);?>; 
//esto estaba mal, porque si un validador modifica los valores estos no se envian modificados con el beforeSubmit
//			options.beforeSubmit = function(){
//				return jQuery('#<?php print $id; ?>').multivalidate();
//			}
			$("#isajaxrequest", this).attr("value", 1);
			$(this).ajaxSubmit(options); 
			return(false);<?
		}
		else{?> 
			return jQuery('#<?php print $id; ?>').multivalidate();<?	
		}?> 
		
		});
		<?elseif($this->getAjaxMethod()=='ajaxForm'):?> 
		var options = <?=json_encode($options);?>; 
		options.beforeSubmit = function(){
			return jQuery('#<?php print $id; ?>').multivalidate();
		}; 
		$('#<?=$id?>').ajaxForm(options);
		<?endif?>
		}
		catch(e){
			alert(e);
		}
	});
</script>
<?	
}

?>