<?php
namespace app\Models;
use StdCLass;
use Exception;

class Comments extends BaseModel{

	protected static $table = 'comments';
	/* 
		only these columns will be pulled from table
	*/	
	private static $allowed_fields = null;
		
	/* 
		primary id column name in table
	*/	
	protected static $primary_id = 'comment_id';

	/* total count */
	protected static $total_count = null;

	/* 
		object to store
	*/	
	private static $result = null;
	/*
	*/
	protected static $cache_key_prefix = 'comment_';


	public function __construct($config = null){

		if(is_array($config) && isset($config['set_size'])){
			self::set_size($config['set_size']);			
		}
		//
		parent::__construct();
	}	

	public static function Instance(){
		static $inst = null;
		if ($inst === null) {
			$inst = new Comments();
		}
		return $inst;
	}
	
	public function get_result($_mixed, $intent = "", $type = null){		 
	
	 	return false;

	}

	/* 
		get detail sqls for the comment object, with related data
		@param: String
		@return String
	*/
	public static function get_comment_detail_by_ids_sql($ids = ""){
		return "SELECT c.comment_id as 'id', c.comment, c.proposal_id, p.proposal_type, p.officially_cast, 
				t.first_name as talent_first_name, t.last_name as talent_last_name, t.url_handle as talent_url_handle, t.talent_id,	t.main_pic as main_talent_pic,			
				r.name as role_name, r.url_handle as role_url_handle , r.role_id, r.main_pic as main_role_pic,
				ti.name as title_name, ti.url_handle as title_url_handle, ti.title_id, ti.main_pic as main_title_pic, ti.status_id, 
				t_media.filename as media_talent_pic, r_media.filename as media_role_pic,
				i.identity_name, i.identity_type, i.identity_label,
				mt.main_pic as movie_title_main_pic, mt.year_released, mt.title_id as movie_title_id, mt.name as movie_title_name,
				u.first_name as user_first_name, u.last_name as user_last_name, u.user_id, u.oauth_uid, u.is_admin, u.main_pic AS user_pic

				FROM ".self::$table." c
				LEFT JOIN proposals p ON c.proposal_id = p.proposal_id
				LEFT JOIN talent t ON p.talent_id = t.talent_id
				LEFT JOIN roles r ON p.role_id = r.role_id
				LEFT JOIN titles ti ON p.title_id = ti.title_id

				LEFT JOIN movie_titles mt ON p.title_id    = mt.title_id
				LEFT JOIN media r_media ON r_media.media_id = p.role_pic_id 
				LEFT JOIN media t_media ON t_media.media_id = p.talent_pic_id
				LEFT JOIN identities i ON p.crew_position_id = i.identity_id 
				LEFT JOIN users u ON c.user_id = u.user_id

				WHERE c.comment_id IN(". $ids .") 
				AND c.anonymous <> 'Y'
                GROUP BY c.comment_id  "; 
	}

	/* 
		get comment count for a given id/type combination
		@param: Int
		@param: String
		@param: Array
		@return INT
	*/
	public static function get_count($_id, $item_type, $options = array() ){ 

		// pass expiration time (in seconds) for cache objects to expire 
		$expires = (60 * 30); // .5 hour
		$result = self::get_list(array(
            "key_name"=>@$options['key_name']."_".$_id,
            "expires"=>$expires,
             "what"=>array( // column list
                "COUNT(comment_id) AS total"
              ),
             "where"=>array(
                array(
                    "name"=>$item_type."_id",
                    "operator"=>"=",
                    "value"=>$_id
                ) 
            )
           ) 
        );

		if (is_array($result)){
			return $result[0]['total'];
		}
		return 0;
	}
	
	/* 
		get active status given id/type combination
		@param: Int
		@param: String
		@return Boolean
	*/
	public static function thread_is_deactivated($item_id, $item_type_id){
		return CommentsThreads::Instance()->is_deactivated($item_id, $item_type_id);			
	}

	/* 
		deactivate a thread based on given id/type combination
		@param: Int
		@param: String
		@return Boolean
	*/
	public static function deactivate_thread($_item_id, $_item_type_id) {
		return CommentsThreads::Instance()->deactivate($_item_id, $_item_type_id);			
	}

	/* 
		activate a thread based on given id/type combination
		@param: Int
		@param: String
		@return Boolean
	*/
	public static function activate_thread($_item_id, $_item_type_id) {
		return CommentsThreads::Instance()->activate($_item_id, $_item_type_id);			
	}
 
	/* 
		get a thread based on given id/type combination		
		@return Array
	*/
	public static function get_thread_list(){
		return CommentsThreads::Instance()->get_list(array(
            "key_name"=>'c_key', 
            "expires"=>1,
             "what"=>array( // column list
                "*"
              ),
             "limit"=>10
           ) 
        ); 
	}
	/*
		required by interface
		to be used to attach secondary and associated data
	*/
	protected function _has_relationships(){
		//self::$has_many ;
	} 

	/*
	this needs to match the schema
	*/
	protected function _get_filtered_values($pairs){

		$filter = array( );
		$arr = filter_var_array($pairs);//, $filter);
		
		/*
			filter out blank values
		*/
		$arr = array_filter($arr, function ($item) use (&$arr) {
		    if($arr[key($arr)] == ""){ next($arr); return false; }
		    next($arr);
		    return true;
		});

    	return $arr;

	}

}
