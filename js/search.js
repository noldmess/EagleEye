var lasturl="";
$(document).ready(function() {
	checkURL();	//check if the URL has a reference to a page and load it

    $('ul li a option').click(function (e){	//traverse through all our navigation links..

            checkURL(this.hash);	//.. and assign them a new onclick event, using their own hash as a parameter (#page1 for example)

    });

    setInterval("checkURL()",250);	//check for a change in the URL every 250 ms to detect if the history buttons have been used
 	

});
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
