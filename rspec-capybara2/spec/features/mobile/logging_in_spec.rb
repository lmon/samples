# encoding: utf-8

require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/spec_helper.rb"
require 'capybara/rspec'
# slow down
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/selenium-throttle.rb"
# global file for common tasks
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/helpers.rb"
# desktp file for common tasks
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/mobile_helpers.rb"
 

feature 'Valid User' , :driver => current_device_driver do
  it 'allows the user to log in' do
  	do_login valid_email, valid_pw
	  expect(page).to have_selector('.welcome_bar', text: 'Welcome, Lucas')     

  end
end
=begin
feature 'Invalid Email' , :driver => current_device_driver  do
  it 'prevents the unregistered email from logging in' do
  	do_login 'XXX@XXX.com', valid_pw
	expect(page).to have_content('Sorry, email and password did not match!')     
  end
end

feature 'Invalid Password' , :driver => current_device_driver  do
  it 'prevents the invalid password from logging in' do
  	do_login valid_email, 'XXX'
	expect(page).to have_content('Sorry, email and password did not match!')     
  end
end

feature 'Missing Password' , :driver => current_device_driver  do
  it 'prevents the missing password from logging in' do
  	do_login valid_email, ''
	expect(page).to have_content('Please enter a password')     
  end
end

feature 'Missing Email' , :driver => current_device_driver  do
  it 'prevents the missing email from logging in' do
  	do_login '', '123456'
	expect(page).to have_content('Please enter a valid email')     
  end
end

feature 'Malformed Email ' , :driver => current_device_driver  do
  it 'prevents the invalid email from logging in' do
   do_login('lukelucasmonaco.com', valid_pw )
	expect(page).to have_content('Please enter a valid email')     
  end
end

feature 'Login with Error Then Success' , :driver => current_device_driver  do
  it 'ensure the user is redirected to the correct page' do

   do_login('luke@lucasmonaco.comXX', valid_pw, '/stories/aquamanstory')
    expect(page).to have_content('Sorry, email and password did not match!')     

    within("#login_form") do
      fill_in 'email', :with => valid_email
      fill_in 'password', :with => valid_pw 
    end
    click_button 'LOG IN' 

    expect(page).to have_content('Aquaman') 
  end
end
=end
feature 'Login Screen to Terms' , :driver => current_device_driver  do
  it 'allows the user to get to the terms screen from Login' do
   
   do_show_login
   find('#terms').click
     # new window
     switch_to_popup     
     expect(page).to have_content('Terms of Use')    
     switch_to_main_window 
  end
end

feature 'Login Screen to Privacy'  , :driver => current_device_driver do
   it 'allows the user to get to the Pvcy from Login' do
  do_show_login
   find('#privacy').click
     # new window
     switch_to_popup     
     expect(page).to have_content('Privacy Policy')     
     switch_to_main_window
  end
end

feature 'Login Screen to Facebook' , :driver => current_device_driver  do
    pending("FB login to be tested on public server") do
      it 'allows the user to get to the Facebook screen from Login' do
      do_show_login
      find('#fb_login_btn').click
      expect(page).to have_selector('h1', 'Facebook is Awesome')     
    end
   
  end
end

feature 'Login redirects correctly(Story::Aquaman)' , :driver => current_device_driver  do
   it 'allows the user to get back to the Story page they were on when they logged in' do

    do_login(valid_email, valid_pw,'/stories/aquamanstory')

   expect(page).to have_content('Aquaman')     
  end
end


feature 'Login redirects correctly(Proposal::List)' , :driver => current_device_driver do
   it 'allows the user to get back to the Proposal page they were on when they logged in' do

    do_login(valid_email, valid_pw,'/proposals/4102')

   expect(page).to have_content('Ellen Wong as Linh Cinder')     
   expect(page).to have_content('CASTING PROPOSAL')     
  end
end

feature 'Login redirects correctly(Stories::List)' , :driver => current_device_driver  do
   it 'allows the user to get back to the Stories page they were on when they logged in' do

    do_login(valid_email, valid_pw,'/stories/')
    
   expect(page).to have_content('PROPOSE A STORY')     
   #expect(page).to have_content('Titles proposed for film & TV')     
   expect(page).to have_selector('h1', text: 'Stories')     
 
  end
end
