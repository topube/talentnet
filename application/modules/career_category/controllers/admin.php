<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends MX_Controller {
	private $module = 'career_category';
	public $table= 'career_category';
	function __construct(){
		parent::__construct();
		$this->table = PREFIX.$this->table;
		$this->mid= (int)$this->input->get('mid');
		$this->load->model('admin_model','model');
		$this->load->model('adminwz/adminwz_model', 'adminwz_model');
		$this->template->set_template('admin');
	}
	
	/*-------------------------------------- FrontEnd --------------------------------------*/	
	function index(){
		$this->model->permission($this->mid, 'read');
		$get= array(
			'ps'			=> (int)$this->input->get('ps'),
			'p'				=> (int)$this->input->get('p'),
			'field'			=> filter_input_xss($this->input->get('field')),
			'sort'			=> filter_input_xss($this->input->get('sort')),
			'range'			=> filter_input_xss($this->input->get('range')),
			'keyword'		=> filter_input_xss($this->input->get('k'))
		);
		$page_size		= ($get['ps']) ? $get['ps'] : 10;
		$page_num		= ($get['p']) ? $get['p'] : 1;
		$total_row	 	= $this->model->getList(-1,-1, $get);	
		$start_row 		= ($page_num - 1) * $page_size;		
		$result = $this->model->getList($page_size, $start_row, $get);


		/* if(!empty($result)){
			foreach($result as $r){
				$r->name_category = $r->category == 'wz_information' ? 'Information' : 'New Arrivals';
				$table_category = $r->category == 'wz_information' ? CAREER_CATEGORY_TB : NEW_ARRIVALS_CATEGORY_TB;
				$slug_category = $r->category == 'wz_information' ? 'information-center' :'market-entry';
				$detail = $this->model->get('slug_en,category_id', $r->category ,"`status` = 1 AND `id` = '{$r->c_id}'");
				if(!empty($detail)){
					$category =  $this->model->get('slug_en', $table_category ,"`status` = 1 AND `id` = '{$detail->category_id}'");
					if(!empty($category)){
						$r->link_detail = base_url().'en/'.$slug_category.'/'.$category->slug_en.'/'.$detail->slug_en;
					}
				}
			}
		} */
		$data= array(
			'module'		=> $this->module,
			'sort'			=> ($this->input->get('sort') == 'asc') ? 'desc' : 'asc',
			'pagination'	=> admin_pagination($total_row, $page_size, $page_num , 3),
			'current_url'	=> current_url().'?'.$_SERVER['QUERY_STRING'],
			'result'		=> $result,
			'mid'           => (int)$this->input->get('mid')
		);
		
        if($this->input->get('excel')!='' && $this->input->get('excel')){
            $this->load->view('BACKEND/report', $data);
        }
        else{
    		$this->template->write('title', 'Subscribe');
    		$this->template->write_view('content', 'BACKEND/list', $data);
    		$this->template->render();
        }
	}
	function edit(){
		$this->session->set_userdata('referrer_page_list', $this->agent->referrer());
		
		$nid= (int)$this->input->get('nid');
		$this->model->permission($this->mid, 'edit');
		$result= $this->model->get('*', CAREER_CATEGORY_TB, "id = '{$nid}'");
		if(!empty($result)){
			//$result->type = $result->category =='wz_information' ? 'Information' : 'New Arrivals';
		}
		$form= array(
			'result'	=> $result,
			'mid'		=> $this->mid
		);
				
		$data= array(
			'module'			=> $this->module,
			'result'			=> $result,
			'nid' 				=> $nid,
			'form'				=> $form
		);

		$this->template->write('title', 'Subscribe');
		$this->template->write_view('content', 'BACKEND/edit', $data);
		$this->template->render();
	}
	
	function save(){
		$nid= (int)$this->input->post('nid');
		$mid= (int)$this->input->post('mid');
		$this->model->permission($mid, 'edit');
		$data= array();$arr_error= array();$error= false;$txt= '';
		
		$this->adminwz_model->getFormData($mid, $nid, $data, $data_field, $arr_error, $error);
		
		$array_field = array();
	
		foreach($array_field as $field=>$value){
			$this->admin_model->validate_ext($arr_error, $error, $field, $value.' chưa nhập');
		}		
		
		if(empty($error)){
			$this->model->save($nid, $mid, $data, $data_field, $txt);
		}
		
		$json= array(
			'st'		=> (empty($error))?'SUCCESS':'FALSE',
			'error'		=> $arr_error,
			'txt'		=> (!empty($txt)) ? ($txt == 'insert') ? 'Insert Success' : 'Update Success' : ''
		);	
		print_r(json_encode($json));
	}
	
	function callback($nid= 0){
		
	}
	
	function delete($result= 0){
		
	}
	
	function status($nid= 0){
		
	}
	
	function filter_category(){
		$parent_id = $this->input->post('parent_id');
		$news_category = $this->model->fetch('*',CAREER_GROUP_TB,"`status` = 1 AND `parent` ='{$parent_id}'","id","asc");
		$html ='<select class="span6 chosen" data-placeholder="Choose a Thể Loại" tabindex="1" name="category_id">';
		$html .='<option value="">Choose category</option>';
        if(!empty($news_category)){
			foreach($news_category as $category){
				$html.='<option value="'.$category->id.'" >'.$category->name_en.'</option>';
			}
		}
		$html .='</select>';
		echo $html;
		
	}
}	