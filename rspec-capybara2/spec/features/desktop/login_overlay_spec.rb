# encoding: utf-8

require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/spec_helper.rb"
require 'capybara/rspec'
# desktp file for common tasks
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/desktop_helpers.rb"

feature 'Login Overlay' do
  it 'shows the Login Window' do
    visit '/'
    	#selenium specific
    	page.driver.browser.action.move_to(page.find('.login').native).perform
	    find('#menu_login a.menulink.login_link').click
    expect(page).to have_content('Don\'t have an account?')

  end
end

