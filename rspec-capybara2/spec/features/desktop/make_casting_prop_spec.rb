# encoding: utf-8

require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/spec_helper.rb"
require 'capybara/rspec'
# slow down
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/selenium-throttle.rb"
# global file for common tasks
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/helpers.rb"
# desktp file for common tasks
require "/Library/WebServer/Documents/iflist/iflist-dev.com/tests/rspec-capybara2/spec/desktop_helpers.rb"
 
wait = Selenium::WebDriver::Wait.new( :timeout=>2 )


feature 'Make Proposal: Fill Out Fields Except Image' , :driver => current_device_driver do
  it 'allows user to enter data and make a proposal, using HP blue button' do

  	wait = Selenium::WebDriver::Wait.new( :timeout=>2 )

    do_proposal_form_setup
    
    do_proposal_partial_fill
    # submit form
    find('a', :text => "PUBLISH").click
    
    expect(page).to have_content('Please select a source')     

    within("#title_form") do
      do_select_fill
    end   
    #submit
    find('a', :text => "PUBLISH").click

    wait.until do
      expect(page).to have_content(proposal_title) 
    end   

  end
end

feature 'Make Proposal: Fill Out All Fields w Image' , :driver => current_device_driver do
  it 'allows user to make a proposal, using HP blue button' do
    
    do_proposal_form_setup
    
    do_proposal_partial_fill

    # submit form w/o all data
    find('a', :text => "PUBLISH").click
    
    expect(page).to have_content('Please select a source')     

    within("#title_form") do
      do_select_fill

      do_form_image_upload

    end   
    #submit
    find('a', :text => "PUBLISH").click

    wait.until do
      expect(page).to have_content(proposal_title) 
    end   

  end
end


def do_select_fill
  within("#title_form") do
    find(:css, "a.select2-choice").click 
     wait.until do
      find(:xpath, "(//*[@id='select2-drop']/ul/li)[1]/div" ).click
     end
  end  
end

def do_proposal_form_setup 
  do_login valid_email, valid_pw
    #expect(page).to have_selector('.make_proposal_btn', text:  'Make a Proposal')     
    find('a', :text => "MAKE A PROPOSAL").click
    find('a', :text => "PROPOSE NOW").click
end 

def do_proposal_partial_fill
  within("#title_form") do
      fill_in 'title_name', :with => proposal_title
      fill_in 'creator_name', :with => "test creator name"
      fill_in 'description', :with => "this is my test2. this is my test2. this is my test2. this is my test2."      
    
      # Genre
      find('button.ms-choice').click
      find(:xpath, "(//input[@name='selectItem'])[2]").click
      find(:xpath, "(//input[@name='selectItem'])[3]").click
    end    
end 
