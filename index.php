<?php

	require_once 'dbconfig.php';
	
	if(isset($_GET['delete_id']))
	{
		// select image from db to delete
		$stmt_select = $DB_con->prepare('SELECT userPic FROM tb_anexo WHERE id_anexo =:uid');
		$stmt_select->execute(array(':uid'=>$_GET['delete_id']));
		$imgRow=$stmt_select->fetch(PDO::FETCH_ASSOC);
		unlink("user_images/".$imgRow['userPic']);
		
		// it will delete an actual record from db
		$stmt_delete = $DB_con->prepare('DELETE FROM tb_anexo WHERE id_anexo =:uid');
		$stmt_delete->bindParam(':uid',$_GET['delete_id']);
		$stmt_delete->execute();
		
		header("Location: index.php");
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
	<title>Desafio - Max Milhas</title>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">

	<link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
</head>

<body>
	<script type="text/javascript" src="scripts/modal.js"></script>
	<script type="text/javascript" src="scripts/jquery-3.0.0.min.js"></script>

	<?php
		include 'nav.html';
	?>

<br/>
<div class="container">

	<nav class="navbar navbar-default navbar-static-top ">
	  <div class="container" >
	    <ul class="nav navbar-nav">
	      <li >
	        <h4 class="text-larger"><strong>Todas as fotos</strong> </h4>
	      </li>
	    </ul>
	    <ul class="nav navbar-nav navbar-right">
	      <li>
	        <a  href="addnew.php" style="padding-right: 36px;"> <span class="glyphicon glyphicon-plus"></span> &nbsp; Cadastrar Foto </a>
	      </li>
	    </ul>
	  </div>
	</nav>
    
<br />

<div class="row">
<?php
	
	$stmt = $DB_con->prepare('SELECT id_anexo, descricao, userPic FROM tb_anexo ORDER BY id_anexo DESC');
	$stmt->execute();
	
	if($stmt->rowCount() > 0)
	{
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{	
			extract($row);
			?>
			<div class="col-xs-3">
				<p class="page-header"><?php echo $descricao; ?></p>
				<img src="user_images/<?php echo $row['userPic']; ?>" class="img-rounded foto"  
					width="200px" height="200px" 
					id="<?php echo $row['id_anexo']; ?>" 
					onclick="abreModal(<?php echo $row['id_anexo']; ?>)" 
					alt="<?php echo $row['descricao']; ?>"
				/>
				<p class="page-header">
				<span>
					<a class="btn btn-info botao-editar" href="editform.php?edit_id=<?php echo $row['id_anexo']; ?>" title="Click para editar" onclick="return confirm('Tem certeza que quer editar ?')"><span class="glyphicon glyphicon-edit"></span> Editar</a> 
					<a class="btn btn-danger botao-deletar" href="?delete_id=<?php echo $row['id_anexo']; ?>" title="Click para deletar" onclick="return confirm('Tem certeza que quer deletar?')"><span class="glyphicon glyphicon-remove-circle"></span> Deletar</a>
				</span>
				</p>
			</div>       
			<?php
		}
	}
	else
	{
		?>
        <div class="col-xs-12">
        	<div class="alert alert-warning">
            	<span class="glyphicon glyphicon-info-sign"></span> &nbsp; Nenhuma foto encontrada ... 
            </div>
        </div>
        <?php
	}
	
?>
</div>	


		<!-- Modal de exibição -->
		<div id="myModal" class="modal">
		  <span class="close">×</span>
		  <img src="images/seta-esquerda.jpg" class="seta-esquerda" onclick="paginaAnterior()">
		  <img src="images/seta-direita.jpg" class="seta-direita" onclick="proximaPagina()">
		  <img class="modal-content" id="foto-modal">
		  <div id="caption"></div>
		</div>



</div>


<!-- Latest compiled and minified JavaScript -->
<script src="bootstrap/js/bootstrap.min.js"></script>


</body>
</html>