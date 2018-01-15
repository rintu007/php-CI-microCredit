<?php

class DatatableModel extends CI_Model {
    
	// var $table;
    // var $column_order = [null,'memberAcID','memberShareID','memberAccountingID','memberName','memberGuardianName','memberGuardianPro','memberGuardianAge','memberPEaddrrs','memberPRaddrrs','memberNID','memberNationality','memberDOB','createDate','modifiedDate']; //set column field database for datatable orderable
    // var $column_search = ['memberAcID','memberShareID','memberAccountingID','memberName','memberGuardianName','memberGuardianPro','memberGuardianAge','memberPEaddrrs','memberPRaddrrs','memberNID','memberNationality','memberDOB','createDate','modifiedDate']; //set column field database for datatable searchable 
    // var $order; // default order 
    // protected $table;
    // protected $column_order;
    // protected $column_search;
    // protected $order;
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query($array)
    {
         
        $this->db->from($array['table']);
 
        $i = 0;
     
        foreach ($array['search'] as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($array['search']) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($array['columns'][$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if(isset($array['order'])) {
            $order = $array['order'];
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables($array)
    {
        $this->_get_datatables_query($array);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
    }
 
    function count_filtered($array)
    {
        $this->_get_datatables_query($array);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($array)
    {
        $this->db->from($array['table']);
        return $this->db->count_all_results();
    }
}