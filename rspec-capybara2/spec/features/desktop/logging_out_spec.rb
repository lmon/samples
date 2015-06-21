# encoding: utf-8

require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/spec_helper.rb"
require 'capybara/rspec'
# slow down
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/selenium-throttle.rb"
# global file for common tasks
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/helpers.rb"
# desktp file for common tasks
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/desktop_helpers.rb"

feature 'Valid User Log Out', :driver => current_device_driver do
  it 'allows the user to log out' do
    do_login valid_email, valid_pw
    expect(page).to have_selector('.make_proposal_btn', text: 'MAKE A PROPOSAL')     

    do_logout 
    
    expect(page).to have_selector('.make_proposal_btn', text: 'JOIN FREE')  

  end
end


