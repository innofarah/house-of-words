<html><div id='countervalue' hidden></div></html>
<script>
function hello1(ab){
  alert(ab);
}

/*var func;
function generate_the_function(){
	functionBody = "var updates_div_counter = \"<?php echo $_SESSION['updates_div_counter'] ?>\";";
	functionBody += "id = \"#updates_div\" + updates_div_counter;";
	functionBody += "url = \"generate_updates.php\";";
	functionBody += "$(document).ready(function(){ $(id).load(url,function(){";
	functionBody += "alert(\"<?php echo 'hello' ?>\");alert(id);";	
 	functionBody += "});});";
 	alert(functionBody);		 
 				 
	func = new Function (functionBody);
	func();
}*/

function generate_updates_action(){
  url1 = "load_counterval.php";
  $(document).ready(function(){
  	$('#countervalue').load(url1, function(){
  		//alert("started");
  		var updates_div_counter = document.getElementById('countervalue').innerHTML;
 		updates_div_counter = parseInt(updates_div_counter);
 		id = "#updates_div" + updates_div_counter;
 		//alert(id);
 		url = "generate_updates.php";
 		updates_div_counter += 1;
 		//alert(id);
                      $(id).load(url,function(){
                      //	alert("<?php echo 'hello' ?>");	
                       //  alert(id);
                         document.getElementById('updatesdiv').innerHTML +=
                         "<div id='updates_div" + updates_div_counter + "'></div>";
                      });

                  
  	},1000);
  })

 // var updates_div_counter = document.getElementById('countervalue').innerHTML;
 // updates_div_counter = parseInt(updates_div_counter);
  //var updates_div_counter = "<?php echo $_SESSION['updates_div_counter'] ?>";
  //id = "#updates_div" + updates_div_counter;
  //url = "generate_updates.php";
  //$(document).ready(function(){
    //                  $(id).load(url,function(){
     //                 	alert("<?php echo 'hello' ?>");	
      //                   alert(id);
       //               });
        //           })
}

//generate_the_function();
//func();
</script>

