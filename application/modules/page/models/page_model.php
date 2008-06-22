<?php

	class Page_Model extends Model {
		
		function Page_model()
		{
			parent::Model();
			
			$this->table = 'pages';
		}
		
		function get_page($data)
		{
			$this->db->select('*');
			
			if ( is_array($data) )
			{
				foreach ($data as $key => $value)
				{
					$this->db->where($key, $value);
				}
			}
			else
			{
				$this->db->where('uri', $data);
			}
			
			$query = $this->db->get($this->table, 1);
			
			if ( $query->num_rows() == 1 )
			{
				return $query->row_array();
			}
			else
			{
				return false;
			}
		}
		
		function list_pages()
		{
			$this->db->select('id, title, uri, active');
			$query = $this->db->get($this->table);
			
			$pages = array();
			
			if ( $query->num_rows() > 0 )
			{
				$pages = $query->result_array();
			}
			
			return $pages;
		}

		function new_pages($limit = 10)
		{
			$this->db->select('id, title, uri, active');
			$this->db->order_by('id', 'DESC');
			$this->db->limit($limit);
			$query = $this->db->get($this->table);
			
			$pages = array();
			
			if ( $query->num_rows() > 0 )
			{
				$pages = $query->result_array();
			}
			
			return $pages;
		}		
	}


?>