/**
 * @author Mauricio Prado
 */
(function($){
	$.fn.multivalidator = function(callback, allwayscall){
		this.each(function(){
			if(this.multivalidator==null){
				this.multivalidator = {};
				this.multivalidator.validators = {};
				this.multivalidator.validate = {};
				this.multivalidator.validate_async = {};
				var that = this;
				this.multivalidator.add = function(nombre, callback, allwayscall){
					if(that.multivalidator.validate[nombre]==null){
						that.multivalidator.validate[nombre] = function(){
							var return_val = true;
							for(idx in that.multivalidator.validators[nombre]){
								var validator = that.multivalidator.validators[nombre][idx];
								var call_it = return_val || validator.allwayscall;
								//window.console.log(['return_val', return_val]);
								//window.console.log(['allwayscall', validator.allwayscall]);
								//window.console.log(['call_it', call_it]);
								if(call_it){
									try{
										var ret = validator.callback();
										//window.console.log(['ret', ret]);
									}catch(e){}
									if(return_val&&ret==false){
										//window.console.log(ret);
										return_val = false;
									}
								}
							}
							return return_val;
						}
						that.multivalidator.validate_async[nombre] = function(callbackresult,idx_validator, return_val){
							if(return_val==null)
								return_val = true;
							if(idx_validator==null)
								idx_validator = 0;
							if(that.multivalidator.validators[nombre][idx_validator]==null){
								if(return_val==true){
									jQuery.unblockUI();
								}
								callbackresult(return_val);
							}
							else{
								var idx = idx_validator;
								var validator = that.multivalidator.validators[nombre][idx];
								var __doit = function(){
									//window.console.log('recibo: '+return_val);
//									window.console.log(idx_validator);
//									window.console.log(validator.callback.toString());
									var call_it = return_val || validator.allwayscall;
									//window.console.log(['return_val', return_val]);
									//window.console.log(['allwayscall', validator.allwayscall]);
									//window.console.log(['call_it', call_it]);
									if(call_it){
										try{
											var ret = validator.callback();
											//window.console.log(['ret', ret]);
										}catch(e){}
										if(return_val&&ret==false){
											//window.console.log(ret);
											return_val = false;
										}
									}
									//window.console.log('envio: '+return_val);
									that.multivalidator.validate_async[nombre](callbackresult, idx+1, return_val);
								}
								if(return_val&&validator.callback.wait_message!=null){
									show_messages([validator.callback.wait_message], true, null, 
										{
											onBlock:__doit
										}, 'img_wait'
									);
								}
								else{
									__doit();
								}
							}
							return null;
						}
						that.multivalidator.validators[nombre] = [];
					}
					if(allwayscall==null)allwayscall = false;
					allwayscall = allwayscall?true:false;
					var validator = {
						callback:callback,
						allwayscall: allwayscall
					};
					that.multivalidator.validators[nombre].push(validator);
				}
			}
			this.multivalidator.add('kradkk', callback, allwayscall);
		});
	}
	$.fn.multivalidate = function(callbackresult){
		var return_val = true;
		this.each(function(){
			if(this.multivalidator==null)
				return true;
			if(callbackresult!=null){
				var ret = this.multivalidator.validate_async['kradkk'](callbackresult);
				return null;
			}
			else{
				var ret = this.multivalidator.validate['kradkk'](callbackresult);
			}
			if(ret==false)
				return_val = false;
		});
		return return_val;
	}

}(jQuery));