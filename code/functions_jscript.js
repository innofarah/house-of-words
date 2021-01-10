

function insertcomment(event, element, userId, updateId){
      if(event.keyCode==13){    //enter key
        var id = element.id.substr(13);
        id = "#comments_div" + id;
        //the space in the cmnt made a problem
        var url = "processlikes.php?insertcomment=1&user_id=" + userId + "&comment_txt=" + encodeURI(element.value) + "&update_id=" + updateId + " #comments_div";   
        $(document).ready(function(){
                      $(id).load(url,function(){
                        element.value="";
                      });
                    })
      }
}

function display_search(){
      var key = document.getElementById('search_form').search_key.value;  //space is causing a problem
    //  key = key.replace(/\s/g, "");    
      //alert(key);
      var filter = document.getElementById('search_form').filter.value;
      var url = "search.php?key=" + encodeURI(key) + "&filter="+filter+ " #search_results_div";
      //alert(url);
      $(document).ready(function(){
        $("#search_result").load(url,function(){

        });
      })
}
                  
function view_stars(myRating,eltId){
    var theImage = "";
    for(var i=0; i<myRating; i++)
      theImage += "<img src='star.png' width='10px' height='10px; '>";
    var id="stars"+eltId;
    document.getElementById(id).innerHTML = theImage;
}

function view(readlink,desc) {
    if(document.getElementById(readlink).innerHTML == "..read more") {
      document.getElementById(desc).hidden = false;
      document.getElementById(readlink).innerHTML = "  less";
    }
    else {
      document.getElementById(desc).hidden = true;
      document.getElementById(readlink).innerHTML = "..read more";
    }
    return false;
}

function clicked_like(user_id,update_id,like_link_id,nb_of_likes_id){
    $(document).ready(function(){
    var url = "processlikes.php?update_id=" + update_id + "&user_id=" + user_id + " #" + "numlikes";
    $("#" + nb_of_likes_id).load(url,function(){
                  if(document.getElementById(like_link_id).innerHTML == "Like")
                   document.getElementById(like_link_id).innerHTML = "Unlike";
                  else 
                    document.getElementById(like_link_id).innerHTML = "Like";
          });
        });
    return false;
}

function display_likes(updateId){
    $(document).ready(function(){
    var url = "processlikes.php?update_id=" + updateId + " #" + "likes_display"; 
    $("#likers_modal").load(url,function(){
            // Get the modal
                 var modal = document.getElementById('myModal');
            // Get the <span> element that closes the modal
                 var span = document.getElementsByClassName("close")[0];
                 modal.style.display = "block";
            // When the user clicks on <span> (x), close the modal
                 span.onclick = function() {
                 modal.style.display = "none";
                  }
           // When the user clicks anywhere outside of the modal, close it
                 window.onclick = function(event) {
                   if (event.target == modal) {
                      modal.style.display = "none";
                   }
                 }
          }); 
        });
    return false;
}

function hide_view(eltId){
  var x = document.getElementById(eltId);
  x.hidden = !x.hidden;
}


function display_shelf(state, userId){
  var url = "displaying_shelf.php?state=" + state + "&user_id=" + userId + " #shelf";
  //alert(url);
  $(document).ready(function(){
    $("#shelf_division").load(url, function(){});
  });
  return false;
}


