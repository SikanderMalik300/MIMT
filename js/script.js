function start_loader(){
	$('body').append('<div id="preloader"><div class="loader-holder"><div></div><div></div><div></div><div></div>')
}
function end_loader(){
	 $('#preloader').fadeOut('fast', function() {
		$('#preloader').remove();
      })
}
// function 
window.alert_toast= function($msg = 'TEST',$bg = 'success' ,$pos=''){
	   	 var Toast = Swal.mixin({
	      toast: true,
	      position: $pos || 'top-end',
	      showConfirmButton: false,
	      timer: 5000
	    });
	      Toast.fire({
	        icon: $bg,
	        title: $msg
	      })
	  }

$(function(){
	// Login
	$('#login-frm').on('submit',function(e){
		e.preventDefault()
		start_loader()
		if($('.err_msg').length > 0)
			$('.err_msg').remove()
		$.ajax({
			url:_base_url_+'classes/Login.php?f=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)

			},
			success:function(resp){
				if(resp){
					resp = JSON.parse(resp)
					if(resp.status == 'success'){
						location.replace(_base_url_);
					}else if(resp.status == 'incorrect'){
						var _frm = $('#login-frm')
						var _msg = "<div class='alert alert-danger text-white err_msg'><i class='fa fa-exclamation-triangle'></i> Incorrect username or password</div>"
						_frm.prepend(_msg)
						_frm.find('input').addClass('is-invalid')
						$('[name="username"]').trigger('focus')
					}
						end_loader()
				}
			}
		})
	})
	// Login
	$('#forget-frm').on('submit',function(e){
		e.preventDefault()
		start_loader()
		if($('.err_msg').length > 0)
			$('.err_msg').remove()
		$.ajax({
			url:_base_url_+'classes/Login.php?f=forget',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)

			},
			success:function(resp){
				if(resp){
					resp = JSON.parse(resp)
					if(resp.status == 'success'){
						$('#msg').html('<div class="alert alert-danger">Kindly check your email</div>')
						$("html, body").animate({
						  scrollTop: 0
						}, "fast");

						// location.replace(_base_url_);
					}else if(resp.status == 'incorrect'){
						var _frm = $('#forget-frm')
						$('#msg').html('<div class="alert alert-danger">Email does not exist</div>')
						$("html, body").animate({
						  scrollTop: 0
						}, "fast");
						_frm.find('input').addClass('is-invalid')
						$('[name="email"]').trigger('focus')
					}
						end_loader()
				}
			}
		})
	})
	//Establishment Login
	$('#flogin-frm').on('submit',function(e){
		e.preventDefault()
		start_loader()
		if($('.err_msg').length > 0)
			$('.err_msg').remove()
		$.ajax({
			url:_base_url_+'classes/Login.php?f=flogin',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)

			},
			success:function(resp){
				if(resp){
					resp = JSON.parse(resp)
					if(resp.status == 'success'){
						location.replace(_base_url_+'faculty');
					}else if(resp.status == 'incorrect'){
						var _frm = $('#flogin-frm')
						var _msg = "<div class='alert alert-danger text-white err_msg'><i class='fa fa-exclamation-triangle'></i> Incorrect username or password</div>"
						_frm.prepend(_msg)
						_frm.find('input').addClass('is-invalid')
						$('[name="username"]').trigger('focus')
					}
						end_loader()
				}
			}
		})
	})

	//user login
	$('#slogin-frm').on('submit',function(e){
		e.preventDefault()
		start_loader()
		if($('.err_msg').length > 0)
			$('.err_msg').remove()
		$.ajax({
			url:_base_url_+'classes/Login.php?f=slogin',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)

			},
			success:function(resp){
				if(resp){
					resp = JSON.parse(resp)
					if(resp.status == 'success'){
						location.replace(_base_url_+'student');
					}else if(resp.status == 'incorrect'){
						var _frm = $('#slogin-frm')
						var _msg = "<div class='alert alert-danger text-white err_msg'><i class='fa fa-exclamation-triangle'></i> Incorrect username or password</div>"
						_frm.prepend(_msg)
						_frm.find('input').addClass('is-invalid')
						$('[name="username"]').trigger('focus')
					}
						end_loader()
				}
			}
		})
	})

	$('#manage-ruser').on('submit',function(e) {

		e.preventDefault();
		var _this = $(this)
		start_loader()
		$.ajax({
		  url: _base_url_ + 'classes/Users.php?f=rsave',
		  data: new FormData($(this)[0]),
		  cache: false,
		  contentType: false,
		  processData: false,
		  method: 'POST',
		  type: 'POST',
		  success: function(resp) {
			resp = JSON.parse(resp)
			console.log(resp);
			if(resp.status == 'success'){
				location.replace(_base_url_);
			}
			if (resp == 1) {
			  location.href = './';
			} else {
			  $('#msg').html('<div class="alert alert-danger">Username already exist</div>')
			  $("html, body").animate({
				scrollTop: 0
			  }, "fast");
			}
			end_loader()
		  }
		})
	  })

	$('#verify_otp').on('submit',function(e) {

		e.preventDefault();
		var _this = $(this)
		start_loader()
		$.ajax({
		  url: _base_url_ + 'classes/Users.php?f=verifyotp',
		  data: new FormData($(this)[0]),
		  cache: false,
		  contentType: false,
		  processData: false,
		  method: 'POST',
		  type: 'POST',
		  success: function(resp) {
			resp = JSON.parse(resp)
			console.log(resp);
			if(resp.status == 'success'){
				location.replace(_base_url_);
			}else {
			  $('#msg').html('<div class="alert alert-danger">Invalid OTP</div>')
			  $("html, body").animate({
				scrollTop: 0
			  }, "fast");
			}
			end_loader()
		  }
		})
	  })
	// System Info
	$('#system-frm').on('submit',function(e){
		e.preventDefault()
		start_loader()
		if($('.err_msg').length > 0)
			$('.err_msg').remove()
		$.ajax({
			url:_base_url_+'classes/SystemSettings.php?f=update_settings',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					// alert_toast("Data successfully saved",'success')
						location.reload()
				}else{
					$('#msg').html('<div class="alert alert-danger err_msg">An Error occured</div>')
					end_load()
				}
			}
		})
	})
	$('#receive-nav').on('click',function(e){
		e.preventDefault();
		$('#uni_modal').on('shown.bs.modal',function(){
		  $('#find-transaction [name="tracking_code"]').trigger('focus');
		})
		uni_modal("Enter Tracking Number","transaction/find_transaction.php");
	})

})
