<?php

	error_reporting( ~E_NOTICE );
	
	require_once 'dbconfig.php';
	
	if(isset($_GET['edit_id']) && !empty($_GET['edit_id']))
	{
		$id = $_GET['edit_id'];
		$stmt_edit = $DB_con->prepare('SELECT descricao, userPic FROM tb_anexo WHERE id_anexo =:uid');
		$stmt_edit->execute(array(':uid'=>$id));
		$edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
		extract($edit_row);
	}
	else
	{
		header("Location: index.php");
	}
	
	
	
	if(isset($_POST['btn_save_updates']))
	{
		$descricao = $_POST['descricao'];
			
		$imgFile = $_FILES['foto']['name'];
		$tmp_dir = $_FILES['foto']['tmp_name'];
		$imgSize = $_FILES['foto']['size'];
					
		if($imgFile)
		{
			//Pasta onde as imagens ficam salvas
			$upload_dir = 'user_images/'; 
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); 
			// Extensões validas
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif');
			$userpic = rand(1000,1000000).".".$imgExt;
			if(in_array($imgExt, $valid_extensions))
			{			
				if($imgSize < 5000000)
				{
					unlink($upload_dir.$edit_row['userPic']);
					move_uploaded_file($tmp_dir,$upload_dir.$userpic);
				}
				else
				{
					$errMSG = "Desculpe, seu arquivo é muito grande. Limite 5mb";
				}
			}
			else
			{
				$errMSG = "Formatos suportados: JPG, JPEG, PNG e GIF.";		
			}	
		}
		else
		{
			// Caso não selecione nenhum imagem, mantem a original
			$userpic = $edit_row['userPic']; 
		}	
						
		
		// Verifica erro
		if(!isset($errMSG))
		{
			$stmt = $DB_con->prepare('UPDATE tb_anexo 
									     SET descricao=:uname, 
										     userPic=:upic 
								       WHERE id_anexo=:uid');
			$stmt->bindParam(':uname',$descricao);
			$stmt->bindParam(':upic',$userpic);
			$stmt->bindParam(':uid',$id);
				
			if($stmt->execute()){
				?>
                <script>
				alert('Atualizado com sucesso ...');
				window.location.href='index.php';
				</script>
                <?php
			}
			else{
				$errMSG = "Algum erro ocorreu!";
			}
		
		}
		
						
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Desafio - Max Milhas</title>

<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">
<script type="text/javascript" src="scripts/jquery-3.0.0.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</head>
<body>

	<?php
		include 'nav.html';
	?>


<div class="container">

	<nav class="navbar navbar-default navbar-static-top ">
	  <div class="container" >
	    <ul class="nav navbar-nav">
	      <li >
	        <h4 class="text-larger"><strong>Editar Foto</strong> </h4>
	      </li>
	    </ul>
	    <ul class="nav navbar-nav navbar-right">
	      <li>
	        <a  href="index.php" style="padding-right: 36px;"> <span class="glyphicon glyphicon-backward"></span> &nbsp; Vizualizar todas as fotos </a>
	      </li>
	    </ul>
	  </div>
	</nav>

<div class="clearfix"></div>

<form method="post" enctype="multipart/form-data" class="form-horizontal">
	
    
    <?php
	if(isset($errMSG)){
		?>
        <div class="alert alert-danger">
          <span class="glyphicon glyphicon-info-sign"></span> &nbsp; <?php echo $errMSG; ?>
        </div>
        <?php
	}
	?>
   
    
	<table class="table table-bordered table-responsive">
	
    <tr>
    	<td><label class="control-label">Descrição</label></td>
        <td><input class="form-control" type="text" name="descricao" value="<?php echo $descricao; ?>" required /></td>
    </tr>
    
    <tr>
    	<td><label class="control-label">Foto</label></td>
        <td>
        	<p><img src="user_images/<?php echo $userPic; ?>" height="150" width="150" /></p>
        	<input class="input-group" type="file" name="foto" accept="image/*" />
        </td>
    </tr>
    
    <tr>
        <td colspan="2"><button type="submit" name="btn_save_updates" class="btn btn-success">
        <span class="glyphicon glyphicon-save"></span> Salvar
        </button>
        
        <a class="btn btn-warning" href="index.php"> <span class="glyphicon glyphicon-backward"></span> Cancelar </a>
        
        </td>
    </tr>
    
    </table>
    
</form>

</div>
</body>
</html>