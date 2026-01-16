<?php 
session_start();
if(empty($_SESSION['usuario']['user']) || isset($_POST['cerrar_session']))
{
session_destroy();
header('location:index.php');
}


require 'html2pdf/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;




if(isset($_POST['pdf']) && !empty($_POST['pdf']))
{
  ob_start();

  include'pdf.php';
  $datos_separados=explode('*',$_POST['pdf']);
  $html=ob_get_clean();
  $html2pdf = new Html2Pdf('P','lette','ES','true','UTF-8',0);

  $html2pdf->setTestTdInOnePage(false);
   $html2pdf->pdf->SetDisplayMode('fullpage');
  $html2pdf->writeHTML($html);
$html2pdf->output($datos_separados[1].'.pdf','I');
 // $html2pdf->output('lalal'.'.pdf','D');

}

?>  



<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>COTIZADOR</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

 
  <link href="js/fontawesome-free-web/css/all.css" rel="stylesheet" />


<link rel="stylesheet" href="js/jquery.typeahead.min.css">

<style type="text/css">
  
  .select2-selection__rendered
  {
    font-size: 14px;
  }
  .select2-results__option 
  {
    font-size: 12px; 
  }
  td
  {
    font-size: 14px;
  }

</style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled"  id="accordionSidebar"><!--toggled-->

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
          COTI
        </div>
        <div class="sidebar-brand-text mx-3">COTIZADOR</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item ">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>INICO</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
   <!--<div class="sidebar-heading">
        ALTAS
      </div>-->
      <!-- Nav Item - Pages Collapse Menu -->

      <li class="nav-item">
        <a href="contacto.php" class="nav-link collapsed" aria-expanded="true" aria-controls="collapseTwo">
         <i class="fas fa-users"></i>
          <span>CONTACTOS</span>
        </a>
  
      </li>


      <li class="nav-item">
          <a href="prospectos.php" class="nav-link collapsed" aria-expanded="true" aria-controls="collapseTwo">
         <i class="fas fa-user"></i>
            <span>PROSPECTOS</span>
          </a>

        </li>
      
      <li class="nav-item active">
        <a href="cotizacion.php" class="nav-link collapsed" aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-file-contract"></i>
          <span>COTIZACION</span>
        </a>

      </li>
  
  <?php 
      $tipo_usuario = $_SESSION['usuario']['tipo_user'];
        switch ($tipo_usuario) 
        {
          case '1':
          ?>
        <li class="nav-item">
          <a href="aseguradoras.php" class="nav-link collapsed" aria-expanded="true" aria-controls="collapseTwo">
            <i class="far fa-building"></i>
            <span>ASEGURADORAS</span>
          </a>

        </li>
          <?php
            break;
        }
   ?>
    




      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <!--<form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>-->

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

   


           
            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo$_SESSION['usuario']['nombres'] ?></span>
               <!-- <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">-->
              </a>

              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <!--<a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>-->
                <!--<a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>-->
                <!--<a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>-->
                <!--<div class="dropdown-divider"></div-->
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  SALIR
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
         
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
          </div>-->
          
          <div class="col-lg-12" style="border:0px red solid;">

          
          <!--aqui comienza el card del formulario-->
            <div class="card shadow mb-4 contenedor_card_formulario">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

                  <?php 
                     $tipo_user=$_SESSION['usuario']['tipo_user'];
                    switch ($tipo_user) 
                    {
                      case 1://administrador
                       ?>
                       <div class="col-lg-12" style="display: inline-block;">
                         
                        <div class="col-lg-12" style="border:0px blue solid;display:block;">
                          <button class="btn btn-primary" id="autos_admin">AUTOS</button>
                        </div>
                        <div class="col-lg-12" style="border:0px red solid;display:block; display:none ;" id="contenedor_cotizacion_autos">
                              <h6 class="m-0 font-weight-bold text-primary">
                                 <button style="border:1px grey solid;" class="btn btn-warning cotizacion">COTIZACION</button> 
                                  <button style="border:1px grey solid;" class="btn btn-warning lista_cotizaciones">LISTA DE NUEVAS COTIZACIONES</button>
                                <button style="border:1px grey solid;" class="btn btn-warning lista_renovaciones">LISTA DE RENOVACIONES</button>
                              </h6> 
                        </div>
                       </div>
                         
                       <?php
                        break;
                      case 2://master
                        # code...
                        break;
                        break;
                      case 4://autos
                      ?>
                       <h6 class="m-0 font-weight-bold text-primary">
                       <button style="border:1px grey solid;" class="btn btn-warning cotizacion">COTIZACION</button> 
                        <button style="border:1px grey solid;" class="btn btn-warning lista_cotizaciones">LISTA DE NUEVAS COTIZACIONES</button>
                        <button style="border:1px grey solid;" class="btn btn-warning lista_renovaciones">LISTA DE RENOVACIONES</button>
                     </h6>
                      <?php
                        break;
                      case 5://daños
                        # code...
                        break;
                      case 6://gm
                        # code...
                        break;
                        case 7://vida
                        # code...
                        break;
                      
                      default:
                        # code...
                        break;
                    }

                   ?>
                 
                  <!--<div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div>-->
                </div>
                <!-- Card Body -->
              
                <div class="card-body ">
                 
                <?php 
                   
                    switch ($tipo_user) 
                    {
                      case 1://administrador
                       include('cotizacion_autos.php');
                        break;
                      case 2://master
                        # code...
                        break;
                        break;
                      case 4://autos
                       include('cotizacion_autos.php');
                        break;
                      case 5://daños
                        # code...
                        break;
                      case 6://gm
                        # code...
                        break;
                        case 7://vida
                        # code...
                        break;
                      
                      default:
                        # code...
                        break;
                    }
                 ?>
                 

<div id="contenedor_general_si_no_concreta" class="col-lg-12" style="border:0px red solid;display: none;" >
                 <center><legend>CONCRETAR / NO CONCRETAR </legend></center>
        <div class="col-lg-12" id="contenedor_formulario_concreta" style="display: none;">
            <form method="post" id="formulario_concretar">
              <div class="col-lg-12" >
                               <div style="display: inline-block;" class="col-lg-1">
                                  <label for="firstname" class="control-label" style="font-size: 12px;">PROSPECTO: </label>
                                </div>
                               <div style="display: inline-block;" class="col-lg-10">
                                <input type="text"  readonly="" class="form-control" name="prospecto_concretar" id="prospecto_concretar">
                              </div>
                               <div>  
                                  <center>
                                    <span id="error_prospecto_concretar" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                  </center>
                              </div>                        
                        </div>
                        <div class="col-lg-12" >
                               <div style="display: inline-block;" class="col-lg-1">
                                  <label for="firstname" class="control-label" style="font-size: 12px;">DESCRIPCION VEHICULO : </label>
                                </div>
                               <div style="display: inline-block;" class="col-lg-10">
                                <input type="text"  readonly="" class="form-control" name="descripcion_vehiculo_concretar" id="descripcion_vehiculo_concretar">
                              </div>
                               <div>  
                                  <center>
                                    <span id="error_descripcion_vehiculo_concretar" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                  </center>
                              </div>                        
                        </div>

                      <div class="col-lg-12" >
                               <div style="display: inline-block;" class="col-lg-1">
                                  <label for="firstname" class="control-label" style="font-size: 12px;">OPCIONES COTIZADAS: </label>
                                </div>
                               <div style="display: inline-block;" class="col-lg-10">
                                <select style="text-align-last: center;"  name="opciones_Cotizadas" id="opciones_Cotizadas"  class="form-control " >
                                </select>
                              </div>
                               <div>  
                                  <center>
                                    <span id="error_opciones_Cotizadas" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                  </center>
                              </div>                        
                        </div>
                      <div class="col-lg-12" >
                               <div style="display: inline-block;" class="col-lg-1">
                                  <label for="firstname" class="control-label" style="font-size: 12px;">PRIMA NETA ANUAL:</label>
                                </div>
                               <div style="display: inline-block;" class="col-lg-10">
                                <input type="text" name="prima_anual_concretar" id="prima_anual_concretar" readonly="" class="form-control " >
                              </div>
                               <div>  
                                  <center>
                                    <span id="error_telefono_contacto" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                  </center>
                              </div>                        
                          </div>
                        

                        <div class="col-lg-12" >
                               <div style="display: inline-block;" class="col-lg-1">
                                  <label for="firstname" class="control-label" style="font-size: 12px;">No.POLIZA: </label>
                                </div>
                               <div style="display: inline-block;" class="col-lg-10">
                                <input type="text" name="numero_poliza" id="numero_poliza" class="form-control " >
                              </div>
                               <div>  
                                  <center>
                                    <span id="error_numero_poliza" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                  </center>
                              </div>                        
                          </div>

                           <div class="col-lg-12" >
                               <div style="display: inline-block;" class="col-lg-1">
                                  <label for="firstname" class="control-label" style="font-size: 12px;">INICIO VIGENCIA: </label>
                                </div>
                               <div style="display: inline-block;" class="col-lg-10">
                                <input type="text" name="inicio_vigencia" id="inicio_vigencia" class="form-control" >
                              </div>
                               <div>  
                                  <center>
                                    <span id="error_inicio_vigencia" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                  </center>
                              </div>                        
                          </div>
                            
                            <div class="col-lg-12" style="text-align:center;">
                              <button type="button" value="" class="btn btn-primary" name="guardar_concretar" id="guardar_concretar">GUARDAR</button>
                            </div>
                      </form>
        </div>

        <div class="col-lg-12" id="contenedor_formulario_no_concreta" style="display: none;">
            
            <form id="formulario_no_concreta">
              <div class="col-lg-12" >
                       <div style="display: inline-block;" class="col-lg-1">
                          <label for="firstname" class="control-label" style="font-size: 12px;">MOTIVOS: </label>
                        </div>
                       <div style="display: inline-block;" class="col-lg-10">
                        <select class="form-control" style="text-align-last: center;" name="select_opcion_no_concretar" id="select_opcion_no_concretar">
                          <option value="0">SELECCIONA UN MOTIVO</option>
                          <option value="PRECIO">PRECIO</option>
                          <option value="SIN RESPUESTA">SIN RESPUESTA</option>
                            <option value="SOLO COTIZO">SOLO COTIZO</option>
                          
                        </select>
                      </div>
                       <div>  
                          <center>
                            <span id="error_select_opcion_no_concretar" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                          </center>
                      </div>                        
                  </div> 
                   <div class="col-lg-12" style="text-align:center;">
                      <button type="button" value="" class="btn btn-primary" id="guardar_no_concretar">GUARDAR</button>
                    </div> 
            </form>
          
        </div>
                
                     

<div class="alert alert-danger" role="alert" id="alerta_error_concretar" style="display:none;">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"><i class="fas fa-exclamation"></i></span>
  <span class="sr-only completo_mensaje">Error:</span><span id="texto_error_concretar">Se produjo un error.No se pudo registrar los datos</span>
</div>

<div class="alert alert-success" role="alert" id="alerta_correcta_concretar" style="display:none ;">
  <span class="glyphicon glyphicon-ok" aria-hidden="true"><i class="fas fa-check"></i></span>
  <span class="sr-only">Exitoso</span><span id="texto_correcto_concretar">Registro completo</span>
</div>

  </div>

  <!-------aqui termina el contenedor de concretar-->
                 
                


                </div>
              </div>


          </div>
        
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5  style="text-align:center;" class="modal-title" id="exampleModalLabel">¿LISTO PARA SALIR?</h5>
          <!--<button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>-->
        </div>
        <div class="modal-body" style="text-align: center;">Presiona salir para cerrar sesion</div>
        <div class="modal-footer">
          <form method="post"> 
            
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
          <button class="btn btn-primary" type="submit" name="cerrar_session">Salir</button>
           </form>
         
        </div>
      </div>
    </div>
  </div>
<!--
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cotizacion_capturada">
  Launch demo modal
</button>-->
  <!-- CERRADA COTIZACION Modal-->
  <div class="modal fade" id="cotizacion_capturada" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5  style="text-align:center;" class="modal-title" id="exampleModalLabel">ALTA COTIZACION</h5>
          <!--<button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>-->
        </div>
        
        <div class="modal-body" style="text-align: center;">
          <form id="formulario_pdf" method="post">
                  <span id="texto_mensaje" style="color:#f57f17;font-weight: bold;">LA COTIZACION FUE REGISTRADA CORRECTAMENTE</span>
                    <br>
                
                  <button id="boton_pdf" name="pdf" value="1" style="width: 13%;margin-top: 3%;" type="submit" class="btn btn-outline-success">
                    <img style="width: 100%;" src="img/pdf_imagen.jpg">
                  </button>
                  
          </form>

        </div>
        
        <div class="modal-footer">
         
            
          <button class="btn btn-secondary" type="button" data-dismiss="modal">CERRAR</button>
         
         
        </div>
      </div>
    </div>
  </div>

  <!-- DATOS PRSOPECTO,CONTACTO,OPCIONES DE EMPRESAS-->
  <div class="modal fade" id="modal_mostrar_datos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5  style="text-align:center;" class="modal-title" id="titulo_mostrar_datos">DATOS</h5>
          <!--<button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>-->
        </div>
        
        <div class="modal-body" style="text-align: center;" id="cuerpo_modal_mostrar_datos">
          

        </div>
        
        <div class="modal-footer">
         
            
          <button class="btn btn-secondary" type="button" data-dismiss="modal">CERRAR</button>
         
         
        </div>
      </div>
    </div>
  </div>

    <!-- Alerta anulacion cancelar concretacion-->
  <div class="modal fade" id="modal_anulacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5  style="text-align:center;" class="modal-title" id="titulo_mostrar_datos">ALERTA</h5>
          <!--<button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>-->
        </div>
        
        <div class="modal-body" style="text-align: center;" id="cuerpo_modal_mostrar_datos">
          ¿ESTAS SEGURO DE ANULAR LA CONCRETACION O NO CONCRETACION?

        </div>
        
        <div class="modal-footer">
         
          <button class="btn btn-success" type="button" name="confirmacion_anualcion" id="confirmacion_anulacion">SI</button>
          <button class="btn btn-primary" type="button" data-dismiss="modal">NO</button>
         
         
        </div>
      </div>
    </div>
  </div>


  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!--datepicker librerias-->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <!---->   



  <!--librerias para select2-->
  <link rel="stylesheet" type="text/css" href="js/select2.min.css">
  <script type="text/javascript" src="js/select2.min.js"></script>
 <!--aqui termina librerias select2-->

<!--libreria lista en input typehead-->
<script src="js/jquery.typeahead.min.js"></script> 

<!--aqui termina typehead-->

<script type="text/javascript" src="js/decimal.js"></script>




<!--libreria tablas-->
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="
https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">

<script src="DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
<!--fin liberia tablas-->

<!--liberrias de moment van despues de las tablas-->
<script src="js/moment2.min.js"></script>
<script src="js/datetime-moment.js"></script>
<!--fin de las librerias de moment-->



<script src="js/dataTables.buttons.min.js"></script>
<script src="js/jszip.min.js"></script>
<script src="js/buttons.html5.min.js"></script>


<script type="text/javascript" src="DataTables-1.10.18/js/dataTables.select.min.js"></script>

<script type="text/javascript" src="js/ad.js"></script>
<?php 

if(strcmp($tipo_usuario, '1')==0 ||strcmp($tipo_usuario, '4')==0||strcmp($tipo_usuario, '2')==0)
{


 ?>
<script type="text/javascript" src="js/formulario.js"></script>
<script type="text/javascript" src="js/funciones_cotizacion_tabla.js"></script>
<?php 
}

 ?>
</body>
<script type="text/javascript">
  $('#fecha_vigencia').datepicker({
                minDate: 0,
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd-mm-yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''});


     $('#inicio_vigencia').datepicker({
                minDate: 0,
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd-mm-yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''});
  
</script>
</html>
