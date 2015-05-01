
function check_all(chk)
{
	//alert("Hello! I am andfgdg alert box!!");
	/*for(var i=0; chk[i]; ++i){
		alert(chk[i].value);
	}*/
	
	if(document.ref_list.Check_All.value == "Check All"){
		for(var i=0; chk[i]; ++i){
			chk[i].checked = true ;
		}			
		document.ref_list.Check_All.value="UnCheck All";
	}else{

		for(var i=0; chk[i]; ++i)
			chk[i].checked = false ;
		document.ref_list.Check_All.value="Check All";
	}
}



function myFunction() {
    confirm("Are you sure!");
}


function validateForm() {
    var x = document.forms["register"]["username"].value;
    if (x == null || x == "") {
        alert("Name must be filled out");
        return false;
    }
}
