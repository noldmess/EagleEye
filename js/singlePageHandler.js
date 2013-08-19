$(document).ready(function() {
	//executed after the page has loaded
	Module.toolSlide();
	Module.init();

    checkURL();	//check if the URL has a reference to a page and load it

    $('ul li a option').click(function (e){	//traverse through all our navigation links..

            checkURL(this.hash);	//.. and assign them a new onclick event, using their own hash as a parameter (#page1 for example)

    });

    setInterval("checkURL()",250);	//check for a change in the URL every 250 ms to detect if the history buttons have been used

});

var lasturl="";	//here we store the current URL hash
function checkURL(hash)
{
    if(!hash) hash=window.location.hash;	//if no parameter is provided, use the hash value from the current address

    if(hash != lasturl)	// if the hash value has changed
    {
    	 var helpold=lasturl.substr(1);
    	 var helpold=helpold.split("/");
        lasturl=hash;	//update the current hash
        var veiwName=hash.substr(1);
        var help=veiwName.split("/");
       
      //  loadPage(hash);	// and load the new page
        Module.hideView();
        switch (help[0]) {
        case "photoview":
        	PhotoView.showView();
        	PhotoView.ClickImg(help,helpold);
            break;
        case "facefinder":
    		FaceFinder.showView();
    		FaceFinder.load(help);
    		Module.resateView();
            break;
        case "duplicatits":
    		$("button[title='Remove']").text("Remove (0)");
    		$("button[title='Remove']").removeClass("btn btn-warning");
    		Duplicatits.showView();
    		Duplicatits.load(help);
            break;
        case "":
            break;
        default:
        	  Module.viewLoader(help[0]);
            break;
        }
    }else{
    	  if(hash ==="")
    	window.history.pushState({path:"facefinder"},"","#facefinder");
    }
      
        //.click();
    }


//function loadPage(url)	//the function that loads pages via AJAX
//{
//    url=url.replace('#page','');	//strip the #page part of the hash and leave only the page number
//
//    $('#loading').css('visibility','visible');	//show the rotating gif animation
//
//    $.ajax({	//create an ajax request to load_page.php
//        type: "POST",
//        url: "load_page.php",
//        data: 'page='+url,	//with the page number as a parameter
//        dataType: "html",	//expect html to be returned
//        success: function(msg){
//
//            if(parseInt(msg)!=0)	//if no errors
//            {
//                $('#pageContent').html(msg);	//load the returned html into pageContet
//                $('#loading').css('visibility','hidden');	//and hide the rotating gif
//            }
//        }
//
//    });
