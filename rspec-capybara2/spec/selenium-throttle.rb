require 'selenium-webdriver'
 
   module ::Selenium::WebDriver::Remote
     class Bridge
       def execute(*args)
         res = raw_execute(*args)['value']
         # comment this out to have no slowing
          sleep 0.1
         res
       end
     end
   end 

