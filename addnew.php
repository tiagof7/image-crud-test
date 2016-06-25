<?php

	error_reporting( ~E_NOTICE ); 
	
	require_once 'dbconfig.php';
	
	if(isset($_POST['btnsave']))
	{
		$descricao = $_POST['descricao'];
		
		$imgFile = $_FILES['foto']['name'];
		$tmp_dir = $_FILES['foto']['tmp_name'];
		$imgSize = $_FILES['foto']['size'];
		
		
		if(empty($descricao)){
			$errMSG = "Por favor informe a descrição!";
		}
		else if(empty($imgFile)){
			$errMSG = "Por favor selecione a imagem!";
		}
		else
		{
			//Pasta onde as imagens ficam salvas
			$upload_dir = 'user_images/'; 
	
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); 
		
			// Extensões validas
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); 
		
			// Numero aleatorio para salvar no banco
			$userpic = rand(1000,1000000).".".$imgExt;
				
			// Verifica extensão
			if(in_array($imgExt, $valid_extensions)){			
				// Checa tamanho do arquivo
				if($imgSize < 5000000)				{
					move_uploaded_file($tmp_dir,$upload_dir.$userpic);
				}
				else{
					$errMSG = "Desculpe, seu arquivo é muito grande. Limite 5mb!";
				}
			}
			else{
				$errMSG = "Formatos suportados: JPG, JPEG, PNG e GIF.";		
			}
		}
		
		
		// Verifica erro
		if(!isset($errMSG))
		{
			$stmt = $DB_con->prepare('INSERT INTO tb_anexo(descricao,userPic) VALUES(:uname, :upic)');
			$stmt->bindParam(':uname',$descricao);
			$stmt->bindParam(':upic',$userpic);
			
			if($stmt->execute())
			{
				$successMSG = "Nova foto cadastrada";
				//Espera 2 segundos e redireciona para a tela inicial
				header("refresh:2;index.php"); 
			}
			else
			{
				$errMSG = "Erro ao inserir.";
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

<!-- Optional theme -->
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">

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
	        <h4 class="text-larger"><strong>Cadastrar Foto</strong> </h4>
	      </li>
	    </ul>
	    <ul class="nav navbar-nav navbar-right">
	      <li>
	        <a  href="index.php" style="padding-right: 36px;"> <span class="glyphicon glyphicon-backward"></span> &nbsp; Vizualizar todas as fotos </a>
	      </li>
	    </ul>
	  </div>
	</nav>
    

	<?php
	if(isset($errMSG)){
			?>
            <div class="alert alert-danger">
            	<span class="glyphicon glyphicon-info-sign"></span> <strong><?php echo $errMSG; ?></strong>
            </div>
            <?php
	}
	else if(isset($successMSG)){
		?>
        <div class="alert alert-success">
              <strong><span class="glyphicon glyphicon-info-sign"></span> <?php echo $successMSG; ?></strong>
        </div>
        <?php
	}
	?>   

<form method="post" enctype="multipart/form-data" class="form-horizontal">
	    
	<table class="table table-bordered table-responsive">
	
    <tr>
    	<td><label class="control-label">Descrição</label></td>
        <td><input class="form-control" type="text" name="descricao" placeholder="Digite uma descrição" value="<?php echo $descricao; ?>" /></td>
    </tr>
    
    <tr>
    	<td><label class="control-label">Foto</label></td>
        <td><input class="input-group" type="file" name="foto" accept="image/*" /></td>
    </tr>
    
    <tr>
        <td colspan="2"><button type="submit" name="btnsave" class="btn btn-success">
        <span class="glyphicon glyphicon-save"></span> &nbsp; Salvar
        </button>
        </td>
    </tr>
    
    </table>
    
</form>


</div>

<script src="bootstrap/js/bootstrap.min.js"></script>


</body>
</html>