<!DOCTYPE html>
<html>
<head>
	<title>Track Order</title>
	<link rel="stylesheet" type="text/css" href="css/track_order.css">

	<script type="text/javascript" src="engine0/jquery.js"></script>
</head>
<body>
	<div class="main_container">
		<header class="container header_cont">
			<div>
				<h1>
					Ushopper Order Tracking and Managing System
				</h1>
			</div>	
		</header>

		<section class="container section_cont">
			
			<div class="order_table_cont">
				<table class="order_table">
					<thead>
						<tr>
							<th>Order ID</th>
							<th>Status</th>
							<th>Address</th>
							<th>Manage</th>
						</tr>
					</thead>
					<tbody class="order_list_cont">
						
					</tbody>
				</table>
			</div>
		</section>

		<footer class="container footer_cont"></footer>
	</div>



	<script type="text/javascript">

		$(document).ready(function(){

			var oidList=new Array();

			var evtSource = new EventSource("exp.php");

	  		evtSource.onopen = function() {
			    console.log("Connection to server opened.");
			};

			var checkExistence=function(oid){
					
				var flag=true;
				for(var item in oidList){
					if(oid===oidList[item]){
						flag=false;
						return false;
					}
				}

				if(flag){
					oidList.push(oid);
					return true;
				}
			}

			evtSource.onmessage = function(e) {

				var data=JSON.parse(e.data);

				for(var object in data){
					
					if(checkExistence(data[object].oid)){
						var oid=$("<td></td>",{
					  		"text":data[object].oid
					  	});

					  	var status=$("<td></td>",{
					  		"text":data[object].status
					  	});

					  	var address=$("<td></td>",{
					  		"text":data[object].address
					  	});

					  	var approve=$("<button></button>",{
					  		"text":"Approve",
					  		"data-oid":data[object].oid
					  	});
					  	var cancel=$("<button></button>",{
					  		"text":"Cancel",
					  		"data-oid":data[object].oid
					  	});
					  	var deletebut=$("<button></button>",{
					  		"text":"Delete",
					  		"data-oid":data[object].oid
					  	});

					  	var manage=$("<td></td>");

					  	var row=$("<tr></tr>");

					  	manage.append(approve);
					  	manage.append(cancel);
					  	manage.append(deletebut);

					  	row.append(oid);
					  	row.append(status);
					  	row.append(address);
					  	row.append(manage);

					  	$(".order_list_cont").append(row);
					}
				}
			  	console.log("message: " + e.data);
			}

			evtSource.onerror = function(e) {
			  	console.log("EventSource failed.");
			};

		});

		

		/*evtSource.addEventListener("abhishek", function(e) {
		  var newElement = document.createElement("li");
		  var element=document.getElementById("list");
		  console.log(e);
		  newElement.innerHTML = e.type+" " + e.data;
		  element.appendChild(newElement);
		}, false);*/
	</script>

</body>
</html>