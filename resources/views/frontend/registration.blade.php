<html>
<head>
<link rel="stylesheet" href="{{ asset('https://fonts.googleapis.com/css?family=Montserrat') }}">
</head>
<style>
/*basic reset*/
* {margin: 0; padding: 0;}

html {
	height: 100%;
	background: linear-gradient(to right, #ff9b44 0%, #fc6075 100%);
}

body {
	font-family: montserrat, arial, verdana;
	
}
/*form styles*/
#msform {
	width: 400px;
	margin: 50px auto;
	text-align: center;
	position: relative;
}
#msform fieldset {
	background: white;
	border: 0 none;
	border-radius: 3px;
	box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
	padding: 20px 30px;
	box-sizing: border-box;
	width: 80%;
	margin: 0 10%;
	
	/*stacking fieldsets above each other*/
	position: relative;
}
/*Hide all except first fieldset*/
#msform fieldset:not(:first-of-type) {
	display: none;
}
/*inputs*/
#msform input, #msform textarea {
	padding: 15px;
	border: 1px solid #ccc;
	border-radius: 3px;
	margin-bottom: 10px;
	width: 100%;
	box-sizing: border-box;
	font-family: montserrat;
	color: #2C3E50;
	font-size: 13px;
}
/*buttons*/
#msform .action-button {
	width: 100px;
	background: #27AE60;
	font-weight: bold;
	color: white;
	border: 0 none;
	border-radius: 1px;
	cursor: pointer;
	padding: 10px;
	margin: 10px 5px;
  text-decoration: none;
  font-size: 14px;
}
#msform .action-button:hover, #msform .action-button:focus {
	box-shadow: 0 0 0 2px white, 0 0 0 3px #27AE60;
}
/*headings*/
.fs-title {
	font-size: 15px;
	text-transform: uppercase;
	color: #2C3E50;
	margin-bottom: 10px;
}
.fs-subtitle {
	font-weight: normal;
	font-size: 13px;
	color: #666;
	margin-bottom: 20px;
}
/*progressbar*/
#progressbar {
	margin-bottom: 30px;
	overflow: hidden;
	/*CSS counters to number the steps*/
	counter-reset: step;
}
#progressbar li {
	list-style-type: none;
	color: white;
	text-transform: uppercase;
	font-size: 9px;
	width: 50%;
	float: left;
	position: relative;
}
#progressbar li:before {
	content: counter(step);
	counter-increment: step;
	width: 20px;
	line-height: 20px;
	display: block;
	font-size: 10px;
	color: #333;
	background: white;
	border-radius: 3px;
	margin: 0 auto 5px auto;
}
/*progressbar connectors*/
#progressbar li:after {
	content: '';
	width: 100%;
	height: 2px;
	background: white;
	position: absolute;
	left: -50%;
	top: 9px;
	z-index: -1; /*put it behind the numbers*/
}
#progressbar li:first-child:after {
	/*connector not needed before the first step*/
	content: none; 
}
/*marking active/completed steps green*/
/*The number of the step and the connector before it = green*/
#progressbar li.active:before,  #progressbar li.active:after{
	background: #27AE60;
	color: white;
}

</style>

<!-- multistep form -->
<form id="msform" action="{{route('registration')}}" method="post" style="width: 718px;">
    @csrf
  <!-- progressbar -->
  <ul id="progressbar">
    <li class="active">Account Setup</li>
    <li>HR Profile</li>
  </ul>
  <!-- fieldsets -->
  <fieldset>
    <h2 class="fs-title">Create your account</h2>
    <h3 class="fs-subtitle">This is step 1</h3>
    <input type="text" name="companyName" placeholder="companyName" />
    <input type="text" name="companyDetails" placeholder="companyDetails" />
    <input type="text" name="address" placeholder="address" />
    <input type="file" accept="image/png, image/gif, image/jpeg, image/jpg" name="logo" placeholder="logo" />
    <input type="text" name="contactNumber" placeholder="contactNumber" />
    <input type="button" name="next" class="next action-button" value="Next" />
  </fieldset>

  <fieldset>
    <h2 class="fs-title">HR Details</h2>
    <h3 class="fs-subtitle">We will never sell it</h3>
    <input type="text" name="name" placeholder="name" />
    <input type="email" name="email" placeholder="email" />
    <input type="password" id = "password" name="password" placeholder="password" min="4" />
    <input type="password" id = "password_confirmation" name="password_confirmation" placeholder="Confirm password" />
    <div id="message"></div>
    <input type="button" name="previous" class="previous action-button" value="Previous" />
    <input type="submit" id="submitBtn" name="submit" class="submit action-button" value="Submit">
  </fieldset>
  
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

//jQuery time
var current_fs, next_fs, previous_fs; //fieldsets
var left, opacity, scale; //fieldset properties which we will animate
var animating; //flag to prevent quick multi-click glitches

$(document).ready(function() {
    $('#password, #password_confirmation').on('keyup', function () {
        var password = $('#password').val();
        var confirmPassword = $('#password_confirmation').val();

        if (password.length >= 6) {
            if (password == confirmPassword) {
                $('#message').html('Matching').css('color', 'green');
                $('#submitBtn').prop('disabled', false); // Enable the submit button
            } else {
                $('#message').html('Not Matching').css('color', 'red');
                $('#submitBtn').prop('disabled', true); // Disable the submit button

            }
        } else {
            $('#message').html('Password must be at least 6 characters').css('color', 'red');
            $('#submitBtn').prop('disabled', true); // Disable the submit button

        }
    });
});

$(".next").click(function(){
    
	if(animating) return false;
    //fill up all feild code
    var allFieldsFilled = true;
      var emptyFields = [];
      $(this).parent().find("input:not(.optional),select").each(function () {
        if (!$(this).val()) {
          allFieldsFilled = false;
          emptyFields.push($(this).attr("placeholder"));
        }
      });

      if (!allFieldsFilled) {
        var errorMessage = "Please fill in the following fields before moving to the next step: \n";
        for (var i = 0; i < emptyFields.length; i++) {
          errorMessage += "\t- " + emptyFields[i] + "\n";
        }
        alert(errorMessage);
        return false;
      }

	animating = true;
	
	current_fs = $(this).parent();
	next_fs = $(this).parent().next();

	//activate next step on progressbar using the index of next_fs
	$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
	
	//show the next fieldset
	next_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale current_fs down to 80%
			scale = 1 - (1 - now) * 0.2;
			//2. bring next_fs from the right(50%)
			left = (now * 50)+"%";
			//3. increase opacity of next_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({
        'transform': 'scale('+scale+')',
        'position': 'absolute'
      });
			next_fs.css({'left': left, 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
});

$(".previous").click(function(){
	if(animating) return false;
	animating = true;
	
	current_fs = $(this).parent();
	previous_fs = $(this).parent().prev();
	
	//de-activate current step on progressbar
	$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
	
	//show the previous fieldset
	previous_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale previous_fs from 80% to 100%
			scale = 0.8 + (1 - now) * 0.2;
			//2. take current_fs to the right(50%) - from 0%
			left = ((1-now) * 50)+"%";
			//3. increase opacity of previous_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({'left': left});
			previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
});


$(document).ready(function() {
	var baseUrl = "{{ $baseUrl }}";
    $('#msform').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
                url: baseUrl + '/register', // Assuming your backend route is '/register'
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration successful',
                        text: 'Your registration was successful!',
                        showConfirmButton: true, // Set to true to show the "OK" button
						}).then((result) => {
						if (result.isConfirmed) {
							window.location.href = '/login-form';
						}
					});
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.error;
                        var errorMessage = "<ul>";
                        for (var field in errors) {
                            errorMessage += "<li>" + errors[field][0] + "</li>";
                        }
                        errorMessage += "</ul>";
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: errorMessage
                        });
                    }
                }
            });
        });
    });


</script>
</html>
