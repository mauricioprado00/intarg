/**
 * @author Mauricio Prado
 */
(function($){
	$.fn.timemouse = function(mouseover, mouseout, time){
		if(typeof(mouseover)=='object'){
			if(mouseover.mouseover==null)
				return this;
			if(mouseover.mouseout!=null){
				if(typeof(mouseover.mouseout)=='function')
					mouseout = mouseover.mouseout;
				else return this
			}
			if(mouseover.time!=null){
				time = parseInt(mouseover.time);
			}
			if(mouseover.mouseover!=null){
				if(typeof(mouseover.mouseover)=='function')
					mouseover = mouseover.mouseover;
				else return this;
			}
		}
		if(!time)
			time = 500;
		this
			.mouseover(function(){
				if(!this.count_hover){
					this.count_hover = 0;
					try{
						mouseover.apply(this);
					}catch(e){}
				}
				this.count_hover ++;
			})
			.mouseout(function(){
				this.count_hover --;
				this.count_hover = this.count_hover<0?0:this.count_hover;
				var that = this;
				setTimeout(
					function(){
						if(that.count_hover==0){
							try{
								mouseout.apply(that);
							}catch(e){}
							//jQuery(that).removeClass('show_filtro_hover');
						}
					},
					time
				);
			})
		return this;
	}
}(jQuery));