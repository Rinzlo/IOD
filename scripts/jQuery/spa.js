$(function(){
	
	//Initial Display
	if( location.hash === ''){
		displayPage("#loginPage");
		$('#userButtonLogin').hide();
		$('#userButtonLogout').show();
		$('#logout').hide();
	}else{
		displayPage(location.hash);
	}
	//Flag of complete
	var completeFlg = true;
	
	//When buttons are clicked
	//Change hash
	$('.btn').click(function(e){
		e.preventDefault();
		if($(this).attr('id') === 'logout'){
			$('#userButtonLogin').hide();
			$('#userButtonLogout').show();
			$('#logout').hide();
		}
		var hash = "#"+$(this).attr('data-hash');
		location.hash = hash;
	});
	
	//When hash is changed, display new page
	$(window).hashchange(function(){
		if(completeFlg === true){
			clearPage();
			displayPage(location.hash);  
		}else{
			location.hash = "#three";    
			return false;
		}
		if(location.hash === "#three"){
			completeFlg = false;
		}
	});    
	
	// Page Clear
	function clearPage(){
		$(".page").css("display", "none");
	}
	
	//Displaying Page
	function displayPage(hash){
		$(hash).css("display", "block");
	}
});    

