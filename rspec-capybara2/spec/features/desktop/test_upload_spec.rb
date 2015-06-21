# encoding: utf-8

require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/spec_helper.rb"
require 'capybara/rspec'
# slow down
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/selenium-throttle.rb"
# global file for common tasks
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/helpers.rb"
# desktp file for common tasks
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/desktop_helpers.rb"
 


feature 'Make Proposal: Fill Out All Fields w Image' , :driver => current_device_driver do
  it 'allows user to make a proposal, using HP blue button' do
    
    wait = Selenium::WebDriver::Wait.new( :timeout=>3 )
    visit "/tests/test_upload.php"  
    

    within("#myform") do
        fill_in 'name', :with => "this is my name"      
        expect(page).to have_selector('#pic_choose')
        expect(page).to have_selector('#pic_choose', :visible => false)
        attach_file('pic_choose', "/Users/lmonaco/Downloads/coop/unnamed.jpg")

      click_button('submit')
    end   

      expect(page).to have_content("this is my name")    
      expect(page).to have_content("unnamed.jpg")    

  end
end



  
