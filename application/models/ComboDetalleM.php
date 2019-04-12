<?php
class ComboDetalleM extends CI_Model{

    /**
     * CAMPOS
     */
    var $tabla = 'combo_detalle';
    var $columnas =array('COD_CODIGO','COD_ART_CODIGO','COD_CANTIDAD');
    /**
     * MÉTODOS
     */
    public function guardar($data){
        $this->db->insert($this->tabla, $data);
		return $this->db->insert_id();
    }

    public function obtenerPorCodigo($codigo){

    }

    public function modificar($where, $data){
        $this->db->update($this->tabla, $data, $where);
		return $this->db->affected_rows();
    }

    
    public function obtener_por_codigo_combo($codigo)
	{
        $this->db->select('a.ART_NOMBRE,c.COD_CANTIDAD,c.COD_ART_CODIGO,c.COD_TAB_ART,c.COD_CODIGO,c.COD_PRECIO_VENTA');
        $this->db->from('articulo a,combo_detalle c');
        $this->db->where('a.ART_CODIGO=c.COD_ART_CODIGO');
		$this->db->where('c.COD_TAB_ART',$codigo);
		$this->db->where('c.COD_ESTADO',1);
		$query = $this->db->get();

		return $query->result();
    }

    
    public function obtener_lista_por_codigo_combo($codigo)
	{
        $this->db->select('c.COD_CANTIDAD,c.COD_ART_CODIGO,c.COD_ART_STOCK,c.COD_CODIGO');
        $this->db->from('articulo a,combo_detalle c');
        $this->db->where('a.ART_CODIGO=c.COD_ART_CODIGO');
		$this->db->where('c.COD_TAB_ART',$codigo);
		$this->db->where('c.COD_ESTADO',1);
		$query = $this->db->get();

		return $query->result();
    }

    
    public function obtener_cantidad_articulo_en_combos($codigo)
	{
        $this->db->select_sum('COD_CANTIDAD_POR_STOCK',' cantidad');
        $this->db->from('combo_detalle');
		$this->db->where('COD_ART_CODIGO',$codigo);
		$this->db->where('COD_ESTADO',1);
		$query = $this->db->get();

		return $query->result()[0];
    }
    

    public function dependencia($codigo) {
	
		$where['COD_TAB_ART'] = $codigo;
		$query = $this->db->get_where('combo_detalle',$where,1);
	
		if($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}
?>