$(document).ready(function(){

	if(!uShopper.noProduct){
		$('.no-product').hide();
	}
	else{
		$('.no-product').show();
	}

	$('.flip-me').on('click',function(e){
	
		if(uShopper.flip){			
			if($(this).parent().parent().hasClass('shop-detail')){
				$('.shop-detail').removeClass('flip2');
				$('.filter-bar').removeClass('flip1');
				$('.shop-detail').addClass('flip1');
				$('.filter-bar').addClass('flip2');
				$('main').css({"min-height":"48em"});
			}
			else{
				$('.shop-detail').removeClass('flip1');
				$('.filter-bar').removeClass('flip2');
				$('.shop-detail').addClass('flip2');
				$('.filter-bar').addClass('flip1');
				$('main').css({"min-height":"33em"});
			}

			if(e.isTrigger===3){
				$('.shop-detail').removeClass('flip2');
				$('.filter-bar').removeClass('flip1');
				$('.shop-detail').addClass('flip1');
				$('.filter-bar').addClass('flip2');
				$('main').css({"min-height":"48em"});	
			}
		}
	});

	if(uShopper.flip)
		$('.flip-me').trigger('click');

	$( ".radioset" ).buttonset();
	
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

			AsyncProductSearch();	
		}
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

 	$('input[name="discount"]').on("click",function(){
 		uShopper.disValue=this.dataset.value;
 		uShopper.disFilter=true;
 		uShopper.pendingTag="discount";
 		uShopper.offset=20;

 		AsyncProductSearch();
 	});
 	$('input[name="prate"]').on("click",function(){
 		uShopper.prateFilter=true;
 		uShopper.prateValue=this.dataset.value;
 		uShopper.pendingTag="prate";
 		uShopper.offset=20;

 		AsyncProductSearch();
 	});

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

		uShopper.offset=20;

		AsyncProductSearch();
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

	$('.load').on('click',function(){

		$('#loadsync').show();
		$('.unloadsync').hide();
		var json={
			"offset":uShopper.offset+1
		}
		//console.log(uShopper.offset);
		AsyncFilterSearch(false,json);
	});

	var AsyncProductSearch=function(detach=true,opts={}){

		if(detach)
			$(".loadme").fadeToggle(500);

		var json={
			"param":uShopper.sort+","+getFilterType(),
			"searchshopfor":uShopper.search,
			"sid":uShopper.sid,
			"discount":getFilterDiscount(),
			"pricemin":getFilterPriceMIN(),
			"pricemax":getFilterPriceMAX(),
			"prate":getFilterPrate()
		};

		json=$.extend({},json,opts);

		var request=$.ajax({
			url:"myvendor/shopmanager.php",
			method:"POST",
			dataType:"text",
			data:json,
		});

		request.done(function(data){ 
			console.log(data);

			if(detach)
				$(".loadme").fadeToggle(500);
			else{
				$('#loadsync').hide();
				$('.unloadsync').show();
			}
			
			data=JSON.parse(data);

			if(data.success){
				
				if(detach){
					$('.no-product').hide();
					$('.loadmore').show();	
				}
				else{
					uShopper.offset=uShopper.offset+20;
				}

				var stateObj={source:"shopviewer.php"};
				//console.log(uShopper.sort+uShopper.filter);
				if(uShopper.sort!="0"&&getFilterType()!="0"){
					history.pushState(stateObj,"search","shopviewer.php?id="+uShopper.sid+"&searchshopfor="+uShopper.search+"&sort="+uShopper.sort+"&filter="+getFilterType()+"&pricemin="+uShopper.pricemin+"&pricemax="+uShopper.pricemax+"&discount="+uShopper.disValue+"&prate="+uShopper.prateValue);
				}
				else if(getFilterType()!="0"){
					history.pushState(stateObj,"search","shopviewer.php?id="+uShopper.sid+"&searchshopfor="+uShopper.search+"&filter="+getFilterType()+"&pricemin="+uShopper.pricemin+"&pricemax="+uShopper.pricemax+"&discount="+uShopper.disValue+"&prate="+uShopper.prateValue);	
				}
				else if(uShopper.sort!="0"){
					history.pushState(stateObj,"search","shopviewer.php?id="+uShopper.sid+"&searchshopfor="+uShopper.search+"&sort="+uShopper.sort);	
				}
				
				if(uShopper.pendingTag!=""&&detach){
					addFilterTag();
				}
				loadProduct(data.list,detach);
			}
			else{
				if(detach){
					addFilterTag();
					$(".search-result-container>.item").detach();
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
	
	$(".sort").on("change",function(){
		uShopper.sort=this.value;
		AsyncProductSearch();
	} );

	var loadProduct=function(data,detach){	
		
		if(detach)
 			$(".search-result-container>.item").detach();
 		
 		for (key in data){
 			
 			var img=$("<img />",{
 				"src":data[key].photo[0]
 			});
 			var name=$("<a></a>",{
 				"text":data[key].pname,
 				"href":"#"
 			});
 			var rating=$("<p></p>");
 			var select=$("<select class='"+data[key].pid+"'><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option></select>");

 			var price=$("<p></p>",{
 				"text":data[key].pactualprice
 			});
 			var mrp=$("<del></del>",{
 				"text":data[key].pmrp
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
 			detail.append(rating);
 			detail.append(price);

 			item_get.append(cart);
 			item_get.append(buy);

 			item.append(img);
 			item.append($("<span class='manage' data-item-id='"+data[key].pid+"' data-todo='wishlist_add' data-c ommand='add' >F</span>"));
 			item.append(detail);
 			item.append(item_get);

 			$(".search-result-container").append(item);
 			var rate=(parseInt(data[key].prating)>5)?5:parseInt(data[key].prating);
 			$("."+data[key].pid).barrating({
	        	theme: 'css-stars',
	        	initialRating:rate,
	        	readonly:true
			});
 		}

 		uShopper.noProduct=false;
 	}
});