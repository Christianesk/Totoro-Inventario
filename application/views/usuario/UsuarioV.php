<br>
		<h3 style="float: right;"> Usuarios</h3>
		<br>
		<div id="menu" style="float: left;">
			<?php $this->load->view('menu/Menu');?>
		</div>
		<br>
		<br>
		<div id="formulario">
			<?php $this->load->view('usuario/UsuarioFormulario');?>
		</div>
		<br>
		<div id="tabla">
		 	<?php $this->load->view('usuario/UsuarioTabla');?>
		</div>