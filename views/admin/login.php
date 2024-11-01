<script>
    let Api_Key_Access = localStorage.getItem('Api_Key');
    let Merchant_Id_Access = localStorage.getItem('Merchant_id');
    if (Api_Key_Access) {
      window.location.href = "edit.php?post_type=chargely-plugin";
    } else if (Merchant_Id_Access) {
      window.location.href = "edit.php?post_type=chargely-plugin";
    } 
  </script>
<style>
    
    .input-icons img {
        position: absolute;
    }
          
    .input-icons {
        margin-bottom: 10px;
    }
          
    .icon {
        padding: 10px;
        color: rgb(0 98 153);
        min-width: 40px;
        text-align: center;
		font-size: 20px;
    }
          
    .form-control {
        width: 300px;
		height: 40px;
        padding: 10px;
        text-align: center;
    }
          
	.nav-tabs{
		background-color: rgb(0 98 153);
	}
		
    .nav-tabs>li.active>a, .nav-tabs>li.active>a:hover, .nav-tabs>li.active>a:focus{
		background-color: #ff910a;
        color: #ffffff;
        cursor: pointer;
        width: 100px;
        text-align: center;
        height: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 2px;
        box-shadow: rgb(0 0 0 / 16%) 0px 3px 6px, rgb(0 0 0 / 23%) 0px 3px 6px;
	}
		
    a{
		color: rgb(0 98 153);
		font-size: 16px;
		font-weight: 500;
	}
		
    .sbtn{
		cursor:pointer;
		border:none; 
		color: #E1EBF3; 
		background-color: #ff910a; 
		padding:10px; 
		border-radius: 50px; 
		font-size: 18px;
		font-weight: 500;
		text-shadow: 2px 4px 3px rgba(0,0,0,0.3);
		width: 150px;
		box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
	}
		
    .form-control:focus {
		border-color: yellow;
	}
		
    .sbtn:hover{
		background-color: #ffb531;
	}
	
    ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: lightgrey;
        opacity: 1; /* Firefox */
    }
			
    .overlay {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        transition: opacity 500ms;
        visibility: hidden;
        opacity: 0;
        z-index: 99999;
    }

    .overlay:target {
        visibility: visible;
        opacity: 1;
    }

    .popup {
        margin: 70px auto;
        padding: 5px 10px;
        background: #F2F2F2;
        border-radius: 5px;
        width: 20%;
        position: relative;
        transition: all 5s ease-in-out;
    }

    .popup h2 {
        margin-top: 0;
        color: #333;
        font-family: Tahoma, Arial, sans-serif;
    }

    .popup .close {
        transition: all 200ms;
        font-size: 30px;
        text-decoration: none;
        color: #333;
    }

    .popup .close:hover {
        color: #ff0000;
    }

    .popup .close:focus {
            box-shadow: none;
            outline: none;
        }

    .popup .content {
        max-height: 30%;
        overflow: auto;
        overflow: hidden;
        text-align: center;
    }

    .continue-btn {
        border: none;
        width: 150px;
        height: 40px;
        border-radius: 4px;
        background-color: #611f69;
        font-weight: 500;
        font-size: 16px;
        cursor: pointer;
    }

    .continue-btn {
        text-decoration: none;
        color: #ffffff;
    }

    #popup1 button:hover {
        background-color: #401046;
    }

    .content-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        row-gap: 10px;
    }
    
    .about_btn {
        padding: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        width: 110px;
        border-radius: 4px;
        color: white;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        margin: 0;
        box-shadow: 0px 4px 4px rgb(0 0 0 / 25%);
		text-decoration: none;
    }
		
    .about_signup_button{
        width: 232px;
        height: 45px;
        background: #ff910a;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.25);
        border-radius: 10px;
        border: none;
        font-weight: 600;
        font-size: 18px;
        line-height: 156.2%;
        color: #FFFFFF;
        cursor: pointer;
    }
        
    .about_signup_button:hover{
        background: #ffb531;
        color: white;
    }
	
    .popup .about_head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
		margin-bottom: 30px;
    }
	
    .loader {
		border: 10px solid #a7aaad;
        border-radius: 50%;
        border-top: 10px solid #3498db;
        width: 80px;
        height: 80px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
    }

    /* Safari */
    @-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
    }
    
</style>

<div class="wrap" style="padding: 50px 50px;">
	<?php settings_errors(); ?>
		<div>	
			<ul class="nav nav-tabs" style="display: flex;align-items: center;justify-content: space-between;padding: 20px 10px;">
				<a href="#tab-1" style="font-size: 24px;font-weight: 500;color: white;text-decoration: none;padding-left: 20px;">Chargely Subscription Management</a>
				<li class="active" style="padding-right: 20px;"><a href="#about" class="about_btn">About</a></li>
			</ul>
		</div>

	<div id="load" class="overlay" style="display: flex;align-items: center;justify-content: center;">
            <div class="popup" style="width: 100px;height:100px;display: flex;align-items: center;justify-content: center;">
            <div class="content-containe" style="display: flex;align-items: center;column-gap: 20px;text-align: center;">    
                    <div style="font-size: 20px;font-weight: 500;line-height: 2rem;">
					<div class="loader"></div>
                    </div>                                       
                </div>
            </div>
        </div>

        <div id="about" class="overlay">
            <div class="popup" style="width: 556px;height: 616px;background: white;padding:5px 30px;">
			<a class="close" href="admin.php?page=chargely_wordpress_login" style="float: right;padding: 15px 0;">×</a><br><br>
            <div class="content-containe">    
			<div style="color: rgb(0 98 153);font-size: 17px;font-weight: 600;margin-bottom: 14px;"><span style="color: black;">Chargely - </span>Free Subscription Payments Plugin for Wordpress</span></div>
                        <div style="display: flex;row-gap: 24px;justify-content: center;flex-direction: column;margin: 30px 0;">
                            <div style="display: flex;column-gap: 12px;">
                                <div style="color: black;font-weight: 400;font-size: 18px;line-height: 2rem;">
                                    Chargely is a Advanced Subscription/Recurring Payments Plugin developed by Payment experts 
                                    previously worked with PayPal and Braintree.
                                </div>
                            </div>
                            <div style="display: flex;column-gap: 12px;">
                                <div style="color: black;font-weight: 400;font-size: 18px;line-height: 156.2%;">
                                    You don't need to be PCI certified as chargely provides a PCi certified payment page for card processing
                                </div>
                            </div>
                            <div style="display: flex;column-gap: 12px;">
                                <div style="color: black;font-weight: 400;font-size: 18px;line-height: 156.2%;">
                                    Your customer data is stored in a secured and certified environment
                                </div>
                            </div>
                        </div>
                        <div style="display: flex;align-items: center;justify-content: space-between;">
                            <div>
                                <button id="about_signup_button" class="about_signup_button">Start Free trial</button>
                            </div>
                            <div style="width: 250px;" >
                                <img style="width: 100%;height: 100%;" src="<?php print plugins_url('images/about.png', __FILE__) ?>" alt="" srcset="">
                            </div>
                        </div>
                </div>
            </div>
        </div>
        
	<div class="tab-content" style="background: white;">
		<div id="tab-1" class="tab-pane active" style="flex-direction: column;text-align: center;">
			<div style="display:flex;align-items: center;padding-top: 50px;justify-content: center;padding-left: 50px;">
				<div style="display: flex;height: 350px;width: 500px;align-items: center;flex-direction: column;">
					<img style="width: 100%; height:100%;" src="<?php print plugins_url('images/subscription.png', __FILE__) ?>" alt="img">
				</div>
				
				<div style="padding: 50px;">
					<div style="width: 500px;"><div style="font-size: 20px;font-weight: 700;">Enable Subscriptions on Your Wordpress Store</div ></div>
					<div style="text-align: center;">
					<p>Get the Merchant ID & API Key from the account settings page in <br/>
					<a target="_blank" href="https://chargely.com/merchant/dashboard/account/settings/profile">chargely.com</a></p>
					</div>
					
					<form action="javascript:void(0)" style="display: flex;flex-direction: column;row-gap: 10px;" id="frmLogin">
						<div style="display: flex;flex-direction: column;align-items: center;">
							<div class="input-icons">
                                <img src="<?php print plugins_url('images/merchant_id.png' , __FILE__) ?>" alt="" style="width: 18px;height: 18px;margin: 14px 0 0 14px;">
								<input style="" class="form-control" type="password" name="mercant_id" id="merchant_ID" placeholder="Merchant ID" maxlength="8" required>
                                <p id="merchant_id_err"  style="color: red;margin: 0;padding: 0 2px;text-align: left;"></p>
							</div>
							<div class="input-icons">
                                <img src="<?php print plugins_url('images/merchant_key.png' , __FILE__) ?>" alt="" style="width: 18px;height: 18px;margin: 14px 0 0 14px;">
								<input style="" class="form-control" type="password" ame="api_key" id="APIKEY" placeholder="API KEY" maxlength="37" required>
								<p id="api_key_err"  style="color: red;margin: 0;padding: 0 2px;text-align: left;"></p>
							</div>
							<button id="button_submit" class="sbtn" style="">Login</button>
							<p>
							New to Chargely?
							<a target="_blank" style="cursor: pointer;text-decoration: underline;" id="login_signup_button">Start Free</a>
							</p>
						</div>
					</form>
				</div>
			</div>
			<script>
			const btn = document.querySelector("#button_submit");
			const merchant_id = document.getElementById("merchant_ID");
			const api_key = document.getElementById("APIKEY");

            const login_signup_button = document.getElementById("login_signup_button");
            login_signup_button.addEventListener("click", (e) => {
                e.preventDefault()
                let origin = window.location.origin;
                let pathname = window.location.pathname;
                let about_signup_url = "https://chargely.com/oauth/merchant?redirect_uri=" + origin + pathname + "&page=chargely_wordpress_login&utm_source=woocommerceplugin&utm_medium=banner&utm_campaign=woocommerce";
                window.location.href = about_signup_url;
            });

            const about_signup_button = document.getElementById("about_signup_button");
            about_signup_button.addEventListener("click", (e) => {
                e.preventDefault()
                let origin = window.location.origin;
                let pathname = window.location.pathname;
                let about_signup_url = "https://chargely.com/oauth/merchant?redirect_uri=" + origin + pathname + "&page=chargely_wordpress_login&utm_source=woocommerceplugin&utm_medium=banner&utm_campaign=woocommerce";
                window.location.href = about_signup_url;
            });

			btn.addEventListener("click", (e) => {
				e.preventDefault()

				var merchantEmailValue = merchant_id.value.trim();
                var merchantPasswordValue = api_key.value.trim();

				if (merchantEmailValue == "") {
                    setErrorr(merchant_id, "Merchant Id is required*");
                    merchant_id.style.border = "1px solid red";
                    merchant_id.style.boxShadow = "none";
                    merchant_id.style.outline = "none";
                    return false;
                }else if(merchantEmailValue.length<8){
                    setErrorr(merchant_id, "The Merchant Id field must have 8 characters");
                    merchant_id.style.border = "1px solid red";
                    merchant_id.style.boxShadow = "none";
                    merchant_id.style.outline = "none";
                    return false;
                }else{
                    merchant_id.style.border = "1px solid green";
                    merchant_id_err.innerText = '';
                    merchant_id.style.boxShadow = "none";
                    merchant_id.style.outline = "none";
                }

                if (merchantPasswordValue == "") {
                    setErrorr(api_key, "Api Key is required*");
                    api_key.style.border = "1px solid red";
                    api_key.style.boxShadow = "none";
                    api_key.style.outline = "none";
                    return false;
                }else if(merchantPasswordValue.length<37){
                    setErrorr(api_key, "The Api Key field must have 37 characters");
                    api_key.style.border = "1px solid red";
                    api_key.style.boxShadow = "none";
                    api_key.style.outline = "none";
                    return false;
                }else {
                    api_key.style.border = "1px solid green";
                    api_key_err.innerText = '';
                    api_key.style.boxShadow = "none";
                    api_key.style.outline = "none";
                }

                function setErrorr(u, msg) {
                    var parentBox = u.parentElement;
                    parentBox.className = "input-icons";
                    var p = parentBox.querySelector("p");
                    p.innerText = msg;
                }

				window.location.href = "#load";

				const request = new XMLHttpRequest();
				request.open("GET", "https://chargely.com/api/get/merchant/by/" + merchant_id.value);

				request.onload = () => {
					let response_api_key = JSON.parse(request.response).message.api_key;
					let response_merchant_id = JSON.parse(request.response).message.merchant_id;
					if (response_api_key === api_key.value) {
                        localStorage.setItem("Merchant_id", response_merchant_id);
						localStorage.setItem("Api_Key", response_api_key);
                        window.location.href = "edit.php?post_type=chargely-plugin";
					} else {
						window.location.href = "#invalid";
					}
				}
				request.setRequestHeader( 'Content-Type',   'application/blahblah' );
				request.setRequestHeader( 'Accept', 'application/blahblah' );
				request.setRequestHeader("Authorization", "Basic " + btoa(merchant_id.value + ":" + api_key.value)); 
				request.send();
				return true;
			});

            var u = new URLSearchParams(window.location.search);
            if (u?.get('merchant_id') && u?.get('api_key')) {
                localStorage.setItem("Chargely_Woocommernce_Merchant_id", u.get('merchant_id'));
                localStorage.setItem("Chargely_Woocommernce_Api_Key", u.get('api_key'));
                document.cookie="Chargely_Woocommernce_Merchant_id=" + u.get('merchant_id');
                document.cookie="Chargely_Woocommernce_Api_Key=" + u.get('api_key');
                window.location.href = "#active_payment";
            }

			</script>
			
			<br/>
			<br/>
			<div style="display: flex;justify-content: center;column-gap: 50px;font-size: 18px; padding: 0 50px;">
				<div style="display: flex;align-items: center;justify-content: center;">
					<div style="width: 50px;display: flex;align-items: center;justify-content: center;">
						<img style="width: 100%; height:100%;" src="<?php print plugins_url('images/pci_compliance_logo.png', __FILE__) ?>" alt="img">
					</div>
					Certified Payment Page
				</div>
				<div style="display: flex;align-items: center;justify-content: center;column-gap: 5px;">
					<div style="width: 50px;display: flex;align-items: center;justify-content: center;">
						<img style="width: 100%; height:100%;" src="<?php print plugins_url('images/secure.png', __FILE__) ?>" alt="img">
					</div>
					Secure Data Storage
				</div>
				<div style="display: flex;align-items: center;justify-content: center;column-gap: 5px;">
					<div style="width: 50px;display: flex;align-items: center;justify-content: center;">
						<img style="width: 100%; height:100%;" src="<?php print plugins_url('images/payment_gateway.png', __FILE__) ?>" alt="img">
					</div>
					Popular Gateways
				</div>
				<div style="display: flex;align-items: center;justify-content: center;">
					<div style="width: 50px;display: flex;align-items: center;justify-content: center;">
						<img style="width: 100%; height:100%;" src="<?php print plugins_url('images/currency-card-color-icon.jpg', __FILE__) ?>" alt="img">
					</div>
					Multiple Currency Support
				</div>
			</div>
			<div>	
				<p style="font-size: 34px;font-weight: 500;padding: 20px;">Payment Gateway Support</p>
				<div style="display: flex;align-items: center;justify-content: center;padding: 0 100px 100px 100px;column-gap: 30px;">
					<div style="width: 150px;padding: 15px;height: 30px;border-radius: 5px;display: flex;align-items: center;justify-content: center;box-shadow: rgba(0, 0, 0, 0.15) 0px 2px 8px; background-color: #ffffff;">
						<img style="width: 100%; height:auto" src="<?php print plugins_url('images/Stripe_Logo.png', __FILE__) ?>" alt="img">
					</div>	
					<div style="width: 150px;padding: 10px;height: 50px;border-radius: 5px;display: flex;align-items: center;justify-content: center;box-shadow: rgba(0, 0, 0, 0.15) 0px 2px 8px; background-color: #ffffff;">
						<img style="width: 100%; height:auto" src="<?php print plugins_url('images/PayPal.png', __FILE__) ?>" alt="img">
					</div>
					<div style="width: 150px;padding: 10px;height: 50px;border-radius: 5px;display: flex;align-items: center;justify-content: center;box-shadow: rgba(0, 0, 0, 0.15) 0px 2px 8px; background-color: #ffffff;">
						<img style="width: 100%; height:auto" src="<?php print plugins_url('images/Braintree_logo.png', __FILE__) ?>" alt="img">					
					</div>
					<div style="width: 150px;padding: 10px;height: 50px;border-radius: 5px;display: flex;align-items: center;justify-content: center;box-shadow: rgba(0, 0, 0, 0.15) 0px 2px 8px; background-color: #ffffff;">
						<img style="width: 100%; height:auto" src="<?php print plugins_url('images/adyen.png', __FILE__) ?>" alt="img">					
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

		<div id="invalid" class="overlay">
            <div class="popup" style="width: 30%;background: red;color: white;">
            <div class="content-containe" style="display: flex;align-items: center;justify-content: space-between;text-align: center;">    
                    <div style="font-size: 20px;line-height: 2rem;">
						Invalid Credentials
                    </div>                    
                    <div>
                        <a class="close" href="admin.php?page=chargely_wordpress_login" style="color:white;">×</a>
                    </div>                    
                </div>
            </div>
        </div>

        <div id="active_payment" class="overlay">
            <div class="popup" style="width: 50%;">
                <div class="content-containe" style="display: flex;align-items: center;column-gap: 20px;text-align: center;    justify-content: space-between;">  
                <div style="font-size: 20px;font-weight: 500;line-height: 2rem;">
                    Payment Gateways Not Available Please Active Your Payment Gatways in <a href="https://chargely.com/merchant/dashboard/payment/settings" style="color: #611f69;">Chargely</a>
                </div>  
                <div>
                    <a class="close" href="admin.php?page=chargely_wordpress_login">×</a>
                </div>  
                  
                </div>
            </div>
        </div>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-SG0BWN3MYP"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-SG0BWN3MYP');
</script>





