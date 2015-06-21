<?php
/**
 * Created by PhpStorm.
 * User: lmonaco
 * Date: 4/13/15
 * Time: 4:05 PM
 */

require_once './util/site_bootstrap.php';

use \stdClass;
use app\Views\Chat\ChatProfile;

require_once  './util/db/Fixture.php';

class ChatProfileTest extends \PHPUnit_Framework_TestCase
{

    protected $myfixture;
    protected $ChatInstance;
    protected $otheruser = array('id'=>4544, 'username'=>'Luke Mon');
    protected $debug = false;

    protected function setUp() {
        $this->myfixture = new Fixture();
        $this->ChatInstance = new ChatProfile();
    }

    // the teardown() to remove the changes made to the database:
    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */

    protected function tearDown() {

        $this->myfixture->tearDown();
        $this->ChatInstance = null;

    }

    public function test_exists()
    {
        if($this->debug) print PHP_EOL."=======".__FUNCTION__ .PHP_EOL;
        //" ". get_class($this->ChatInstance).PHP_EOL;
        $this->assertTrue(is_a($this->ChatInstance, 'app\Views\Chat\ChatProfile'));
    }


    /*
     *  1. CHAT VIEW
     */
    /*
        the loggedin user can get to a list of his/her chats
    */
    public function test_loggedin_user_can_see_chat_view()
    {
        if($this->debug) print PHP_EOL."=======".__FUNCTION__ .PHP_EOL;
        $chat_list = null;
        $user = $this->myfixture->new_user_setup("user1");
        $chat_list = $this->ChatInstance->get_chat_list($user['user_id']);
        $this->assertTrue(is_array($chat_list), "Chat List should be an array");
        $this->assertTrue((count($chat_list) == 0), "Chat List should be empty");
    }

    /*
        the loggedin user w chats can get to a list of his/her chats
    */
    public function test_loggedin_user_w_chats_can_see_chat_view()
    {
        if($this->debug) print PHP_EOL."=======".__FUNCTION__ .PHP_EOL;
        $chat_list = null;
        $user = $this->myfixture->new_user_setup("user1");
        if($this->debug) print PHP_EOL." - ".PHP_EOL;

        $chats = $this->myfixture->new_chat_setup($user['user_id'], $this->otheruser['id']);
        if($this->debug) print PHP_EOL." - ".PHP_EOL;

        $chat_list = $this->ChatInstance->get_chat_list($user['user_id']);
        if($this->debug) print PHP_EOL." - ".PHP_EOL;

        $this->assertTrue(is_array($chat_list), "Chat List should be an array");
        $this->assertTrue((count($chat_list) == 1), "Chat List count should be 1. Actual:".count($chat_list));
    }

    /*
        the logged out user cant see any chats
    */
    public function test_loggedout_user_cant_see_chats()
    {
        if($this->debug) print PHP_EOL."=======".__FUNCTION__ .PHP_EOL;
        $chat_list = null;
        $chat_list = $this->ChatInstance->get_chat_list(null);
        $this->assertFalse(is_array($chat_list), "Chat List should not be an array");
        $this->assertTrue(( $chat_list == null), "Chat List response should be null");

    }

    /*
        the user can get to a chat
    */
    public function test_can_see_a_chat()
    {
        if($this->debug) print PHP_EOL."=======".__FUNCTION__ .PHP_EOL;
        $chat_list = null;
        $user = $this->myfixture->new_user_setup("user1");

        $chat = $this->myfixture->new_chat_setup($user['user_id'], $this->otheruser['id']);

        $res = $this->myfixture->new_message_setup($user['user_id'], $this->otheruser['id'], $chat['chat_id'], "message1");
        $res = $this->myfixture->new_message_setup($user['user_id'], $this->otheruser['id'], $chat['chat_id'], "message2");

        $chat_list = $this->ChatInstance->get_chat_list($user['user_id']);
        $this->assertTrue(is_array($chat_list), "Chat List should be an array");
        $this->assertTrue((count($chat_list) == 1), "Chat List count should be 1. Actual:".count($chat_list));

        $messages = $this->ChatInstance->get_message_list($chat['chat_id']);
        $this->assertTrue((count($messages) == 2), "Messages count should be 2. Actual:".count($messages));

    }

    /*
        the user can get to a specific chat
    */
    public function test_can_see_a_specific_chat()
    {
        if($this->debug) print PHP_EOL."=======".__FUNCTION__ .PHP_EOL;
        $user = $this->myfixture->new_user_setup("user1");
        $chat = $this->myfixture->new_chat_setup($user['user_id'], $this->otheruser['id']);

        $chat_details = $this->ChatInstance->get_chat($chat['chat_id'],1);
        //print_r($chat_details);
        $this->assertTrue(($chat_details[0]['chat_id'] == $chat['chat_id']), "Chat ID should match. Actual: ".$chat_details[0]['chat_id']."!=". $chat['chat_id']);

    }

    /* 
        shows history of conversation between 2 users (scrolling/ loading in sets of 100 may be needed)
        X header: “Conversation with <username>” or “<username>”
        ! each message shows user’s name, image, and dialog text
        ! - date and time shown under each message when time elapsed since last message is > 30 minutes
        -> (exact frequency TBD)

    */
    public function test_can_see_chat_user_details()
    {
        if($this->debug) print PHP_EOL."=======".__FUNCTION__ .PHP_EOL;
        $user = $this->myfixture->new_user_setup("user1");
        $chat = $this->myfixture->new_chat_setup($user['user_id'], $this->otheruser['id']);

        $chat_details = $this->ChatInstance->get_chat($chat['chat_id']);
        $this->assertTrue(($chat_details[0]['chat_id'] == $chat['chat_id']), "Chat ID should match. Actual: ".$chat_details[0]['chat_id']."!=". $chat['chat_id']);

        $res = $this->myfixture->new_message_setup($user['user_id'], $this->otheruser['id'], $chat['chat_id'], "hello world");
        sleep(2);
        $res = $this->myfixture->new_message_setup($this->otheruser['id'], $user['user_id'], $chat['chat_id'], "right back atcha");

        $messages = $this->ChatInstance->get_message_list($chat['chat_id'],1);

        $msgUser1 = $messages[0];
        $msgUser4544 = $messages[1];

        //verify order
        //print_r($messages);
        $this->assertTrue(($msgUser1->body == "hello world"), "Message should be hello world. Actual: ". $msgUser1->body);
        $this->assertTrue(($msgUser4544->body == "right back atcha"), "Message should be right back atcha. Actual: ". $msgUser4544->body);

        //verify message 0 is from user 1
        $this->assertTrue(($msgUser1->user_id == $user['user_id']), "Message should be from user1. ");

        //verify message 0 has user 1 username
        $this->assertTrue(($msgUser1->user->username == "test1user"), "Message should be from user test1user");

        //verify message 0 has image
        //$this->assertTrue(($msgUser1->user->main_pic == "test1user"), "Main Pic should be wawawaw");

        //verify message 0 has date
        $this->assertTrue(($msgUser1->created_at != ""), "created_at should not be blank");


        //verify message 0 is from user4544
        $this->assertTrue(($msgUser4544->user_id == $this->otheruser['id']), "Message should be from  ".$this->otheruser['id']);

        //verify message 0 has user4544 username
        $this->assertTrue(($msgUser4544->user->username == $this->otheruser['username']), "Message should be from user ".$this->otheruser['username']);

        //verify message 0 has user4544 image
        //$this->assertTrue(($msgUser4544->user->main_pic == "test1user"), "Main Pic should be wawawaw");

        //verify message 0 has user4544 date
        $this->assertTrue(($msgUser4544->created_at != ""), "created_at should not be blank");

    }


    /*
     * 2. MESSAGES LIST
     */
    /*
    * shows list of all users conversed with
    */
    public function test_can_see_chat_list()
    {
        if($this->debug) print PHP_EOL."=======".__FUNCTION__ .PHP_EOL;
        $chat_list = null;
        $user = $this->myfixture->new_user_setup("user1");
        $chat = $this->myfixture->new_chat_setup($user['user_id'], $this->otheruser['id']);
        $chat = $this->myfixture->new_chat_setup($user['user_id'], 29);
        $chat = $this->myfixture->new_chat_setup($user['user_id'], 1);

        $chat_list = $this->ChatInstance->get_chat_list($user['user_id']);

        $this->assertTrue(is_array($chat_list), "Chat List should be an array");
        $this->assertTrue((count($chat_list) == 3), "Chat List count should be 3. Actual:".count($chat_list));

        //print_r($chat_list);


    }

    /*
     * each list item shows user name, image, and excerpt of the last message sent
     */
    public function test_can_see_chat_list_with_item_name_image_excerpt()
    {
        if($this->debug) print PHP_EOL."=======".__FUNCTION__ .PHP_EOL;
        $chat_list = null;
        $user = $this->myfixture->new_user_setup("user1");
        $chat = $this->myfixture->new_chat_setup($user['user_id'], $this->otheruser['id']);
        $chat = $this->myfixture->new_chat_setup($user['user_id'], 29);

        $chat_list = $this->ChatInstance->get_chat_list($user['user_id']);
        //print_r($chat_list);

        foreach($chat_list as $thechat){
            $this->assertTrue( ($thechat != ""), "Chat List should be an array");
        }
        $this->assertTrue((count($chat_list) == 2), "Chat List count should be 2. Actual:".count($chat_list));

        // insert a few messages
        $count = 0;
        foreach($chat_list as $thechat){

            $res = $this->myfixture->new_message_setup($user['user_id'], $this->otheruser['id'], $thechat->chat_id, "hello world!");
            sleep(1);
            $res = $this->myfixture->new_message_setup($this->otheruser['id'], $user['user_id'], $thechat->chat_id, "right back 'atcha");
            sleep(1);
            $res = $this->myfixture->new_message_setup($user['user_id'], $this->otheruser['id'], $thechat->chat_id, "oh, no you didnt! $count");
            $count++;
        }

        $chat_list = $this->ChatInstance->get_chat_list($user['user_id']);
        //print_r($chat_list);

        //verify excerpt is last message from the conversation
        foreach($chat_list as $thechat){
            $this->assertInstanceOf( 'stdClass', $thechat->excerpt, "Chat List Excerpt should be an object");
            $this->assertTrue( ($thechat->excerpt->body != ""), "Chat List Excerpt body should be an a string");
        }

        $this->assertEquals((String)$chat_list[0]->excerpt->body, "oh, no you didnt! 0", "0 Chat List Excerpt should be oh, no you didnt! 0. Actual:".$chat_list[0]->excerpt->body);

        $this->assertEquals((String)$chat_list[1]->excerpt->body, "oh, no you didnt! 1", "1 Chat List Excerpt should be oh, no you didnt! 1. Actual:".$chat_list[1]->excerpt->body);


    }


    /*
	 each list item shows the number of new messages since last viewed (TBD)
     */
    public function test_can_see_chat_list_item_new_count()
    {
        if($this->debug)print PHP_EOL."=======".__FUNCTION__ .PHP_EOL;
        $chat_list = null;
        $user = $this->myfixture->new_user_setup("user1");
        $chat = $this->myfixture->new_chat_setup($user['user_id'], $this->otheruser['id']);
        $chat = $this->myfixture->new_chat_setup($user['user_id'], 29);
        $chat = $this->myfixture->new_chat_setup(4544, $user['user_id']);

        $chat_list = $this->ChatInstance->get_chat_list($user['user_id']);

        // insert a few messages
        $count = 0;
        foreach($chat_list as $thechat){

            $res = $this->myfixture->new_message_setup($user['user_id'], $this->otheruser['id'], $thechat->chat_id, "hello world!");
            sleep(1);
            $res = $this->myfixture->new_message_setup($this->otheruser['id'], $user['user_id'], $thechat->chat_id, "right back 'atcha");
            sleep(1);
            $res = $this->myfixture->new_message_setup($user['user_id'], $this->otheruser['id'], $thechat->chat_id, "oh, no you didnt! $count");
            $count++;
        }

        $chat_list = $this->ChatInstance->get_chat_list($user['user_id']);

        //print_r($chat_list);

        foreach($chat_list as $thechat){
            $this->assertTrue( ($thechat != ""), "Chat List should be an array");
        }
        $this->assertTrue((count($chat_list) == 3), "Chat List count should be 3. Actual:".count($chat_list));

        //verify the unread_count for each chat. ( should be 1 for each since the other user only said one thing )
        foreach($chat_list as $thechat){
            $this->assertEquals($thechat->unread_count, 1, "Chat List Unread should be 1. Actual: ".$thechat->unread_count);
        }

    }

    /*
     * 4. BLOCK/ UNBLOCK BUTTON
     */
    /*
    * each user profile has a button to block or unblock a user from sending messages to you (this can be done at any time)
	 “Are you sure?” confirmation dialog
    */
    public function test_can_block_user()
    {
        if($this->debug)print PHP_EOL."=======".__FUNCTION__ .PHP_EOL;
        $chat_list = null;
        $user = $this->myfixture->new_user_setup("user1");

        $blockableuser = $this->myfixture->new_user_setup("blockhead_user1");
        // block user
        $res = $this->ChatInstance->block_user($user['user_id'], $blockableuser['user_id']);
        //check is blocked
        $isblocked = $this->ChatInstance->user_is_blocked($user['user_id'], $blockableuser['user_id']);
        $this->assertTrue($isblocked, "Check: User should be blocked. ");


    }

    /*
     * after blocking, you cant chat with that person
     */
    public function test_can_be_rejected_from_chatting_with_blocked_user()
    {
        if($this->debug)print PHP_EOL."=======".__FUNCTION__ .PHP_EOL;
        $chat_list = null;
        $user = $this->myfixture->new_user_setup("user1");

        $blockableuser = $this->myfixture->new_user_setup("blockhead_user1");
        // block user
        $res = $this->ChatInstance->block_user($user['user_id'], $blockableuser['user_id']);
        // try to create chat with blocked user
        $chat = $this->myfixture->new_chat_setup($user['user_id'], $blockableuser['user_id']);
        // rejected?
        $this->assertFalse($chat, "Chat should be false: Chat with blocked user.");

        // tries to send message to blocked user
        //$res = $this->myfixture->new_message_setup($blockableuser['user_id'], $user['user_id'], $chat->chat_id, "What are you going this to me?");


    }

    /*
     * after being blocked, you cant chat with that person
     */
    public function test_can_be_rejected_from_chatting_when_blocked_by_user()
    {
        if($this->debug)print PHP_EOL."=======".__FUNCTION__ .PHP_EOL;
        $chat_list = null;
        $user = $this->myfixture->new_user_setup("user1");

        $blockableuser = $this->myfixture->new_user_setup("blockhead_user1");
        // create chat
        $chat = $this->myfixture->new_chat_setup($user['user_id'], $blockableuser['user_id']);
        // valid chat?
        $this->assertTrue( isset($chat['chat_id']), "Chat should be True: Chat with non-blocked user.");

        // presumably chatting goes on
        // block user
        $res = $this->ChatInstance->block_user($user['user_id'], $blockableuser['user_id']);
        // blocked user tries to send message
        $res = $this->myfixture->new_message_setup($blockableuser['user_id'], $user['user_id'], $chat['chat_id'], "What are you going this to me?");

        // rejected?
        $this->assertFalse($res, "Chat should be false: Chat with blocked user.");


    }

    /*
     * after blocking, you can unblock that person
     */
    public function test_can_unblock_after_blocking()
    {
        if($this->debug)print PHP_EOL."=======".__FUNCTION__ .PHP_EOL;
        $chat_list = null;
        $user = $this->myfixture->new_user_setup("user1");

        $blockableuser = $this->myfixture->new_user_setup("blockhead_user1");

        $res = $this->ChatInstance->unblock_user($user['user_id'], $blockableuser['user_id']);
        $isblocked = $this->ChatInstance->user_is_blocked($user['user_id'], $blockableuser['user_id']);
        $this->assertFalse($isblocked, "User should not be blocked. ");
    }

    
}