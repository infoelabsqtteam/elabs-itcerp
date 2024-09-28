//********************Multi-Session-Browsing-Script******************************

//Broad cast that your're opening a page.
localStorage.openpages = Date.now();
var onLocalStorageEvent = function(e){
    if(e.key == "openpages"){
	//Listen if anybody else opening the same page
	localStorage.page_available = Date.now();
    }
    if(e.key == "page_available"){
	window.location = SITE_URL+'multi-session-error';
    }
};
window.addEventListener('storage', onLocalStorageEvent, false);
//********************Multi-Session-Browsing-Script******************************