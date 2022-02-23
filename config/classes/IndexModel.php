<?php 
require_once 'Admin.php';
/*error_reporting(E_ALL);
ini_set("display_errors","On");*/
class IndexModel extends Admin {
    public function __construct($config = array()) {
        parent::__construct($config);
      
        $this->web_cred  = $this->fetchDBdetails("alumniportal");
        $this->web         = $this->companyPDODBConnect($this->web_cred);
    }
   
    /*** Ajax pagination ***/
    function get_blogs($post){
        $condition   = array();
        $where       = '';
        
        if(isset($post['category_id'])){
            $where .= " AND category_id =:category_id ";
            $condition[':category_id'] = $post['category_id'];
        }
        
        if(isset($post['id'])){
            $where .= " AND id =:id ";
            $condition[':id'] =$post['id'];
        } 
        
        if(isset($post['status'])){
            $where .= " AND status =:status ";
            $condition[':status'] = $post['status'];
        } 

        $group_by = $order_by = '';
        if(isset($post['order_by'])){
            $order_by = "ORDER BY ".$post['order_by'];
        }

        if(isset($post['group_by'])){
            $group_by = " GROUP BY ".$post['group_by'];
        }
        //$condition[':is_delete'] = '1';
        $params      = array('return_type'=>'all');              //return type limit,order 
    
        $query       = "SELECT * FROM blogs WHERE 1 $where $group_by $order_by"; 
       
        $resdhpi     = $this->selectPDO($query,$condition,$params,$this->web); //Fetch action
        return $resdhpi;
    }

    function update_blogs($data,$condition){
       $result = false;
        if((count($data) > 0) AND (count($condition) > 0)){
            $result = $this->updatePdo('blogs',$data,$condition,$this->web);
        }
        return $result;
    }

    function insert_blogs($data){
        $result = array();
        if(count($data) > 0){           
            $result = $this->insertPdo('blogs',$data,$this->web);
        }
        return $result;
    }

    function delete_blogs($post,$limit=10){
        $condition   = array();
        $where       = '';
        if(isset($post['id'])){
            $where .= " AND id =:id ";
            $condition[':id'] =$post['id'];
        }
                
        $response = false;
        if(count($condition) > 0){
            $query      = "DELETE FROM blogs WHERE 1 $where";
            $response   = $this->deletePDO($query,$condition,$this->web);
        }
        return $response;
    }

    function get_blog_comment($post){
        $condition   = array();
        $where       = '';
        if(isset($post['users_id'])){
            $where .= " AND users_id =:users_id ";
            $condition[':users_id'] =$post['users_id'];
        }
         
        if(isset($post['blogs_id'])){
            $where .= " AND blogs_id =:blogs_id ";
            $condition[':blogs_id'] = $post['blogs_id'];
        }
        
        if(isset($post['reply_id'])){
            $where .= " AND reply_id =:reply_id ";
            $condition[':reply_id'] =$post['reply_id'];
        }

        if(isset($post['parent_comment'])){
            $where .= " AND reply_id ='NULL' OR  reply_id ='0'";
        }

        if(isset($post['id'])){
            $where .= " AND id =:id ";
            $condition[':id'] =$post['id'];
        }
        
        if(isset($post['status'])){
            $where .= " AND status =:status ";
            $condition[':status'] =$post['status'];
        }
        
        //$condition[':is_delete'] = '1';
        $params      = array('return_type'=>'all');              //return type limit,order 
    
        $query       = "SELECT * FROM blog_comment WHERE 1 $where "; 
        $resdhpi     = $this->selectPDO($query,$condition,$params,$this->web); //Fetch action
        return $resdhpi;
    }

    function update_blog_comment($data,$condition){
       $result = false;
        if((count($data) > 0) AND (count($condition) > 0)){
            $result = $this->updatePdo('blog_comment',$data,$condition,$this->web);
        }
        return $result;
    }

    function insert_blog_comment($data){
        $result = array();
        if(count($data) > 0){           
            $result = $this->insertPdo('blog_comment',$data,$this->web);
        }
        return $result;
    }

    function delete_blog_comment($post,$limit=10){
        $condition   = array();
        $where       = '';
        if(isset($post['id'])){
            $where .= " AND id =:id ";
            $condition[':id'] =$post['id'];
        }
                
        $response = false;
        if(count($condition) > 0){
            $query      = "DELETE FROM blog_comment WHERE 1 $where";
            $response   = $this->deletePDO($query,$condition,$this->web);
        }
        return $response;
    }

    function get_job_requests($post){
        $condition   = array();
        $where       = '';
        if(isset($post['like'])){
            $where .=" AND state_id LIKE :keyword OR education_id LIKE :keyword OR country_id LIKE :keyword OR city_id LIKE :keyword";
            $condition[':keyword'] ='%'.strtolower($post['like']).'%';
        } 
        
        if(isset($post['state_id'])){
            $where .= " AND state_id =:state_id ";
            $condition[':state_id'] = $post['state_id'];
        }
         
        if(isset($post['country_id'])){
            $where .= " AND country_id =:country_id ";
            $condition[':country_id'] = $post['country_id'];
        }
        if(isset($post['id'])){
            $where .= " AND id =:id ";
            $condition[':id'] = $post['id'];
        }

        if(isset($post['tp_admin_id'])){
            $where .= " AND tp_admin_id =:tp_admin_id ";
            $condition[':tp_admin_id'] = $post['tp_admin_id'];
        }

        
        if(isset($post['city_id'])){
            $where .= " AND city_id =:city_id ";
            $condition[':city_id'] = $post['city_id'];
        }
        if(isset($post['education_id'])){
            $where .= " AND education_id IN('0','".$post['education_id']."')";
            //$condition[':edu'] = $post['education_id'];
        }
        $condition[':status'] = '1';
        $params      = array('return_type'=>'all');              
        $query       = "SELECT * FROM job_requests WHERE status =:status $where "; 
        $resdhpi     = $this->selectPDO($query,$condition,$params,$this->web); 
        return $resdhpi;
    }

    function update_job_requests($data,$condition){
       $result = false;
        if((count($data) > 0) AND (count($condition) > 0)){
            $result = $this->updatePdo('job_requests',$data,$condition,$this->web);
        }
        return $result;
    }

    function insert_job_requests($data){
        $result = array();
        if(count($data) > 0){           
            $result = $this->insertPdo('job_requests',$data,$this->web);
        }
        return $result;
    }

    function delete_job_requests($post,$limit=10){
        $condition   = array();
        $where       = '';
        if(isset($post['id'])){
            $where .= " AND id =:id ";
            $condition[':id'] =$post['id'];
        }
                
        $response = false;
        if(count($condition) > 0){
            $query      = "DELETE FROM job_requests WHERE 1 $where";
            $response   = $this->deletePDO($query,$condition,$this->web);
        }
        return $response;
    }

    function get_users($post){
        $condition   = array(); $where="";
        
        if(isset($post['email'])){
            $where .= " AND email =:email ";
            $condition[':email'] =$post['email'];
        }

        if(isset($post['password'])){
            $where .= " AND password =:password ";
            $condition[':password'] =$post['password'];
        }

        if(isset($post['id'])){
            $where .= " AND id =:id ";
            $condition[':id'] = (int)$post['id'];
        }

        if(isset($post['user_status'])){
            $where .= " AND user_status =:user_status ";
            $condition[':user_status'] =$post['user_status'];
        }

        $params      = array('return_type'=>'all');              //return type limit,order 
    
        $query       = "SELECT * FROM users WHERE 1 $where ORDER BY id DESC"; 
        
        $resdhpi     = $this->selectPDO($query,$condition,$params,$this->web); //Fetch action
        return $resdhpi;
    }

    function insert_users($data){
        $result = array();
        if(count($data) > 0){           
            $result = $this->insertPdo('users',$data,$this->web);
        }
        return $result;
    }
    
    function update_users($data,$condition){
       $result = false;
        if((count($data) > 0) AND (count($condition) > 0)){
            $result = $this->updatePdo('users',$data,$condition,$this->web);
        }
        return $result;
    }

    function delete_users($post,$limit=10){
        $condition   = array();
        $where       = '';
        if(isset($post['id'])){
            $where .= " AND id =:id ";
            $condition[':id'] =$post['id'];
        }

        $response = false;
        if(count($condition) > 0){
            $query      = "DELETE FROM users WHERE 1 $where";
            $response   = $this->deletePDO($query,$condition,$this->web);
        }
        return $response;
    }

    
}   
