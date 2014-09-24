// JavaScript Document
function check_user()
{
	if(document.getElementById('massmail').checked == true)
    {
	document.getElementById('massmail').checked = false;
	}
	

}
function check_massmail()
{
	if(document.getElementById('massmail').checked == true)
	{ 
	 document.getElementById('user').value = '';
	}
	
	
}