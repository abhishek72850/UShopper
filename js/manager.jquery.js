(function($){
	
	$.fn.manager=function(opts){

		var defaults={
			task:null,
			url:"",
			data:null,
			callback:false,
			callbackfunc:function(){}
		};

		var config=$.extend({},defaults,opts);
		
		console.log(this.context);
		var image=this.context;
		$(this.context).prop('disabled',true);

		var request=$.ajax({
			url:config.url,
			method:"POST",
			dataType:"text",
			data:config.data
		});
		var n=notification('Please Wait.....','info');

		request.done(function(data){
			n.close();
			//console.log(data);
			$(this.context).prop('disabled',false);
			data=JSON.parse(data);
			if(data.success){
				if(config.task==='cart_add'){
					notification('Item Added to cart','success').setTimeout(2000);
					$('#cartCounter').text( parseInt($('#cartCounter').text())+1 );
				}
				else if(config.task==='cart_update'){
					notification('Cart Updated Successfully','success').setTimeout(2000);
					$('#cartCounter').text( parseInt($('#cartCounter').text())+1 );
				}
				else if(config.task==='cart_remove'){
					notification('Item Removed to cart','success').setTimeout(2000);
					$('#cartCounter').text( parseInt($('#cartCounter').text())-1 );
				}
				else if (config.task==='wishlist_add') {
					
					image.src="images/fav_yes.svg";
					image.dataset.task="wishlist_remove";
					image.dataset.taskCommand="delete";
					//console.log(image);
					if(image.dataset.itemType=="product")
						notification('Item Added to wishlist','success').setTimeout(2000);
					else
						notification('Shop Added to Favourite','success').setTimeout(2000);
				}
				else if(config.task==='wishlist_remove'){
					
					image.src="images/fav_no.svg";
					image.dataset.task="wishlist_add";
					image.dataset.taskCommand="add";
					//console.log(image);
					if(image.dataset.itemType=="product")
						notification('Item Removed from wishlist','success').setTimeout(2000);
					else
						notification('Shop Removed from Favourite','success').setTimeout(2000);
				}
				if(config.callback)
					config.callbackfunc(config);
			}
			else{
				if(config.task==='cart_add'){
					notification(data.error,'error');	
				}
				else if(config.task==='cart_update'){
					notification(data.error,'error');	
				}
				else if(config.task==='cart_remove'){
					notification(data.error,'error');	
				}
				else if (config.task==='wishlist_add') {
					notification('Unable to add item to wishlist','error');
				}
				else if(config.task==='wishlist_remove'){
					notification('Unable to remove item from wishlist','error');
				}	
			}
		});
		
		request.fail(function(jqXhr,data,error){
			notification(error,'error');
		});
	};

	var notification=function(msg,type){
		var n=noty({
			text:'<div class="activity-item"><div class="activity">'+msg+'</div> </div>',
			layout:"topCenter",
			type:type,
			progressBar:true,
			animation: {
		    	open: 'animated bounceInLeft', 
		    	close: 'animated bounceOutLeft', 
	    		easing: 'swing',
		    	speed: 500 
			}
		});

		return n;
	}

})(jQuery);