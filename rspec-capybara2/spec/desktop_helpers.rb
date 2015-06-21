# encoding: utf-8

def current_device_driver 
	:selenium
	#:iphone
end

def valid_email 
	'luke@XXXX.com'
end

def valid_pw 
	'XXXX'
end

def login_menu_selector
	'header .login'
end

def loggedin_menu_selector
	'header .logged_in'
end

def join_link_selector
	'#menu_login a.menulink.join_link'
end

def login_link_selector
	'#menu_login a.menulink.login_link'
end
def logout_link_selector
	'#menu_login a.menulink.login_link'
end

def proposal_title 
	"title proposal test # #{rand(10)}"
end	