$(document).ready(function(){

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

			AsyncFilterSearch();	
		}
	});
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
});