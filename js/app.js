$(document).ready(function(){

	$('.loginbut').on("click",function(){
 		$.featherlight({iframe: 'loginframe.php',loading:'Please wait...', iframeMaxWidth: '100%', iframeWidth: 500,iframeHeight: 300});
 	});

 	$('.registerbut').on("click",function(){
 		$.featherlight({iframe: 'signupframe.php', loading:'Please wait...',iframeMaxWidth: '100%', iframeWidth: 500,iframeHeight: 400});
 	});

	$('.menu-stick').on('click',function(){
		
		if(parseInt($('.menu-bar').css('top'))<80){
			$(this).addClass('menu-stick-active');
			$(this).removeClass('menu-stick-disable');
			$('.menu-bar').stop().animate({'top':'80px'},300);
		}
		else{
			$(this).removeClass('menu-stick-active');
			$(this).addClass('menu-stick-disable');
			$('.menu-bar').stop().animate({'top':'40px'},300);
		}
	});

	$('.check-it').on("click",function(e){
		e.preventDefault();
		if(uShopper.isLogin){
			if($(this).attr("href"))
				window.location=$(this).attr("href");
			else
				window.location=this.dataset.href;
		}
		else{
			$('.loginbut').trigger('click');
		}
	});

	if(typeof AOS!=="undefined")
		AOS.init();

	if(typeof jQuery().slick!=="undefined")
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

	if(typeof jQuery().barrating!=="undefined"){
		$('.static-rate').barrating({
	        theme: 'css-stars',
	        readonly:true
		});
		$('.dynamic-rate').barrating({
	        theme: 'css-stars',
		    initialRating:0,
		    allowEmpty:false,
		});

	}

	$('.manage').on('click',function(){

		if(uShopper.isLogin){

			var link="";
			if(typeof this.dataset.changeLink==="undefined"){
				link='android/user_product/cartmanager.php';
			}
			else{
				link='../../android/user_product/cartmanager.php'
			}
  			var call=false;

			$(this).manager({ 
				task:this.dataset.task,
	  			url:link,
	  			data:{
	  				"id":uShopper.id,
	  				"email":uShopper.email,
	  				"data":this.dataset.taskCommand,
	  				"pid":this.dataset.itemId,
	  				"type":this.dataset.itemType ,
	  				"quantity":'1'
	  			},
	  			callback:call,
	  			callbackfunc:function(){

	  			}
			});

			
		}
		else{
			$('.loginbut').trigger('click');
		}
	});

	$('.buynow').on('click',function(){

		if(uShopper.isLogin){
			window.location=this.dataset.href;
		}
		else{
			$('.loginbut').trigger('click');	
		}
	});

	$('.fav_ico').on("click",function(){

		if(uShopper.isLogin){
			
			var link="";
			call=false;
			if(typeof this.dataset.changeLink==="undefined"){
				link='android/user_product/wishlistmanager.php';
			}
			else{
				link='../../android/user_product/wishlistmanager.php'
				call=true;
			}
			
			$(this).manager({
				task:this.dataset.task,
	  			url:link,
	  			data:{
	  				"id":uShopper.id,
	  				"email":uShopper.email,
	  				"data":this.dataset.taskCommand,
	  				"pid":this.dataset.itemId,
	  				"type":this.dataset.itemType
	  			},
	  			callback:call,
	  			callbackfunc:function(){
	  				window.location.reload();
	  			}	
			});

			
			
		}
		else{
			$('.loginbut').trigger('click');
		}
	});

	window.swapToSignup=function(){
		console.log("Parent called");
		var current = $.featherlight.current();
		
		current.close();
		current.close();
		
		$('.registerbut').trigger('click');
	}
});