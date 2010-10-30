<?php

class Freeback_Model extends Model {
	function Freeback_Model()
	{
		parent::Model();
	}

	function getMailto($data = FALSE)
	{
		if ( is_array($data) )
		{
			foreach ($data as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}
		$this->db->where('lang', $this->user->lang );
		$this->db->order_by('weight');
           $query = $this->db->get('freeback');
           $mailto = array();
           if($query->num_rows()>0){           	foreach($query->result_array() as $row){           		$mailto[] = $row;           	}           }
		return $mailto;
	}

	function move($direction, $id)
	{
		$move = ($direction == 'up') ? -1 : 1;
		$this->db->where(array('id' => $id));
		$this->db->set('weight', 'weight+'.$move, FALSE);
		$this->db->update('freeback');

		$this->db->where(array('id' => $id));
		$query = $this->db->get('freeback');
		$row = $query->row();
		$new_ordering = $row->weight;

		if ( $move > 0 )
		{
			$this->db->set('weight', 'weight-1', FALSE);
			$this->db->where(array('weight <=' => $new_ordering, 'id <>' => $id, 'lang' => $this->user->lang));
			$this->db->update('freeback');
		}
		else
		{
			$this->db->set('weight', 'weight+1', FALSE);
			$where = array('weight >=' => $new_ordering, 'id <>' => $id, 'lang' => $this->user->lang);
			$this->db->where($where);
			$this->db->update('freeback');
		}
		//reordinate
		$i = 0;
		$this->db->order_by('weight');
		$this->db->where(array('lang' => $this->user->lang));

		$query = $this->db->get('freeback');

		if ($rows = $query->result())
		{
			foreach ($rows as $row)
			{
				$this->db->set('weight', $i);
				$this->db->where('id', $row->id);
				$this->db->update('freeback');
				$i++;
			}
		}
	}
}


