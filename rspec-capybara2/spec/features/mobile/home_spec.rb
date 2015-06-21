# encoding: utf-8

require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/spec_helper.rb"
require 'capybara/rspec'
# desktp file for common tasks
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/mobile_helpers.rb"


feature 'Main Page' do
  it 'shows the main page content' do
    visit '/'
    expect(page).to have_title('The Imagine Film List | The World\'s Platform for Movie and Casting Ideas')
  end
end

