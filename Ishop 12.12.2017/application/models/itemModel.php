<?php

class itemModel extends CI_Model
{


    public function insertItem()
    {
        $itemName = $this->input->post('itemName');
        $this->db->where('item_name',$itemName);
        $result=$this->db->get('shop_item');

        if($result->num_rows()>0) {
            $this->session->set_flashdata('Error','Item you entered is already exists.!');
            redirect('itemController/addItem');
        }
        else {
            $data = array(
                'item_name' => $this->input->post('itemName'),
                'item_category' => $this->input->post('itemCategory'),
                'quantity' => ($this->input->post('quantity')),
                'measuring_unit' => $this->input->post('measuring_unit'),
                'unit_price' => $this->input->post('price'),
                'item_description' => $this->input->post('description'),
                'shop_shop_id'=>$_SESSION['shop_id'],
            );
            $this->db->insert('shop_item', $data);
        }
    }

    public function viewItems() {

        $this->db->select('*');
        $this->db->from('shop_item');
        $this->db->order_by('shop_item_id');
        $this->db->where('shop_shop_id',$_SESSION['shop_id']);
        $query = $this->db->get();

        return $query->result();

    }

//    return the row that want to be edited
    public function edit($id) {

        $this->db->where('shop_item_id',$id);
        $query = $this->db->get_where('shop_item', array('shop_item_id' => $id));

        return $query->row();

    }

//update the selected with the given data
    public function update($id) {

        $data = array(
            'item_category' => $this->input->post('itemCategory'),
            'item_name' => $this->input->post('itemName'),
            'quantity' => ($this->input->post('quantity')),
            'measuring_unit' => ($this->input->post('measuring_unit')),
            'unit_price' => $this->input->post('price'),
            'item_description' => $this->input->post('description'),
            'shop_shop_id'=>$_SESSION['shop_id'],
        );

        $this->db->where('shop_item_id',$id);
        $this->db->update('shop_item',$data);
        return $id;

    }

    public function delete($id) {

        $this->db->where('shop_item_id',$id);
        $this->db->delete('shop_item');
    }

    function Search($searchkey){

        $this->db->select('*');
        $this->db->where('shop_shop_id',$_SESSION['shop_id']);
        $this->db->from('shop_item');
        $this->db->like('item_name', $searchkey);
        $this->db->or_like('shop_item_id', $searchkey);
        $this->db->or_like('item_category', $searchkey);
        $this->db->or_like('quantity', $searchkey);
        $this->db->or_like('measuring_unit', $searchkey);
        $this->db->or_like('unit_price', $searchkey);
        $this->db->or_like('item_description', $searchkey);


        $query = $this->db->get();
        return $query->result();
    }

    public function getShopCategory()
    {
        return $_SESSION['shop_category'];
    }

    public function getShopCategoryId($shopcat)
    {
        $query=$this->db->query("SELECT shop_category_id FROM shop_category WHERE shop_category='$shopcat'");
        return $query->result_array();
    }


    public function getSubCategory(){
        $query = $this->db->query("SELECT item_category.item_category FROM item_category  INNER JOIN shop_category ON item_category.shop_category_shop_category_id=shop_category.shop_category_id");
        return $query->result();
    }

    public function getMeasuringUnit(){
        $query = $this->db->query("SELECT measuring_unit.measuring_unit FROM measuring_unit  INNER JOIN shop_category ON measuring_unit.shop_category_shop_category_id=shop_category.shop_category_id");
        return $query->result();
    }

    public function addItemCatModel($data){
        $this->db->insert('item_category', $data);
    }
    public function addUnitModel($data){
        $this->db->insert('measuring_unit', $data);


    }




}
?>