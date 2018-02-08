<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function getCategory()
	{
		$this->db->select('*');
		$data = $this->db->get('danhmuc');
		$data = $data->result_array();
		return $data;
	}

	public function addCategory($name)
	{
		$data = array('name' => $name);
		$this->db->insert('danhmuc', $data);

		echo json_encode($this->db->insert_id());
	}

	public function updateCategory($id, $name)
	{
		$this->db->set('name', $name);
		$this->db->where('id', $id);
		return $this->db->update('danhmuc');
	}

	public function deleteCategory($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('danhmuc');
	}

	// ==========// 
	// ! DỊCH VỤ //        
	// ==========// 

	public function getService()
	{
		$this->db->select('*');
		$data = $this->db->get('dichvu');
		$data = $data->result_array();
		return $data;
	}

	public function getServiceById($id)
	{
		$this->db->select('*');
		$this->db->where('id', $id);
		$data = $this->db->get('dichvu');
		$data = $data->result_array();
		return $data;
	}

	public function addService($name,$image,$summary,$content,$keyword)
	{
		$data = array(
			'name'    => $name,
			'image'   => $image,
			'summary' => $summary,
			'content' => $content,
			'keyword' => $keyword
		);
		$this->db->insert('dichvu', $data);
		return $this->db->insert_id();
	}

	public function updateService($id,$name,$hinhanh,$summary,$content,$keyword)
	{
		$data = array(
			'name'    => $name,
			'image'   => $hinhanh,
			'summary' => $summary,
			'content' => $content,
			'keyword' => $keyword
		);
		$this->db->where('id', $id);
		return $this->db->update('dichvu', $data);
	}

	public function deleteService($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('dichvu');
	}

	// ===========// 
	// ! BÀI VIẾT //        
	// ===========// 	

	public function getPost()
	{
		$this->db->select('*');
		$data = $this->db->get('baiviet');
		$data = $data->result_array();
		return $data;
	}

	public function getPostById($id)
	{
		$this->db->select('*');
		$this->db->where('id', $id);
		$data = $this->db->get('baiviet');
		$data = $data->result_array();
		return $data;
	}

	public function addPost($name,$image,$summary,$content)
	{
		$data = array(
			'name'    => $name,
			'image'   => $image,
			'summary' => $summary,
			'content' => $content
		);
		$this->db->insert('baiviet', $data);
		return $this->db->insert_id();
	}

	public function updatePost($id,$name,$hinhanh,$summary,$content)
	{
		$data = array(
			'name'    => $name,
			'image'   => $hinhanh,
			'summary' => $summary,
			'content' => $content
		);
		$this->db->where('id', $id);
		return $this->db->update('baiviet', $data);
	}

	public function deletePost($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('baiviet');
	}

	// ===========// 
	// ! SẢN PHẨM //        
	// ===========// 	

	public function count_all()
	{
		$query = $this->db->get("sanpham");
		return $query->num_rows();
	}	

	public function fetch_details($limit, $start)
	{
		$output = '';
		$this->db->select("*");
		$this->db->from("sanpham");
		$this->db->order_by("name", "ASC");
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		$output .= '
		  <table class="table table-responsive danhsach table-striped" id="country_table">
                <tr>
                  <th width="15px">#</th>
                  <th>Tên danh mục</th>
                  <th style="width: 25%">Hình ảnh</th>
                  <th>Danh mục</th>
                  <th>Giá</th>
                  <th>Số lượng</th>
                  <th>Kích thước</th>
                  <th>Trạng thái</th>
                  <th style="width: 10%">Hành động</th>
                </tr>
		  ';
		$count = $start;
	  	foreach($query->result() as $row)
		{
		   $count ++;
		   $imgs = json_decode($row->image);
		   $base_url = base_url();
		   $output .= '
		        <tr class="sanpham-'.$row->id.' ?>">
                  <td class="text-primary" style="font-weight: 900">'.$count.'</td>
                  <td class="stt">'.$row->name.'</td>
                  <td class="ten" v-for="img in imgs">';
                  	for ($i=0; $i < count($imgs); $i++) { 
                  		$output .= '<img src="'.$imgs[$i].'" alt="'.$imgs[$i].'" class="img-fluid" style="height: 50px; padding-right: 5px">';
                  	}
                  $output .= '</td>
                  <td>'.$row->category.'</td>
                  <td>'.$row->price.' ₫</td>
                  <td>'.$row->quantity.'</td>
                  <td>'.$row->size.'</td>';
                  if ($row->status == 'true') {
                  	$output .= '<td><span class="badge badge-success">Kích hoạt</span></td>';
                  } else {
                  	$output .= '<td><span class="badge badge-danger">Disable</span></td>';
                  }
                  
                  $output .=  '<td> <a href="'.$base_url.'Admin/suasanpham/'.$row->id.'" class="btn btn-warning suaajax"><i class="fa fa-pencil"></i></a>
                    <a href="" class="btn btn-danger" data-toggle="modal" data-target="#myModalDel-'.$row->id.'"><i class="fa fa-times"></i></a>

                    <div class="modal fade" id="myModalDel-'.$row->id.'" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Xóa</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <h5>Bạn có muốn xóa: '.$row->name.'</h5>
                            <img class="img-fluid rounded" src="'.$imgs[0].'" alt="<?php echo $img ?>">
                          </div>
                          <div class="modal-footer">
                            <a href="'.$row->id.'" type="button" class="btn btn-danger pull-right xoaajax">Xóa</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr> 
		   ';
		}
	  	$output .= '</table>';
	  	return $output;
	}


	public function getProduct()
	{
		$this->db->select('*');
		$data = $this->db->get('sanpham');
		$data = $data->result_array();
		return $data;
	}

	public function getProductById($id)
	{
		$this->db->select('*');
		$this->db->where('id', $id);
		$data = $this->db->get('sanpham');
		$data = $data->result_array();
		return $data;
	}

	public function addProduct($name,$category,$content,$keyword,$price,$quantity,$state,$size,$image) 
	{
		if($state == 'on') 
			$state = 'true';
		else $state = 'false';
		$data = array(
			'name'     => $name,
			'category' => $category,
			'content'  => $content,
			'keyword'  => $keyword,
			'price'    => $price,
			'quantity' => $quantity,
			'status'   => $state,
			'size'     => $size,
			'image'    => $image
		);
		return $this->db->insert('sanpham', $data);
	}

	public function updateProduct($id,$name,$category,$content,$keyword,$price,$quantity,$size,$state,$image)
	{
		$data = array(
			'name'     => $name,
			'category' => $category,
			'content'  => $content,
			'keyword'  => $keyword,
			'price'    => $price,
			'quantity' => $quantity,
			'size'     => $size,
			'status'   => $state,
			'image'    => $image
		);
		$this->db->where('id', $id);
		return $this->db->update('sanpham', $data);		
	}

	public function deleteProduct($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('sanpham');
	}

}

/* End of file Admin_model.php */
/* Location: ./application/models/Admin_model.php */