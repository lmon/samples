# encoding: utf-8

require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/spec_helper.rb"
require 'capybara/rspec'
# slow down
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/selenium-throttle.rb"
# global file for common tasks
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/helpers.rb"
# desktp file for common tasks
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/mobile_helpers.rb"

feature 'Join Screen' do
  it 'allows the user to see the Join screen' do
	do_join
	expect(page).to have_selector('h2', 'Join')     
	expect(page).to have_content('Sign Up with Facebook')     
	expect(page).to have_content('SIGN UP WITH EMAIL')     
  end
end

feature 'Join Screen to Email Sign up' do
  it 'allows the user to get to the sign up with email screen from Join' do
	do_join
	 expect(page).to have_selector('h2', text: 'JOIN')     
	 find('a.submit').click
	 expect(page).to have_selector('h2', text: 'SIGN UP')     
  end
end

feature 'Join Screen to Login' do
  it 'allows the user to get to the login screen from Join' do
	do_join
	 find('.login_prompt a').click
	 expect(page).to have_selector('h2', text: 'LOG IN')     
  end
end

feature 'Join Screen to Terms' do
  it 'allows the user to get to the terms screen from Join' do
	do_join
	 find('#terms').click
	   # new window
	   switch_to_popup	   
	   expect(page).to have_content('Terms of Use')    
	   switch_to_main_window 
  end
end

feature 'Join Screen to Privacy' do
   it 'allows the user to get to the Pvcy from Join' do
	do_join
	 find('#privacy').click
	   # new window
	   switch_to_popup	   
	   expect(page).to have_content('Privacy Policy')     
	   switch_to_main_window
  end
end

feature 'Join Screen to Facebook' do
   pending
   #it 'allows the user to get to the Facebook screen from Join' do
	#do_join
	# find('#fb_login_btn').click
	 #expect(page).to have_selector('h1', text: 'Facebook is Awesome')     
  #end
end

