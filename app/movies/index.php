<?php

session_start();

require '../config/database.php';


$sqlPeliculas = "SELECT p.id, p.nombre, p.descripcion, g.nombre AS genero FROM peliculas AS p 
INNER JOIN genero AS g ON p.id_genero=g.id";
$peliculas = $conn->query($sqlPeliculas);

$dir = "posters/";

$contador = 1;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD </title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <div class="container pd-3 ">
        <h2 class="text-center">Registro</h2>
        <hr>
        <?php if(isset($_SESSION['msg']) && isset($_SESSION['color'])) { ?>

            <div class="alert alert-<?=$_SESSION['color']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['msg'];?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        <?php 
        unset($_SESSION['color']);
        unset($_SESSION['msg']);
        
        }?>


        <div class="row justify-content-end">
            <div class="col-auto">
                <a href="#"class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoModal"><i class="bi bi-plus-circle"></i>  Nuevo Registro</a>
            </div>
        </div>
        <table class="table table-sm table-striped table-hover mt-4">
            <thead class="table-dark">
                <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Correo</th>
                <th scope="col">Genero</th>
                <th scope="col">Imagen</th>
                <th scope="col">Aciones</th>
                </tr>
            </thead>   
            <tbody>
                <?php while($row_pelicula = $peliculas->fetch_assoc()) { ?>
                    
                    <tr>
                        <td><?= $contador; ?> </td> <!-- Mostramos el consecutivo -->
                        <td><?= $row_pelicula['nombre']; ?> </td>
                        <td><?= $row_pelicula['descripcion']; ?> </td>
                        <td><?= $row_pelicula['genero']; ?> </td>
                        <td><img src="<?= $dir .$row_pelicula['id'] . '.jpg?N=' . time();?>" width="100"></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                            data-bs-target="#editaModal" data-bs-id="<?= $row_pelicula['id'];?>"> <i class="bi bi-pencil-square"></i> Editar</a>
                            <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                            data-bs-target="#eliminaModal" data-bs-id="<?= $row_pelicula['id'];?>"> <i class="bi bi-trash"></i> Eliminar</a>
                        </td>
                    </tr>
                    
                    <?php $contador++; // Incrementamos el contador en cada iteración del bucle ?>
                <?php } ?>
            </tbody> 

        </table>
        <?php
            $sqlGenero = "SELECT id, nombre FROM genero";
            $generos = $conn->query($sqlGenero);
        ?>

        <?php
        include 'nuevoModal.php';
        ?>
        <?php $generos->data_seek(0); ?>
        <?php
        include 'editaModal.php';
        ?>
        <?php
        include 'eliminaModal.php';
        ?>

        <script>
            let nuevoModal = document.getElementById('nuevoModal')
            let editaModal = document.getElementById('editaModal')
            let eliminaModal = document.getElementById('eliminaModal')

            nuevoModal.addEventListener('shown.bs.modal', event =>{
                nuevoModal.querySelector('.modal-body #nombre').focus()
            })


            nuevoModal.addEventListener('hide.bs.modal', event =>{
                nuevoModal.querySelector('.modal-body #nombre').value = ""
                nuevoModal.querySelector('.modal-body #descripcion').value = ""
                nuevoModal.querySelector('.modal-body #genero').value = ""
                nuevoModal.querySelector('.modal-body #poster').value = ""

            })

            editaModal.addEventListener('hide.bs.modal', event =>{
                editaModal.querySelector('.modal-body #nombre').value = ""
                editaModal.querySelector('.modal-body #descripcion').value = ""
                editaModal.querySelector('.modal-body #genero').value = ""
                editaModal.querySelector('.modal-body #img_poster').value = ""
                editaModal.querySelector('.modal-body #poster').value = ""

            })


            editaModal.addEventListener('shown.bs.modal', event=> {
                let button =event.relatedTarget
                let id = button.getAttribute('data-bs-id')

                let inputId = editaModal.querySelector('.modal-body #id')
                let inputNombre = editaModal.querySelector('.modal-body #nombre')
                let inputDescripcion = editaModal.querySelector('.modal-body #descripcion')
                let inputGenero = editaModal.querySelector('.modal-body #genero')
                let poster = editaModal.querySelector('.modal-body #img_poster')


                let url = "getPelicula.php"
                let formData = new FormData()
                formData.append('id',id)

                fetch(url,{
                    method:"POST",
                    body: formData
                }).then(response => response.json())
                .then(data => {

                    inputId.value = data.id
                    inputNombre.value = data.nombre
                    inputDescripcion.value = data.descripcion
                    inputGenero.value = data.id_genero
                    poster.src = '<?= $dir ?>' + data.id + '.jpg'

                }).catch(err =>console.log(err))

            })

            eliminaModal.addEventListener('shown.bs.modal', event=> {

                let button =event.relatedTarget
                let id = button.getAttribute('data-bs-id')
                eliminaModal.querySelector('.modal-footer #id').value = id
            })
        </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>



</body>
</html>