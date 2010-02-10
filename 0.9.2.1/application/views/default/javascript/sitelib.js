$(document).ready(
	function(){
		$('form.homelogin .input-text').blur(function()
			{
				if ($(this).val() == '')
				{
				$(this).val($(this).attr("title"));
				}
			}
		);
		$('form.homelogin .input-text').focus(function()
			{
				if ($(this).val())
				{
				$(this).val('');
				}
			}
		);



$("#homelogin").submit(function()
{
        //remove all the class add the messagebox classes and start fading
		var backup = $("#login").html();
		var username = $('#username').val();
		var password = $('#password').val();
        $("#login").text('Validating....').fadeIn(1000);
        //check the username exists or not from ajax
        $.post("/member/ajax/login",{ username: username, password: password,rand:Math.random() } ,function(xml)
        {
          if($("status",xml).text() == "1") //if correct login detail
          {
                $("#login").fadeTo(200,0.1,function()  //start fading the messagebox
                {
                  //add message and change the class of the box and start fading
                  $(this).html( $("message", xml).text()).addClass('messageboxok').fadeTo(900,1);
                });
          }
          else
          {
                $("#login").fadeTo(200,0.1,function() //start fading the messagebox
                {
                  $(this).html(backup).addClass('messageboxerror').fadeTo(900,1);
				  $("p.notice").text($("message", xml).text());
                });
          }
       });
       return false;//not to post the  form physically
});		


		$(".input-submit").click(function () { 
		$(this).disabled = true; 
		});

	}
);




	 