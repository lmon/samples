# encoding: utf-8

def current_device_driver 
	:iphone
end

def valid_email 
	'luke@SSSSSSS.com'
end

def valid_pw 
	'DDDD'
end

def login_menu_selector
	'a#global_menu'
end

def loggedin_menu_selector
	'a#global_menu'
end

#def join_link_selector
	#'header a#global_menu'
#end

def login_link_selector
	'#global_login'
end

def logout_link_selector
	'a#global_logout'
end

#// override
def do_show_login ( loc = '/')
  visit loc
  
  find(login_menu_selector).click#perform # rollover
  find(login_link_selector).click
end