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
	$.fn.multivalidate = function(){
		var return_val = true;
		this.each(function(){
			if(this.multivalidator==null)
				return true;
			var ret = this.multivalidator.validate['kradkk']();
			if(ret==false)
				return_val = false;
		});
		return return_val;
	}

}(jQuery));