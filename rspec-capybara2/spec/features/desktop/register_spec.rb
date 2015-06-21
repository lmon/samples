# encoding: utf-8

require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/spec_helper.rb"
require 'capybara/rspec'
# slow down
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/selenium-throttle.rb"
# global file for common tasks
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/helpers.rb"
# desktp file for common tasks
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/desktop_helpers.rb"

feature 'Register' do
  it 'allows the valid user to register with email' do

  	do_register_with_email 'Joe Tester','Joe@testdomain.com', 'testpw','testpw'

	  #success
	  expect(page).to have_selector('.make_proposal_btn', 'MAKE A PROPOSAL')     

  end
end

#add tests here for
# mismatch
# bad email
# no email
# missing pass
# existing email
# etc
feature 'Invalid User Cant Register without Facebook' do
  it 'prevents the bad user from register' do
  	pending
  	#do_login 'XXX@XXX.com', 'XXX'
	#expect(page).to have_content('Sorry, email and password did not match!')     

  end
end

