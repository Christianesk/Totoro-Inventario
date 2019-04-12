<?php
class IngresoDetalleM extends CI_Model {

    /**
     * CAMPOS
     */
    var $tabla = 'ingreso_detalle';
    var $columnas =array('IND_CODIGO','ING_CODIGO','ART_CODIGO','IND_CANTIDAD','IND_PRECIO_COMPRA','IND_PRECIO_VENTA');
    /**
     * MÉTODOS
     */
    public function guardar($data){
		$this->db->insert($this->tabla, $data);
		return $this->db->insert_id();
    }


    public function modificar($where, $data){
        $this->db->update($this->tabla, $data, $where);
		return $this->db->affected_rows();
    }

    public function obtener_por_codigo_ingreso($codigo){

		$this->db->select('i.IND_CODIGO,i.ART_CODIGO,a.ART_NOMBRE,i.IND_CANTIDAD,i.IND_PRECIO_COMPRA,i.IND_PRECIO_VENTA');
		$this->db->from('ingreso_detalle i, articulo a');
		$this->db->where('a.ART_CODIGO = i.ART_CODIGO');
        $this->db->where('i.ING_CODIGO',$codigo);
        
		$query = $this->db->get();

		return $query->result();
    }

    public function obtener_ultimo_registro_por_codigo_articulo($codigo){
        $this->db->select('IND_PRECIO_VENTA');
        $this->db->from('ingreso_detalle');
        $this->db->where('ART_CODIGO',$codigo);
        $this->db->order_by("IND_FECHA_CREACION", "desc");
        $this->db->limit(1);
        $query = $this->db->get();

		return /*$query->result()*/$query->row();
    }
    

    public function obtener_ultimo_precio_compra_por_articulo($codigo){
        $this->db->select('IND_PRECIO_COMPRA');
        $this->db->from('ingreso_detalle');
        $this->db->where('ART_CODIGO',$codigo);
        $this->db->order_by("IND_FECHA_CREACION", "desc");
        $this->db->limit(1);
        $query = $this->db->get();

		return /*$query->result()*/$query->row();
    }
}
?>