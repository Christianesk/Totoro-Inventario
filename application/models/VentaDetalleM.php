<?php
class VentaDetalleM extends CI_Model{

    /**
     * CAMPOS
     */
    var $tabla = 'venta_detalle';
    var $columnas =array('VED_CODIGO','VEN_CODIGO','ART_CODIGO','COM_CODIGO','VED_CANTIDAD','VED_PRECIO_VENTA');
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

    public function obtener_por_codigo_venta($codigo){

		$this->db->select('v.VED_CODIGO,V.ART_CODIGO,a.ART_NOMBRE,v.VED_CANTIDAD,v.VED_PRECIO_VENTA');
		$this->db->from('venta_detalle v, articulo a');
		$this->db->where('a.ART_CODIGO = v.ART_CODIGO');
        $this->db->where('v.VEN_CODIGO',$codigo);
        
		$query = $this->db->get();

		return $query->result();
    }
}
?>