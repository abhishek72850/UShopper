$(document).ready(function(){

		if(!uShopper.noProduct){
			$('.no-product').hide();
		}
		else{
			$('.no-product').show();
		}

		$("#loadsync").hide();

		$( ".pslider" ).slider({
			range: true,
			values: [ 500, 1500 ],
			min:50,
			max:5000,
			range:true,
			step:100,
			slide:function(event,ui){
				$('input[name="slidermin"]').val(ui.values[0]);
				$('input[name="slidermax"]').val(ui.values[1]);
			},
			change:function(event,ui){
				$('input[name="slidermin"]').val(ui.values[0]);
				$('input[name="slidermax"]').val(ui.values[1]);

				uShopper.priceFilter=true;
				uShopper.pricemin=ui.values[0];
				uShopper.pricemax=ui.values[1];
				uShopper.pendingTag="price";
				uShopper.offset=20;

				AsyncFilterSearch(true);	
			}
		});

		$('.load').on('click',function(){

			$('#loadsync').show();
			$('.unloadsync').hide();
			var json={
				"offset":uShopper.offset+1
			}
			//console.log(uShopper.offset);
			AsyncFilterSearch(false,json);
		});

		$('.load-shop').on('click',function(){

			$('#loadsync').show();
			$('.unloadsync').hide();

			var json={
				'offset':uShopper.offset+1,
				'search':'shop_list',
				'data':uShopper.search
			}

			var request=$.ajax({
					url:"android/product_shop/searchexp.php",
					method:"POST",
					dataType:"text",
					data:json,
				});

			request.done(function(data){ 
				console.log(data);
				data=JSON.parse(data);
				
				$('#loadsync').hide();
				$('.unloadsync').show();
				
				if(data.success){
					 uShopper.offset=uShopper.offset+20;
					 loadShop(data.list);
				}
				else{
					$('.no-product').show();
					uShopper.noProduct=true;
				}

			});

		});

		var AsyncFilterSearch=function(detach,opts={}){
			
			if(detach)
				$(".loadme").fadeToggle(500);

			var json={
					"search":uShopper.sort+","+getFilterType(),
					"data":uShopper.search,
					"discount":getFilterDiscount(),
					"pricemin":getFilterPriceMIN(),
					"pricemax":getFilterPriceMAX(),
					"srate":getFilterSrate(),
					"prate":getFilterPrate()
				};

			json=$.extend({},json,opts);	

			var request=$.ajax({
					url:"android/product_shop/searchexp.php",
					method:"POST",
					dataType:"text",
					data:json,
				});

			request.done(function(data){ 
			
				console.log(data);
				data=JSON.parse(data);
				
				if(detach)
					$(".loadme").fadeToggle(500);
				else{
					$('#loadsync').hide();
					$('.unloadsync').show();
				}

				if(data.success){
					if(detach){
						$('.no-product').hide();
						$('.loadmore').show();	
					}
					else{
						uShopper.offset=uShopper.offset+20;
					}
					var stateObj={source:"search.php"};
					//console.log(uShopper.sort+uShopper.filter);
					if(uShopper.sort!="0"&&getFilterType()!="0"){
						history.pushState(stateObj,"search","search.php?searchfor="+uShopper.search+"&sby=product&sort="+uShopper.sort+"&filter="+getFilterType()+"&pricemin="+uShopper.pricemin+"&pricemax="+uShopper.pricemax+"&discount="+uShopper.disValue+"&prate="+uShopper.prateValue+"&srate="+uShopper.srateValue);
					}
					else if(getFilterType()!="0"){
						history.pushState(stateObj,"search","search.php?searchfor="+uShopper.search+"&sby=product&filter="+getFilterType()+"&pricemin="+uShopper.pricemin+"&pricemax="+uShopper.pricemax+"&discount="+uShopper.disValue+"&prate="+uShopper.prateValue+"&srate="+uShopper.srateValue);	
					}
					else if(uShopper.sort!="0"){
						history.pushState(stateObj,"search","search.php?searchfor="+uShopper.search+"&sby=product&sort="+uShopper.sort);	
					}
					
					if(uShopper.pendingTag!=""&&detach){
						addFilterTag();
					}
					loadProduct(data.list,detach);
				}
				else{
					if(detach){
						addFilterTag();
						$(".product-container>.item").detach();	
					}
					else{
						$('.loadmore').hide();
					}
					$('.no-product').show();
					uShopper.noProduct=true;
				}
			});
			request.fail(function(jqXhr,data,error){
				console.log(error);
			});
		}
		
		var checkTag=function(tag){
			if($('.filter-tags').children('.ftag').length>0){
				
				for(var i=0;i<=$('.filter-tags').children('.ftag').length-1;i++){
					if($('.ftag').eq(i).hasClass(tag)){
						return true;
					}
				}
				
				return false;
			}
			else{
				
				return false;
			}
		}

		var addFilterTag=function(){
		
			var text="";
			if(uShopper.pendingTag==="price"){
				text="Price Rs."+uShopper.pricemin+" - "+uShopper.pricemax;
			}
			else if(uShopper.pendingTag==="discount"){
				text="Discount More Than "+uShopper.disValue+"%";
			}
			else if(uShopper.pendingTag==="prate"){
				text="Product Rate More Than "+uShopper.prateValue;
			}
			else if(uShopper.pendingTag==="srate"){
				text="Shop Rate More Than "+uShopper.srateValue;	
			}

			if(uShopper.pendingTag!=""&&uShopper.pendingTag!=null){
				if(!checkTag(uShopper.pendingTag)){
					var ftag=$("<div></div>",{
						"class":"ftag "+uShopper.pendingTag,
						"data-filter-tag":uShopper.pendingTag
					});
					var tagname=$("<div></div>",{
						"text":text
					});
					var remove=$("<span></span>",{
						"text":"x",
						"class":"remove-tag",
						click:function(){
							$('.'+$(this).parent().attr("data-filter-tag")).remove();
							removeFilter($(this).parent().attr("data-filter-tag"));
						}
					});
					ftag.append(tagname);
					ftag.append(remove);
					$('.filter-tags').append(ftag);
					uShopper.pendingTag="";
				}
				else{
					$('.'+uShopper.pendingTag+'>div').text(text);
					uShopper.pendingTag="";
				}
			}
		}

		$('.remove-tag').on('click',function(){
			
			//console.log("clicked");
			$('.'+$(this).parent().attr("data-filter-tag")).remove();
			removeFilter($(this).parent().attr("data-filter-tag"));

		});

		var removeFilter=function(filter){
			if(filter=="price"){
				uShopper.priceFilter=false;
				uShopper.pricemax=0;
				uShopper.pricemin=0;	
			}
			else if(filter=="discount"){
				uShopper.disFilter=false;
				uShopper.disValue=0;
			}
			else if(filter=="prate"){
				uShopper.prateFilter=false;
				uShopper.prateValue=0;	
			}
			else if(filter=="srate"){
				uShopper.srateFilter=false;
				uShopper.srateValue=0;
			}

			uShopper.offset=20;
			AsyncFilterSearch(true);
		}

		var getFilterType=function(){
			uShopper.whichFilter="";

			if(uShopper.priceFilter){
				uShopper.whichFilter="price";
			}
			if(uShopper.disFilter){
				uShopper.whichFilter=uShopper.whichFilter+((uShopper.whichFilter=="")?"discount":"_discount");
			}
			if(uShopper.prateFilter){
				uShopper.whichFilter=uShopper.whichFilter+((uShopper.whichFilter=="")?"prate":"_prate");
			}
			if(uShopper.srateFilter){
				uShopper.whichFilter=uShopper.whichFilter+((uShopper.whichFilter=="")?"srate":"_srate");
			}
			if(uShopper.whichFilter==""){
				uShopper.whichFilter="0";
				uShopper.pendingTag="";
			}

			return uShopper.whichFilter;
		}

		var getFilterDiscount=function(){
			if(uShopper.disFilter){
				return uShopper.disValue;
			}
			else{
				return 0;
			}
		}
		var getFilterPrate=function(){
			if(uShopper.prateFilter){
				return uShopper.prateValue;
			}
			else{
				return 0;
			}
		}
		var getFilterSrate=function(){
			if(uShopper.srateFilter){
				return uShopper.srateValue;
			}
			else{
				return 0;
			}
		}
		var getFilterPriceMIN=function(){
			if(uShopper.priceFilter){
				return uShopper.pricemin;
			}
			else{
				return 0;
			}
		}
		var getFilterPriceMAX=function(){
			if(uShopper.priceFilter){
				return uShopper.pricemax;
			}
			else{
				return 0;
			}
		}

		$( ".radioset" ).buttonset();

	 	$('.loginbut').on("click",function(){
	 		$.featherlight({iframe: 'loginframe.php',loading:'Please wait...', iframeMaxWidth: '100%', iframeWidth: 500,iframeHeight: 300});
	 	});

	 	$('.registerbut').on("click",function(){
	 		$.featherlight({iframe: 'signupframe.php', loading:'Please wait...',iframeMaxWidth: '100%', iframeWidth: 500,iframeHeight: 400});
	 	});

	 	$('input[name="discount"]').on("click",function(){
	 		uShopper.disValue=this.dataset.value;
	 		uShopper.disFilter=true;
	 		uShopper.pendingTag="discount";
	 		uShopper.offset=20;

	 		AsyncFilterSearch(true);
	 	});
	 	$('input[name="prate"]').on("click",function(){
	 		uShopper.prateFilter=true;
	 		uShopper.prateValue=this.dataset.value;
	 		uShopper.pendingTag="prate";
	 		uShopper.offset=20;

	 		AsyncFilterSearch(true);
	 	});
	 	$('input[name="srate"]').on("click",function(){
	 		uShopper.srateValue=this.dataset.value;
	 		uShopper.srateFilter=true;
	 		uShopper.pendingTag="srate";
	 		uShopper.offset=20;

	 		AsyncFilterSearch(true);
	 	});

	 	$('#go').on("click",function(){
	 		var max=$(this).prev().val();
	 		var min=$(this).prev().prev().val();
	 		
	 		var regexp=/\d/g;

	 		if(regexp.test(min)&&regexp.test(max)){
	 			max=parseInt(max);
	 			min=parseInt(min);
	 			if(max>5000||min>5000){
	 				alert("Price Range must be less than 5000");
	 				exit(0);
	 			}
	 			else if(max<min){
	 				alert("Minimum should be less than Maximum Price");
	 				exit(0);
	 			}
	 			$('.pslider').slider("values",[min,max]);
	 		}
	 		else{
	 			alert("Only digits allowed")
	 		}
	 	});

	 	var loadProduct=function(data,detach){	
	 		
	 		if(detach)
	 			$(".product-container>.item").detach();
	 		
	 		for (key in data){
	 			
	 			var img=$("<img />",{
	 				"src":data[key].photo[0]
	 			});
	 			var name=$("<a></a>",{
	 				"text":data[key].name,
	 				"href":"#"
	 			});
	 			var seller=$("<a></a>",{
	 				"text":data[key].sname,
	 				"href":"#"
	 			});
	 			var rating=$("<p></p>");
	 			var select=$("<select class='"+data[key].pid+"'><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option></select>");

	 			var price=$("<p></p>",{
	 				"text":data[key].actualprice
	 			});
	 			var mrp=$("<del></del>",{
	 				"text":data[key].mrp
	 			});
	 			var item_get=$("<div></div>",{
	 			"class":"item-get"
		 		});
		 		var cart=$("<button></button>",{
		 			"text":"ADD TO CART",
		 			"class":"manage",
		 			"data-task":"cart_add",
		 			"data-task-command":"add",
		 			"data-item-id":data[key].pid,
		 			"data-item-type":"product"

		 		});
		 		var buy=$("<button></button>",{
		 			"text":"BUY",
		 			click:function(){
		 				window.location='placeorder.php?buy=product&quantity=1&id='+data[key].pid;
		 			}
		 		});
		 		
	 			var item=$("<div></div>",{
	 				"class":"item"
	 			});
	 			var detail=$("<div></div>",{
	 				"class":"item-detail"
	 			});
	 			
	 			price.append(mrp);
 			
 				rating.append(select);
	 			detail.append(name);
	 			detail.append(seller);
	 			detail.append(rating);
	 			detail.append(price);

	 			item_get.append(cart);
	 			item_get.append(buy);

	 			item.append(img);
	 			item.append(detail);
	 			item.append(item_get);

	 			$(".product-container").append(item);
	 			var rate=(parseInt(data[key].prating)>5)?5:parseInt(data[key].prating);
	 			$("."+data[key].pid).barrating({
		        	theme: 'css-stars',
		        	initialRating:rate,
		        	readonly:true
				});
	 		}

	 		uShopper.noProduct=false;

	 	}

	 	var loadShop=function(data){

			for (key in data){
	 			
	 			var img=$("<img />",{
	 				"src":data[key].photo[0]
	 			});
	 			var name=$("<a></a>",{
	 				"text":data[key].sname,
	 				"href":"#"
	 			});
	 			var rating=$("<p></p>");
	 			var select=$("<select class='"+data[key].sid+"'><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option></select>");

	 			var type=$("<p></p>",{
	 				"text":data[key].stype
	 			});
	 			var mobile=$("<p></p>",{
	 				"text":data[key].smobile
	 			});

	 			var fav=$("<span></span>",{
	 				"text":"F",
	 				"class":"fav",
	 				"data-item-id":data[key].sid,
	 				"data-item-type":"shop"
	 			});

		 		var view=$("<button></button>",{
		 			"text":"View Shop",
		 			click:function(){
		 				window.location='shopviewer.php?id='+data[key].sid;
		 			}
		 		});
		 		
		 		var shop=$("<div></div>");

		 		var imgC=$("<div></div>");

	 			var viewC=$("<div></div>");

	 			var detail=$("<div></div>");
	 			
 				rating.append(select);

 				imgC.append(img);

 				detail.append(name);
 				detail.append(rating);
 				detail.append(type);
 				detail.append(mobile);

 				viewC.append(fav);
 				viewC.append(view);

 				shop.append(imgC);
 				shop.append(detail);
 				shop.append(viewC);
	 			
	 			$(".shop-container").append(item);
	 			var rate=(parseInt(data[key].srating)>5)?5:parseInt(data[key].srating);
	 			
	 			$("."+data[key].sid).barrating({
		        	theme: 'css-stars',
		        	initialRating:rate,
		        	readonly:true
				});
	 		}	 		
	 	}

	 	$('.sort').on('click',function(){

			uShopper.sort=this.dataset.sort;
			uShopper.offset=20;
			
			$(".sort").removeClass("sort-active");
			$(this).toggleClass("sort-active");

			AsyncFilterSearch(true);
	 	});

});