
def wait(duration = 2)
  Selenium::WebDriver::Wait.new( :timeout=>duration )
end

def do_join(loc = '/')
  visit loc
  # selenium specific
  page.driver.browser.action.move_to(page.find(login_menu_selector).native).perform # rollover
    find(join_link_selector).click
end

 def do_logout(loc = '/')
  visit loc
  # selenium specific
  page.driver.browser.action.move_to(page.find(loggedin_menu_selector).native).perform # rollover
 
end

def do_login(user, pass, loc = '/')
  visit loc
  
  do_show_login(loc)

	 within("#login_form") do
    	fill_in 'email', :with => user
    	fill_in 'password', :with => pass
  	end
  	click_button 'LOG IN' 

end

def do_show_login ( loc = '/')
  visit loc
  
  page.driver.browser.action.move_to(page.find(login_menu_selector).native).perform # rollover
    find(login_link_selector).click
end

def do_register_with_email (user,email,pass,pass_confirm,loc = '/')

  visit loc
    # selenium specific
    page.driver.browser.action.move_to(page.find(login_menu_selector).native).perform
    find('#menu_login a.menulink.join_link').click
    # SIGN UP WITH EMAIL
    expect(page).to have_selector('.signup_button', 'SIGN UP WITH EMAIL')
    find('.signup_button').click
    
    expect(page).to have_content('By clicking "Submit", I agree to the IF List')

    within("#signup-form") do
      fill_in 'full_name', :with => user
      fill_in 'email', :with => email
      fill_in 'password', :with => pass
      fill_in 'password-confirm', :with => pass_confirm

# click select2-drop-mask
    page.driver.browser.action.move_to(page.find('#s2id_autogen1').native).perform
    find('#s2id_autogen1').click

# click 1977
  select('1977', :from => 'year')

# click div.gender_select  div.choices .male
    page.driver.browser.action.move_to(page.find('.male.radiobox').native).perform
    find('.male.radiobox').click

# drag the arrow  div.push_arrow i.fa.fa-arrow-right
    element = find('div.push_arrow i.fa.fa-arrow-right', 'item_1')
    target  = page.find(:id, 'list_2')


    end
    click_button 'SUBMIT'

end

def do_form_image_upload
    # show the field using JS 
    script = "$('input[type=file]').show().css({opacity: 100, display: 'block'});"
    page.driver.browser.execute_script(script)     
    attach_file('pic_choose', "/Users/lmonaco/Downloads/coop/unnamed.jpg")

end


 # switches focus to new popup
 def switch_to_popup
    page.driver.browser.switch_to.window(page.driver.browser.window_handles.last)
 end

 # switches focus to main browser
 def switch_to_main_window
    page.driver.browser.switch_to.window(page.driver.browser.window_handles.first)
 end
