$(document).ready(function(){

	 	$('.loginbut').on("click",function(){
	 		$.featherlight({iframe: 'loginframe.php',loading:'Please wait...', iframeMaxWidth: '100%', iframeWidth: 500,iframeHeight: 300});
	 	});

	 	$('.registerbut').on("click",function(){
	 		$.featherlight({iframe: 'signupframe.php', loading:'Please wait...',iframeMaxWidth: '100%', iframeWidth: 500,iframeHeight: 400});
	 	});
		
		$('.product_slider').slick({
			dots: false,
  	 		infinite: false,
  			speed: 300,
  			slidesToShow: 4,
  			slidesToScroll: 4,
  			responsive: [
    			{
	      			breakpoint: 1024,
	      			settings: {
	        			slidesToShow: 3,
	        			slidesToScroll: 3,
	        			infinite: false,
	        			dots: false
	      			}
    			},
    			{
	      			breakpoint: 600,
	      			settings: {
	        			slidesToShow: 2,
	        			slidesToScroll: 2
	      			}
    			},
    			{
	      			breakpoint: 480,
	      			settings: {
	        			slidesToShow: 1,
	        			slidesToScroll: 1
	      			}
    			}
			]
		});
		
		AOS.init();

		$('.check-it').on("click",function(e){
			e.preventDefault();
			if(uShopper.isLogin){
				window.location=$(this).attr("href");
			}
			else{
				$('.loginbut').trigger('click');
			}
		});

		$('.item_view>span').on("click",function(){

			if(uShopper.isLogin){
			
				uShopper.notify=noty({
							text:'<div class="activity-item"><div class="activity"> Adding Item to Wishlist </div> </div>',
							layout:"topCenter",
							type:"notification",
							progressBar:true,
							animation: {
						    	open: 'animated bounceInLeft', 
						    	close: 'animated bounceOutLeft', 
					    		easing: 'swing',
						    	speed: 500 
							}
						});

				var json={
					"id":"<?php echo $user->getUID() ?>",
					"email":"<?php echo $user->getUEmail() ?>",
					"data":"add",
					"pid":this.dataset.itemId,
					"type":this.dataset.itemType
				};
				
				var request=$.ajax({
					url:"android/wishlistmanager.php",
					method:"POST",
					dataType:"text",
					data:json,
				});

				request.done(function(data){
	
					data=JSON.parse(data);

					if(data.success){
						uShopper.notify.setText("Item Added to Wishlist");
						//uShopper.notify.setType("success");
						uShopper.notify.setTimeout(2000);
					}
				});
				request.fail(function(jqXhr,data,error){
					console.log(error);
				});
			}
			else{
				$('.loginbut').trigger('click');
			}
		});
	});