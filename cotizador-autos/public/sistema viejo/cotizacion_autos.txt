 <div id="contenedor_formulario_cotizacion" style="display: none;">
   

 <h6 class="m-0 font-weight-bold text-primary">FORMULARIO</h6>
 <form id="formulario_cotizacion">

   <div class="col-lg-12" style="border:0px red solid;">
                    <legend style="font-size: 14px;font-weight: bold;color:black;text-align-last: center;">COTIZACION DE SEGURO DE AUTOMOVILES</legend>
                    <center>
                      <select class="form-control col-lg-12" name="tipo_cotizacion" id="tipo_cotizacion" style="font-size: 14px; text-align-last: center;width:80%" >
                        <option value="0">SELECCIONA TIPO DE COTIZACION</option>
                        <option value="NUEVA">NUEVA</option>
                        <option value="RENOVACION">RENOVACION</option>
                      </select>
                      </center>
                      <div>  
                            <center>
                              <span id="error_tipo_cotizacion" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                      </div>
                  </div>

                   <div class="col-lg-12" id="contenedor_hora_solicitada">
                         <div style="display: inline-block;" class="col-lg-1">
                            <label for="firstname" class="control-label" style="font-size: 12px;">HORA SOLICITADA:</label>
                          </div>

                           <div style="display: inline-block;text-align:center;" class="col-lg-10">
                            <input style="text-align:center;" type="time" name="hora_solicitada" id="hora_solicitada" class="form-control " disabled="">
                          </div>
                          <div>  
                            <center>
                              <span id="error_hora_solicitada" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                          </div>                          
                      </div>

                  <div class="col-lg-12" style="border:0px red solid;margin-top: 1%;">
                    <legend style="font-size: 14px;font-weight: bold;color:black;text-align-last: center;">CONTACTOS</legend>
                    <center>
                      <select class="form-control col-lg-12" name="contactos" id="contactos" style="font-size: 10px; text-align-last: center;width:80%" >
                        <option value="0">SELECCIONA UN CONTACTO</option>
                      
                      </select>
                      </center>
                      <div>  
                            <center>
                              <span id="error_contactos" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                      </div>
                  </div>
                 

                 


                   <div class="col-lg-12" style="border:0px red solid;margin-top: 1%;">
                    <legend style="font-size: 14px;font-weight: bold;color:black;text-align-last: center;">PROSPECTOS/ASEGURADO</legend>
                    <center>
                      <select class="form-control col-lg-12" name="prospectos_asegurados" id="prospectos_asegurados" style="font-size: 10px; text-align-last: center;width:80%" >
                        <option value="0">SELECCIONA UN PROSPECTOS/ASEGURADO</option>
                      
                      </select>
                      </center>
                      <div>  
                            <center>
                              <span id="error_prospectos_asegurados" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                      </div>
                  </div>


                 
                  
                  <div class="col-lg-12" style="display:none;border:0px red solid;color:black;" id="contenedor_general_prospectos"  >
                    <hr>
                      <legend style="font-size: 14px;font-weight: bold;text-align: center;">INFORMACION DEL ASEGURADO</legend>
                   
                    
                    <div class="col-lg-12" id="contenedor_paterno_dato" style="display: none;" >
                         <div style="display: inline-block;" class="col-lg-1">
                            <label for="firstname" class="control-label" style="font-size: 12px;">APELLIDO PATERNO: </label>
                          </div>
                         <div style="display: inline-block;" class="col-lg-10">
                          <input type="text" name="apellido_paterno" id="apellido_paterno" class="form-control " readonly="">
                        </div>
                         <div>  
                            <center>
                              <span id="error_nombre_asegurado" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                      </div>                        
                      </div>
                      
                      <div class="col-lg-12"  id="contenedor_materno_dato" style="display: none;">
                         <div style="display: inline-block;" class="col-lg-1">
                            <label for="firstname" class="control-label" style="font-size: 12px;">APELLIDO MATERNO: </label>
                          </div>
                         <div style="display: inline-block;" class="col-lg-10">
                          <input type="text" name="apellido_materno" id="apellido_materno" class="form-control " readonly="">
                        </div>
                         <div>  
                            <center>
                              <span id="error_nombre_asegurado" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                      </div>                        
                      </div>


                      <div class="col-lg-12" >
                         <div style="display: inline-block;" class="col-lg-1">
                            <label for="firstname" class="control-label" style="font-size: 12px;">NOMBRE: </label>
                          </div>
                         <div style="display: inline-block;" class="col-lg-10">
                          <input type="text" name="nombre_asegurado" id="nombre_asegurado" class="form-control " readonly="" >
                        </div>
                         <div>  
                            <center>
                              <span id="error_nombre_asegurado" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                      </div>                        
                      </div>


 
                      <div class="col-lg-12" >
                         <div style="display: inline-block;" class="col-lg-1">
                            <label for="firstname" class="control-label" style="font-size: 12px;">C.P. </label>
                          </div>

                           <div style="display: inline-block;" class="col-lg-10">
                            <input type="text" name="codigo_postal" id="codigo_postal" class="form-control " readonly="">
                          </div>
                           <div>  
                            <center>
                              <span id="error_codigo_postal" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                      </div>                            
                      </div>

                      <div class="col-lg-12" >
                         <div style="display: inline-block;" class="col-lg-1">
                            <label for="firstname" class="control-label" style="font-size: 12px;">COLONIA: </label>
                          </div>

                           <div style="display: inline-block;" class="col-lg-10">
                            <input type="text" name="colonia" id="colonia"class="form-control " readonly="">
                          </div> 
                          <div>  
                            <center>
                              <span id="error_colonia" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                      </div>                         
                      </div>
                      <div class="col-lg-12" >
                         <div style="display: inline-block;" class="col-lg-1">
                            <label for="firstname" class="control-label" style="font-size: 12px;">ESTADO: </label>
                          </div>

                           <div style="display: inline-block;" class="col-lg-10">
                            <input type="text" name="estado" id="estado" class="form-control " readonly="">
                          </div>
                          <div>  
                            <center>
                              <span id="error_estado" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                      </div>                                
                      </div>

                  </div>

                   <div class="col-lg-12" style="border:0px red solid;color:black;">
                    <hr>
                      <legend style="font-size: 14px;font-weight: bold;text-align: center;">DESCRIPCION DEL VEHICULO</legend>
                   
                      <div class="col-lg-12" >
                         <div style="display: inline-block;" class="col-lg-1">
                            <label for="firstname" class="control-label" style="font-size: 12px;">MARCA:</label>
                          </div>

                           <div style="display: inline-block;" class="col-lg-10">
                            <input type="text" name="marca" id="marca" class="form-control " >
                          </div>
                          <div>  
                            <center>
                              <span id="error_marca" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                          </div>                          
                      </div>

                      <div class="col-lg-12" >
                         <div style="display: inline-block;" class="col-lg-1">
                            <label for="firstname" class="control-label" style="font-size: 12px;">DESCRIPCION: </label>
                          </div>

                           <div style="display: inline-block;" class="col-lg-10">
                            <input type="text" name="descripcion" id="descripcion"class="form-control " >
                          </div>
                          <div>  
                            <center>
                              <span id="error_descripcion" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                          </div>                        
                      </div>
                      <div class="col-lg-12" >
                         <div style="display: inline-block;" class="col-lg-1">
                            <label for="firstname" class="control-label" style="font-size: 12px;">MODELO: </label>
                          </div>

                           <div style="display: inline-block;" class="col-lg-10">
                            <input type="text" name="modelo" id="modelo" class="form-control " maxlength="4">
                          </div>
                           <div>  
                            <center>
                              <span id="error_modelo" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                          </div>                               
                      </div>


                      <div class="col-lg-12" >
                       <div style="display: inline-block;" class="col-lg-1">
                          <label for="firstname" class="control-label" style="font-size: 12px;">USO DE LA UNIDAD: </label>
                        </div>

                         <div style="display: inline-block;" class="col-lg-10">
                          <input type="text" name="uso_de_unidad" id="uso_de_unidad" class="form-control " >
                        </div>
                        <div>  
                            <center>
                              <span id="error_uso_de_unidad" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                          </div>                         
                    </div>

                    
                       <div class="col-lg-12" >
                         <div style="display: inline-block;" class="col-lg-1">
                            <label for="firstname" class="control-label" style="font-size: 12px;">TIPO AUTO:</label>
                          </div>
                         <div style="display: inline-block;" class="col-lg-10">
                          <select class="form-control" name="tipo_auto" id="tipo_auto" style="font-size: 12px; text-align-last: center;">
                            <option value="0">SELECCIONA TIPO DE AUTO</option>
                            <option value="AUTO">AUTO</option>
                            <option value="MOTO">MOTO</option>
                            <option value="PICK UP">PICK UP</option>
                            <option value="CAMION">CAMION</option>
                          </select>
                        </div>
                        <div>  
                            <center>
                              <span id="error_tipo_auto" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                          </div>                           
                      </div>
                    
                    <div class="col-lg-12" id="contenedor_descripcion_de_la_carga" style="display: none;">
                         <div style="display: inline-block;" class="col-lg-1">
                            <label for="firstname" class="control-label" style="font-size: 12px;">DESCRIPCION DE LA CARGA:</label>
                          </div>
                         <div style="display: inline-block;" class="col-lg-10">
                          <select class="form-control" name="carga" id="carga" style="font-size: 12px; text-align-last: center;">
                            <option value="0">SELECCIONA TIPO DE CARGA</option>
                            <option value="A NO PELIGROSA">A NO PELIGROSA</option>
                            <option value="B PELIGROSA">B PELIGROSA</option>
                            <option value="C MUY PELIGROSA">C MUY PELIGROSA</option>
                           
                          </select>

                        </div>
                         <div>  
                            <center>
                              <span id="error_carga" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                          </div>                              
                      </div>
   
                    
                  </div>

                   <div class="col-lg-12" style="border:0px red solid;color:black; display: none;" id="contenedor_formacion_poliza_renovar" >
                    <hr>
                      <legend style="font-size: 14px;font-weight: bold;text-align: center;">INFORMACION POLIZA A RENOVAR</legend>


                      <div class="col-lg-12" >
                       <div style="display: inline-block;" class="col-lg-1">
                          <label for="firstname" class="control-label" style="font-size: 12px;">COMPAÑIA ACTUAL: </label>
                        </div>

                         <div style="display: inline-block;" class="col-lg-10">
                          <input type="text" name="compañia_actual" id="compañia_actual" class="form-control " >
                        </div>
                        <div>  
                            <center>
                              <span id="error_compañia_actual" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                          </div>   
                      </div>
                    <div class="col-lg-12" >
                       <div style="display: inline-block;" class="col-lg-1">
                          <label for="firstname" class="control-label" style="font-size: 12px;">FIN DE VIGENCIA: </label>
                        </div>

                         <div style="display: inline-block;" class="col-lg-10">
                          <input type="text" name="fecha_vigencia" id="fecha_vigencia" class="form-control " >
                        </div> 

                     <div>  
                            <center>
                              <span id="error_fecha_vigencia" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                          </div>
                    </div>

                    <div class="col-lg-12" >
                       <div style="display: inline-block;" class="col-lg-1">
                          <label for="firstname" class="control-label" style="font-size: 12px;">POLIZA A RENOVAR: </label>
                        </div>

                         <div style="display: inline-block;" class="col-lg-10">
                          <input type="text"  name="poliza_a_renovar" id="poliza_a_renovar" class="form-control " >
                        </div>
                           <div> 
                         <center>
                              <span id="error_poliza_a_renovar" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                            </div>                      
                    </div>

                      <div class="col-lg-12" >
                       <div style="display: inline-block;width: 21%; border:0px red solid" class="col-lg-3">
                          <label for="firstname" class="control-label" style="font-size: 12px;">PRIMA DEL AÑO ANTERIOR: $</label>
                        </div>

                         <div style="display: inline-block;border:0px red solid;width:70.9%" class="col-lg-9">
                          <input type="text" min="0" step="0.01" name="prima_año" id="prima_año" class="form-control " >
                        </div>
                        <div> 
                         <center>
                              <span id="error_prima_año" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                            </div>                      
                    </div>


                   </div>


                   <div class="col-lg-12" style="border:0px red solid;color:black;">
                      <hr>
                        <legend style="font-size: 14px;font-weight: bold;text-align: center;">ASEGURADORAS</legend>
 

                    <div class="col-lg-12" id="contenedor_paquete_solicitado" style="">
                          <div style="display: inline-block;" class="col-lg-1">
                            <label for="firstname" class="control-label" style="font-size: 12px;">PAQUETE SOLICITADO O CONTRATADO:</label>
                          </div>
                         <div style="display: inline-block;" class="col-lg-10">                        

                          <select class="form-control col-lg-12" name="paquete" id="paquete" style="font-size: 12px; text-align-last: center;width: 100%" >
                            <option value="0">SELECCIONA PAQUETE</option>
                            <option value="AMPLIA">AMPLIA</option>
                            <option value="LIMITADA">LIMITADA</option>
                            <option value="RESPONSABILIDAD CIVIL">RESPONSABILIDAD CIVIL</option>
                          </select>
 
                        </div>
                        <div> 
                         <center>
                              <span id="error_paquete" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                            </div>                         
                      </div>




                      <div class="col-lg-12" >
                          <div style="display: inline-block;" class="col-lg-1">
                            <label for="firstname" class="control-label" style="font-size: 12px;">CANTIDAD DE ASEGURADORAS:</label>
                          </div>
                         <div style="display: inline-block;" class="col-lg-10">                        

                          <select class="form-control col-lg-12" name="cantidad_aseguradoras" id="cantidad_aseguradoras" style="font-size: 12px; text-align-last: center;width: 100%">
                            <option value="0">SELECCIONA CANTIDAD DE ASEGURADORAS</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                          </select>
 
                        </div>
                        <div> 
                         <center>
                              <span id="error_cantidad_aseguradoras" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                            </center>
                            </div>                          
                      </div>

                       
                      

                      <div class="col-lg-12 table-responsive" style="border:0px blue solid;margin-top: 1%; display: ;" id="contendor_tabla">
                        
                        <table class="table table-hover  table-condensed" border="">
                          <thead>
                            <tr>
                              <th  style="font-size: 12px;" >ASEGURADORAS</th>
                              <th>
                                <select class="form-control" name="empresas_opcion1" id="empresas_opcion1" disabled="">
                                  <option value="0" style="font-size: 12px;">SELECCIONA UNA ASEGURADORA</option>
                                  <option value="AXA" style="font-size: 12px;">AXA</option>
                                  <option value="BANORTE" style="font-size: 12px;">BANORTE</option>
                                  <option value="BX+" style="font-size: 12px;">BX+</option>
                                  <option value="CHUBB" style="font-size: 12px;">CHUBB</option>
                                  <option value="GNP" style="font-size: 12px;">GNP</option>
                                  <option value="HDI SEGUROS" style="font-size: 12px;">HDI SEGUROS</option>
                                  <option value="QUALITAS" style="font-size: 12px;">QUALITAS</option>
                                </select>

                                  <div> 
                                   <center>
                                        <span id="error_empresas_opcion1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                </div>  
                              </th>
                              <th>
                                <select class="form-control" name="empresas_opcion2" id="empresas_opcion2" disabled="">
                                  <option value="0" style="font-size: 12px;">SELECCIONA UNA ASEGURADORA</option>
                                  <option value="AXA" style="font-size: 12px;">AXA</option>
                                  <option value="BANORTE" style="font-size: 12px;">BANORTE</option>
                                  <option value="BX+" style="font-size: 12px;">BX+</option>
                                  <option value="CHUBB" style="font-size: 12px;">CHUBB</option>
                                  <option value="GNP" style="font-size: 12px;">GNP</option>
                                  <option value="HDI SEGUROS" style="font-size: 12px;">HDI SEGUROS</option>
                                  <option value="QUALITAS" style="font-size: 12px;">QUALITAS</option>
                                </select>
                                  <div> 
                                   <center>
                                        <span id="error_empresas_opcion2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                </div>  
                              </th>
                              <th>
                                
                                <select class="form-control" name="empresas_opcion3" id="empresas_opcion3" disabled="">
                                  <option value="0" style="font-size: 12px;">SELECCIONA UNA ASEGURADORA</option>
                                  <option value="AXA" style="font-size: 12px;">AXA</option>
                                  <option value="BANORTE" style="font-size: 12px;">BANORTE</option>
                                  <option value="BX+" style="font-size: 12px;">BX+</option>
                                  <option value="CHUBB" style="font-size: 12px;">CHUBB</option>
                                  <option value="GNP" style="font-size: 12px;">GNP</option>
                                  <option value="HDI SEGUROS" style="font-size: 12px;">HDI SEGUROS</option>
                                  <option value="QUALITAS" style="font-size: 12px;">QUALITAS</option>
                                </select>
                                  <div> 
                                   <center>
                                        <span id="error_empresas_opcion3" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                </div>  
                              </th>
                              <th>
                                
                                <select class="form-control" name="empresas_opcion4" id="empresas_opcion4" disabled="">
                                  <option value="0" style="font-size: 12px;">SELECCIONA UNA ASEGURADORA</option>
                                  <option value="AXA" style="font-size: 12px;">AXA</option>
                                  <option value="BANORTE" style="font-size: 12px;">BANORTE</option>
                                  <option value="BX+" style="font-size: 12px;">BX+</option>
                                  <option value="CHUBB" style="font-size: 12px;">CHUBB</option>
                                  <option value="GNP" style="font-size: 12px;">GNP</option>
                                  <option value="HDI SEGUROS" style="font-size: 12px;">HDI SEGUROS</option>
                                  <option value="QUALITAS" style="font-size: 12px;">QUALITAS</option>
                                </select>
                                  <div> 
                                   <center>
                                        <span id="error_empresas_opcion4" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                </div>  
                              </th>
                              <th>
                                
                                <select class="form-control" name="empresas_opcion5" id="empresas_opcion5" disabled="">
                                  <option value="0" style="font-size: 12px;">SELECCIONA UNA ASEGURADORA</option>
                                  <option value="AXA" style="font-size: 12px;">AXA</option>
                                  <option value="BANORTE" style="font-size: 12px;">BANORTE</option>
                                  <option value="BX+" style="font-size: 12px;">BX+</option>
                                  <option value="CHUBB" style="font-size: 12px;">CHUBB</option>
                                  <option value="GNP" style="font-size: 12px;">GNP</option>
                                  <option value="HDI SEGUROS" style="font-size: 12px;">HDI SEGUROS</option>
                                  <option value="QUALITAS" style="font-size: 12px;">QUALITAS</option>
                                </select>
                                  <div> 
                                   <center>
                                        <span id="error_empresas_opcion5" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                </div>  
                              </th>
                            </tr>
                            <tbody>
                              <tr>
                                <td colspan="6" style="text-align:center;font-weight: bold;">DESGLOSE DE COBERTURAS</td>
                              </tr>
                              <tr>
                                <td style="font-size: 12px;font-weight: bold;">DAÑOS MATERIALES</td>
                                <td>
                                  <select class="form-control" id="daños_opcion1_selec" name="daños_opcion1_selec" disabled="">
                                   <option style="font-size: 12px;" value="0">SELECCIONA TIPO DE DAÑO</option>
                                    <option style="font-size: 12px;" value="V.COMERCIAL">V.COMERCIAL</option>
                                    <option style="font-size: 12px;" value="V.CONVENIDO">V.CONVENIDO</option>
                                    <option style="font-size: 12px;" value="V.FACTURA">V.FACTURA</option>
                                  </select>
                                   <div> 
                                   <center>
                                        <span id="error_daños_opcion1_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                </div>
                                </td>
                                <td>
                                   <select class="form-control" id="daños_opcion2_selec" name="daños_opcion2_selec" disabled="">
                                     <option style="font-size: 12px;" value="0">SELECCIONA TIPO DE DAÑO</option>
                                    <option style="font-size: 12px;" value="V.COMERCIAL">V.COMERCIAL</option>
                                    <option style="font-size: 12px;" value="V.CONVENIDO">V.CONVENIDO</option>

                                    <option style="font-size: 12px;" value="V.FACTURA">V.FACTURA</option>
                                  
                                  </select>
                                   <div> 
                                   <center>
                                        <span id="error_daños_opcion2_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                </div>
                                </td>
                                <td>
                                   <select class="form-control" id="daños_opcion3_selec" name="daños_opcion3_selec" disabled="">
                                     <option style="font-size: 12px;" value="0">SELECCIONA TIPO DE DAÑO</option>
                                    <option style="font-size: 12px;" value="V.COMERCIAL">V.COMERCIAL</option>
                                    <option style="font-size: 12px;" value="V.CONVENIDO">V.CONVENIDO</option>

                                    <option style="font-size: 12px;" value="V.FACTURA">V.FACTURA</option>
                                  
                                  </select>
                                   <div> 
                                   <center>
                                        <span id="error_daños_opcion3_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                </div>
                                </td>
                                <td>
                                   <select class="form-control" id="daños_opcion4_selec" name="daños_opcion4_selec" disabled="">
                                     <option style="font-size: 12px;" value="0">SELECCIONA TIPO DE DAÑO</option>
                                    <option style="font-size: 12px;" value="V.COMERCIAL">V.COMERCIAL</option>
                                    <option style="font-size: 12px;" value="V.CONVENIDO">V.CONVENIDO</option>

                                    <option style="font-size: 12px;" value="V.FACTURA">V.FACTURA</option>
                                  
                                  </select>
                                   <div> 
                                   <center>
                                        <span id="error_daños_opcion4_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                </div>
                                </td>
                                <td>
                                   <select class="form-control" id="daños_opcion5_selec" name="daños_opcion5_selec" disabled="">
                                     <option style="font-size: 12px;" value="0">SELECCIONA TIPO DE DAÑO</option>
                                    <option style="font-size: 12px;" value="V.COMERCIAL">V.COMERCIAL</option>
                                    <option style="font-size: 12px;" value="V.CONVENIDO">V.CONVENIDO</option>

                                    <option style="font-size: 12px;" value="V.FACTURA">V.FACTURA</option>
                                  
                                  </select>
                                   <div> 
                                   <center>
                                        <span id="error_daños_opcion5_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                </div>
                                </td>  
                              </tr>
<!----------------IMPORTE FACTURA DAÑOS MATERIALES--->
 <tr>
                                <td style="font-size: 12px;font-weight: bold;">IMPORTE FACTURA</td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="daños_material_importe_factura_1" class="form-control" id="daños_material_importe_factura_1" disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_daños_material_importe_factura_1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="daños_material_importe_factura_2" class="form-control" id="daños_material_importe_factura_2"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_daños_material_importe_factura_2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="daños_material_importe_factura_3" class="form-control" id="daños_material_importe_factura_3"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_daños_material_importe_factura_3" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="daños_material_importe_factura_4" class="form-control" id="daños_material_importe_factura_4"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_daños_material_importe_factura_4" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="daños_material_importe_factura_5" class="form-control" id="daños_material_importe_factura_5"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_daños_material_importe_factura_5" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>
          
                            <!-----------DEDUCIBLE DM------->


                               <tr>
                                <td style="font-size: 12px;font-weight: bold;">DEDUCIBLE DM</td>
                                <td>
                                  <select style="text-align-last:center;" class="form-control" id="deducible_opcion1" name="deducible_opcion1" disabled>
                                      <option style="font-size: 12px;" value="na">SELECCIONA DEDUCIBLE</option>
                                    <option style="font-size: 12px;" value="0">0%</option>
                                     <option style="font-size: 12px;" value="3">3%</option>
                                      <option style="font-size: 12px;" value="5">5%</option>
                                       <option style="font-size: 12px;" value="10">10%</option>

                                       <option style="font-size: 12px;" value="15">15%</option>
                                       <option style="font-size: 12px;" value="20">20%</option>
                                   
                                  </select>
                                   <div> 
                                   <center>
                                        <span id="error_deducible_opcion1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select style="text-align-last:center;" class="form-control" id="deducible_opcion2" name="deducible_opcion2"disabled>
                                        <option style="font-size: 12px;" value="na">SELECCIONA DEDUCIBLE</option>
                                       <option style="font-size: 12px;" value="0">0%</option>
                                     <option style="font-size: 12px;" value="3">3%</option>
                                      <option style="font-size: 12px;" value="5">5%</option>
                                       <option style="font-size: 12px;" value="10">10%</option>

                                       <option style="font-size: 12px;" value="15">15%</option>
                                       <option style="font-size: 12px;" value="20">20%</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_deducible_opcion2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select style="text-align-last:center;" class="form-control" id="deducible_opcion3" name="deducible_opcion3"disabled>
                                    <option style="font-size: 12px;" value="na">SELECCIONA DEDUCIBLE</option>
                                       <option style="font-size: 12px;" value="0">0%</option>
                                     <option style="font-size: 12px;" value="3">3%</option>
                                      <option style="font-size: 12px;" value="5">5%</option>
                                       <option style="font-size: 12px;" value="10">10%</option>

                                       <option style="font-size: 12px;" value="15">15%</option>
                                       <option style="font-size: 12px;" value="20">20%</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_deducible_opcion3" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="deducible_opcion4" name="deducible_opcion4"disabled>
                                   <option style="font-size: 12px;" value="na">SELECCIONA DEDUCIBLE</option>
                                       <option style="font-size: 12px;" value="0">0%</option>
                                     <option style="font-size: 12px;" value="3">3%</option>
                                      <option style="font-size: 12px;" value="5">5%</option>
                                       <option style="font-size: 12px;" value="10">10%</option>

                                       <option style="font-size: 12px;" value="15">15%</option>
                                       <option style="font-size: 12px;" value="20">20%</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_deducible_opcion4" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select style="text-align-last:center;" class="form-control" id="deducible_opcion5" name="deducible_opcion5"disabled>
                                     <option style="font-size: 12px;" value="na">SELECCIONA DEDUCIBLE</option>
                                       <option style="font-size: 12px;" value="0">0%</option>
                                     <option style="font-size: 12px;" value="3">3%</option>
                                      <option style="font-size: 12px;" value="5">5%</option>
                                       <option style="font-size: 12px;" value="10">10%</option>

                                       <option style="font-size: 12px;" value="15">15%</option>
                                       <option style="font-size: 12px;" value="20">20%</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_deducible_opcion5" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>
                              <tr>
<!---------------cristales-->

                              <tr>
                                <td style="font-size: 12px;font-weight: bold;">CRISTALES</td>
                                <td>
                                  <select class="form-control" id="cristales_opcion1_selec" name="cristales_opcion1_selec"disabled>
                                      
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   
                                  </select>
                                   <div> 
                                   <center>
                                        <span id="error_cristales_opcion1_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="cristales_opcion2_selec" name="cristales_opcion2_selec"disabled>
                                     

                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_cristales_opcion2_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="cristales_opcion3_selec" name="cristales_opcion3_selec"disabled>
                                    

                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_cristales_opcion3_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="cristales_opcion4_selec" name="cristales_opcion4_selec"disabled>
                                    

                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_cristales_opcion4_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="cristales_opcion5_selec" name="cristales_opcion5_selec"disabled>
                                    

                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_cristales_opcion5_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>
                              <tr>

                               <!----robo total-->
                                <td style="font-size: 12px;font-weight: bold;">ROBO TOTAL</td>
                                <td>
                                   <select class="form-control" id="robo_opcion1_selec" name="robo_opcion1_selec" disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="V.COMERCIAL">V.COMERCIAL</option>
                                    <option style="font-size: 12px;" value="V.CONVENIDO">V.CONVENIDO</option>
                                    <option style="font-size: 12px;" value="V.FACTURA">V.FACTURA</option>
                                   
                                  </select>
                                   <div> 
                                   <center>
                                        <span id="error_robo_opcion1_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="robo_opcion2_selec" name="robo_opcion2_selec" disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="V.COMERCIAL">V.COMERCIAL</option>
                                    <option style="font-size: 12px;" value="V.CONVENIDO">V.CONVENIDO</option>

                                    <option style="font-size: 12px;" value="V.FACTURA">V.FACTURA</option>
                                   
                                  </select>
                                   <div> 
                                   <center>
                                        <span id="error_robo_opcion2_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="robo_opcion3_selec" name="robo_opcion3_selec" disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="V.COMERCIAL">V.COMERCIAL</option>
                                    <option style="font-size: 12px;" value="V.CONVENIDO">V.CONVENIDO</option>

                                    <option style="font-size: 12px;" value="V.FACTURA">V.FACTURA</option>
                                   
                                  </select>
                                   <div> 
                                   <center>
                                        <span id="error_robo_opcion3_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="robo_opcion4_selec" name="robo_opcion4_selec" disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="V.COMERCIAL">V.COMERCIAL</option>
                                    <option style="font-size: 12px;" value="V.CONVENIDO">V.CONVENIDO</option>

                                    <option style="font-size: 12px;" value="V.FACTURA">V.FACTURA</option>
                                   
                                  </select>
                                   <div> 
                                   <center>
                                        <span id="error_robo_opcion4_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="robo_opcion5_selec" name="robo_opcion5_selec" disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="V.COMERCIAL">V.COMERCIAL</option>
                                    <option style="font-size: 12px;" value="V.CONVENIDO">V.CONVENIDO</option>

                                    <option style="font-size: 12px;" value="V.FACTURA">V.FACTURA</option>
                                   
                                  </select>
                                   <div> 
                                   <center>
                                        <span id="error_robo_opcion5_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>
                      <!--aqui importe factura DE ROBO-->
                              <tr>
                                <td style="font-size: 12px;font-weight: bold;">IMPORTE FACTURA</td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text"  name="robo_importe_factura_1" class="form-control" id="robo_importe_factura_1" disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_robo_importe_factura_1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text"  name="robo_importe_factura_2" class="form-control" id="robo_importe_factura_2"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_robo_importe_factura_2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text"  name="robo_importe_factura_3" class="form-control" id="robo_importe_factura_3"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_robo_importe_factura_3" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text"  name="robo_importe_factura_4" class="form-control" id="robo_importe_factura_4"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_robo_importe_factura_4" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text"  name="robo_importe_factura_5" class="form-control" id="robo_importe_factura_5"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_robo_importe_factura_5" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>


<!--deducible RT-->
   <tr>
                                <td style="font-size: 12px;font-weight: bold;">DEDUCIBLE RT</td>
                                <td>
                                  <select style="text-align-last: center;" class="form-control" id="deducible_rt1" name="deducible_rt1" disabled>
                                      <option style="font-size: 12px;" value="na">SELECCIONA DEDUCIBLE</option>
                                    <option style="font-size: 12px;" value="0">0%</option>
                                      <option style="font-size: 12px;" value="5">5%</option>
                                       <option style="font-size: 12px;" value="10">10%</option>

                                       <option style="font-size: 12px;" value="15">15%</option>
                                       <option style="font-size: 12px;" value="20">20%</option>
                                   
                                  </select>
                                   <div> 
                                   <center>
                                        <span id="error_deducible_rt1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select style="text-align-last: center;" class="form-control" id="deducible_rt2" name="deducible_rt2"disabled>
                                        <option style="font-size: 12px;" value="na">SELECCIONA DEDUCIBLE</option>
                                       <option style="font-size: 12px;" value="0">0%</option>
                                      <option style="font-size: 12px;" value="5">5%</option>
                                       <option style="font-size: 12px;" value="10">10%</option>

                                       <option style="font-size: 12px;" value="15">15%</option>
                                       <option style="font-size: 12px;" value="20">20%</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_deducible_rt2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select style="text-align-last: center;" class="form-control" id="deducible_rt3" name="deducible_rt3"disabled>
                                    <option style="font-size: 12px;" value="na">SELECCIONA DEDUCIBLE</option>
                                       <option style="font-size: 12px;" value="0">0%</option>
                                      <option style="font-size: 12px;" value="5">5%</option>
                                       <option style="font-size: 12px;" value="10">10%</option>

                                       <option style="font-size: 12px;" value="15">15%</option>
                                       <option style="font-size: 12px;" value="20">20%</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_deducible_rt3" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select style="text-align-last: center;" class="form-control" id="deducible_rt4" name="deducible_rt4"disabled>
                                   <option style="font-size: 12px;" value="na">SELECCIONA DEDUCIBLE</option>
                                       <option style="font-size: 12px;" value="0">0%</option>
                                      <option style="font-size: 12px;" value="5">5%</option>
                                       <option style="font-size: 12px;" value="10">10%</option>

                                       <option style="font-size: 12px;" value="15">15%</option>
                                       <option style="font-size: 12px;" value="20">20%</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_deducible_rt4" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select style="text-align-last: center;" class="form-control" id="deducible_rt5" name="deducible_rt5"disabled>
                                     <option style="font-size: 12px;" value="na">SELECCIONA DEDUCIBLE</option>
                                       <option style="font-size: 12px;" value="0">0%</option>
                                      <option style="font-size: 12px;" value="5">5%</option>
                                       <option style="font-size: 12px;" value="10">10%</option>

                                       <option style="font-size: 12px;" value="15">15%</option>
                                       <option style="font-size: 12px;" value="20">20%</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_deducible_rt5" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>
                              <tr>
                              
                              <!--rc daños a terceros-->
                        <tr>
                                <td style="font-size: 12px;font-weight: bold;">R.C. DAÑOS A TERCEROS</td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text"  name="daños_tercero_opcion_1" class="form-control" id="daños_tercero_opcion_1" disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_daños_tercero_opcion_1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="daños_tercero_opcion_2" class="form-control" id="daños_tercero_opcion_2"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_daños_tercero_opcion_2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="daños_tercero_opcion_3" class="form-control" id="daños_tercero_opcion_3"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_daños_tercero_opcion_3" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="daños_tercero_opcion_4" class="form-control" id="daños_tercero_opcion_4"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_daños_tercero_opcion_4" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="daños_tercero_opcion_5" class="form-control" id="daños_tercero_opcion_5"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_daños_tercero_opcion_5" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>
<!----deducible de rc-->
                               <tr>
                                <td style="font-size: 12px;font-weight: bold;">DEDUCIBLE DE RC</td>
                                <td>
                                   <div style=" overflow: auto;text-align:center;">
                                    <input type="number" min="0" max="100" name="deducible_de_rc_opcion1" id="deducible_de_rc_opcion1" class="form-control" style="width: 50%;float: left;margin-left:25%;" disabled>
                                    <label style="float: left;font-size: 20px;vertical-align: center;padding-top: 5px;">%</label>
                                 </div>
                                   <div> 
                                   <center>
                                        <span id="error_deducible_de_rc_opcion1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style=" overflow: auto;text-align:center;">
                                    <input type="number" min="0" max="100" name="deducible_de_rc_opcion2" id="deducible_de_rc_opcion2" class="form-control" style="width: 50%;float: left;margin-left:25%;" disabled>
                                    <label style="float: left;font-size: 20px;vertical-align: center;padding-top: 5px;">%</label>
                                 </div>
                                 <div> 
                                   <center>
                                        <span id="error_deducible_de_rc_opcion2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style=" overflow: auto;text-align:center;">
                                    <input type="number" min="0" max="100" name="deducible_de_rc_opcion3" id="deducible_de_rc_opcion3" class="form-control" style="width: 50%;float: left;margin-left:25%;" disabled>
                                    <label style="float: left;font-size: 20px;vertical-align: center;padding-top: 5px;">%</label>
                                 </div>
                                 <div> 
                                   <center>
                                        <span id="error_deducible_de_rc_opcion3" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style=" overflow: auto;text-align:center;">
                                    <input type="number" min="0" max="100" name="deducible_de_rc_opcion4" id="deducible_de_rc_opcion4" class="form-control" style="width: 50%;float: left;margin-left:25%;" disabled>
                                    <label style="float: left;font-size: 20px;vertical-align: center;padding-top: 5px;">%</label>
                                 </div>
                                 <div> 
                                   <center>
                                        <span id="error_deducible_de_rc_opcion4" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style=" overflow: auto;text-align:center;">
                                    <input type="number" min="0" max="100" name="deducible_de_rc_opcion5" id="deducible_de_rc_opcion5" class="form-control" style="width: 50%;float: left;margin-left:25%;" disabled>
                                    <label style="float: left;font-size: 20px;vertical-align: center;padding-top: 5px;">%</label>
                                 </div>
                                 <div> 
                                   <center>
                                        <span id="error_deducible_de_rc_opcion5" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>
   <!------------aqui debe ir rc fallecimiento--------->

                              <tr>
                                <td style="font-size: 12px;font-weight: bold;">R.C. FALLECIMIENTO</td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="fallecimiento_opcion_1" class="form-control" id="fallecimiento_opcion_1"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_fallecimiento_opcion_1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="fallecimiento_opcion_2" class="form-control" id="fallecimiento_opcion_2"disabled></div>
                                  </div>
                                  <div> 
                                   <center>
                                        <span id="error_fallecimiento_opcion_2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="fallecimiento_opcion_3" class="form-control" id="fallecimiento_opcion_3"disabled></div>
                                  </div>
                                  <div> 
                                   <center>
                                        <span id="error_fallecimiento_opcion_3" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="fallecimiento_opcion_4" class="form-control" id="fallecimiento_opcion_4"disabled></div>
                                  </div>
                                  <div> 
                                   <center>
                                        <span id="error_fallecimiento_opcion_4" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="fallecimiento_opcion_5" class="form-control" id="fallecimiento_opcion_5"disabled></div>
                                  </div>
                                  <div> 
                                   <center>
                                        <span id="error_fallecimiento_opcion_5" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>
                              
                              
<!----gastos medicos ocupantes-->

                              <tr>
                                <td style="font-size: 12px;font-weight: bold;">GASTOS MEDICOS OCUPANTES</td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="gastos_medicos_opcion_1" class="form-control" id="gastos_medicos_opcion_1"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_gastos_medicos_opcion_1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="gastos_medicos_opcion_2" class="form-control" id="gastos_medicos_opcion_2"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_gastos_medicos_opcion_2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="gastos_medicos_opcion_3" class="form-control" id="gastos_medicos_opcion_3"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_gastos_medicos_opcion_3" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="gastos_medicos_opcion_4" class="form-control" id="gastos_medicos_opcion_4"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_gastos_medicos_opcion_4" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="gastos_medicos_opcion_5" class="form-control" id="gastos_medicos_opcion_5"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_gastos_medicos_opcion_5" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>
<!--accidentes al condutor-->
<tr>
                                <td style="font-size: 12px;font-weight: bold;">ACCIDENTES AL CONDUCTOR</td>
                                <td>
                                    <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="accidente_conducir_opcion_1" class="form-control" id="accidente_conducir_opcion_1"disabled></div>
                                  </div>
                                    <div> 
                                   <center>
                                        <span id="error_accidente_conducir_opcion_1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                    <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="accidente_conducir_opcion_2" class="form-control" id="accidente_conducir_opcion_2"disabled></div>
                                  </div>
                                    <div> 
                                   <center>
                                        <span id="error_accidente_conducir_opcion_2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                    <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="accidente_conducir_opcion_3" class="form-control" id="accidente_conducir_opcion_3"disabled></div>
                                  </div>
                                    <div> 
                                   <center>
                                        <span id="error_accidente_conducir_opcion_3" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                    <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="accidente_conducir_opcion_4" class="form-control" id="accidente_conducir_opcion_4"disabled></div>
                                  </div>
                                    <div> 
                                   <center>
                                        <span id="error_accidente_conducir_opcion_4" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                    <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="accidente_conducir_opcion_5" class="form-control" id="accidente_conducir_opcion_5"disabled></div>
                                  </div>
                                    <div> 
                                   <center>
                                        <span id="error_accidente_conducir_opcion_5" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>

<!--proteccion_legal-->

                              <tr>
                                <td style="font-size: 12px;font-weight: bold;">PROTECCION LEGAL</td>
                                <td>
                                   <select class="form-control" id="proteccion_opcion1_selec" name="proteccion_opcion1_selec"disabled>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_proteccion_opcion1_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="proteccion_opcion2_selec" name="proteccion_opcion2_selec"disabled>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_proteccion_opcion2_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="proteccion_opcion3_selec" name="proteccion_opcion3_selec"disabled>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_proteccion_opcion3_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="proteccion_opcion4_selec" name="proteccion_opcion4_selec"disabled>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_proteccion_opcion4_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="proteccion_opcion5_selec" name="proteccion_opcion5_selec"disabled>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_proteccion_opcion5_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>
<!---asistencia vial-->

 <tr>
                                <td style="font-size: 12px;font-weight: bold;">ASISTENCIA VIAL</td>
                                <td>
                                   <select class="form-control" id="asistencia_vial_opcion1_selec" name="asistencia_vial_opcion1_selec"disabled>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   
                                  </select>
                                     <div> 
                                   <center>
                                        <span id="error_asistencia_vial_opcion1_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="asistencia_vial_opcion2_selec" name="asistencia_vial_opcion2_selec"disabled>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_asistencia_vial_opcion2_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="asistencia_vial_opcion3_selec" name="asistencia_vial_opcion3_selec"disabled>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_asistencia_vial_opcion3_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="asistencia_vial_opcion4_selec" name="asistencia_vial_opcion4_selec"disabled>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_asistencia_vial_opcion4_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                   <select class="form-control" id="asistencia_vial_opcion5_selec" name="asistencia_vial_opcion5_selec"disabled>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   
                                  </select>
                                  <div> 
                                   <center>
                                        <span id="error_asistencia_vial_opcion5_selec" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>
<!--daños por la carga-->
                               <tr>
                                <td style="font-size: 12px;font-weight: bold;">DAÑOS POR LA CARGA</td>
                                <td>
                                  <select class="form-control" id="daños_carga_opcion_selec_1" name="daños_carga_opcion_selec_1"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                     <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                       <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>

                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_daños_carga_opcion_selec_1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <select class="form-control" id="daños_carga_opcion_selec_2" name="daños_carga_opcion_selec_2"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>

                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_daños_carga_opcion_selec_2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <select class="form-control" id="daños_carga_opcion_selec_3" name="daños_carga_opcion_selec_3"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                    <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>
                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_daños_carga_opcion_selec_3" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>

                                </td>
                                <td>
                                  <select class="form-control" id="daños_carga_opcion_selec_4" name="daños_carga_opcion_selec_4"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                    <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>
                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_daños_carga_opcion_selec_4" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <select class="form-control" id="daños_carga_opcion_selec_5" name="daños_carga_opcion_selec_5"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                    <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>
                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_daños_carga_opcion_selec_5" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>

<!----------adaptaciones------------------->
                              
                              <tr>
                                <td style="font-size: 12px;font-weight: bold;">ADAPTACIONES, CONVERSIONES Y/O EQUIPO ESPECIAL</td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="adaptaciones_opcion_1" class="form-control" id="adaptaciones_opcion_1"disabled></div>
                                  </div>
                                  <div> 
                                   <center>
                                        <span id="error_adaptaciones_opcion_1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="adaptaciones_opcion_2" class="form-control" id="adaptaciones_opcion_2"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_adaptaciones_opcion_2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="adaptaciones_opcion_3" class="form-control" id="adaptaciones_opcion_3"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_adaptaciones_opcion_3" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="adaptaciones_opcion_4" class="form-control" id="adaptaciones_opcion_4"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_adaptaciones_opcion_4" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="adaptaciones_opcion_5" class="form-control" id="adaptaciones_opcion_5"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_adaptaciones_opcion_5" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>


                             <!-------------descripcion------------------->

                              <tr>
                                <td style="font-size: 12px;font-weight: bold;">DESCRIPCION</td>
                                <td colspan="5"><input class="form-control" type="text" name="descripcion_tabla" id="descripcion_tabla" disabled></td>
                                 
                              </tr>
      <!-----------------------Extension rc----------------->
                              <tr>
                                <td style="font-size: 12px;font-weight: bold;">EXTENSION DE RC</td>
                                <td>
                                  <select class="form-control" id="extension_rc_opcion1" name="extension_rc_opcion1" disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>
                                     
                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_extension_rc_opcion1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <select class="form-control" id="extension_rc_opcion2" name="extension_rc_opcion2"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                  <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>

                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_extension_rc_opcion2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <select class="form-control" id="extension_rc_opcion3" name="extension_rc_opcion3"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                    <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>

                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_extension_rc_opcion3" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>

                                </td>
                                <td>
                                  <select class="form-control" id="extension_rc_opcion4" name="extension_rc_opcion4"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                    <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>
                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_extension_rc_opcion4" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <select class="form-control" id="extension_rc_opcion5" name="extension_rc_opcion5"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                    <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>
                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_extension_rc_opcion5" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>

                              <!------------opcion 1 de cobertura------------------>

                              <tr>
                                <td style="font-size: 12px;font-weight: bold;">
                                
                                  <input type="text" name="cobertura_opcion_1" id="cobertura_opcion_1" class="form-control" disabled>
                                <div> 
                                   <center>
                                        <span id="error_cobertura_opcion_1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <select class="form-control" id="cobertura_opcion_1_select" name="cobertura_opcion_1_select" disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>
                                     
                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_cobertura_opcion_1_select" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <select class="form-control" id="cobertura_opcion_2_select" name="cobertura_opcion_2_select"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                  <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>

                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_cobertura_opcion_2_select" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <select class="form-control" id="cobertura_opcion_3_select" name="cobertura_opcion_3_select"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                    <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>

                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_cobertura_opcion_3_select" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>

                                </td>
                                <td>
                                  <select class="form-control" id="cobertura_opcion_4_select" name="cobertura_opcion_4_select"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                    <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>
                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_cobertura_opcion_4_select" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <select class="form-control" id="cobertura_opcion_5_select" name="cobertura_opcion_5_select"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                    <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>
                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_cobertura_opcion_5_select" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>


                              <!---------------opcion 2 de cobertura------------------->
                                <tr>
                                <td style="font-size: 12px;font-weight: bold;">
                                  <input type="text" name="cobertura_opcion_2" id="cobertura_opcion_2" class="form-control" disabled>
                                  <div> 
                                   <center>
                                        <span id="error_cobertura_opcion_2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <select class="form-control" id="cobertura_opcion_2_1_select" name="cobertura_opcion_2_1_select" disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                   <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>
                                     
                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_cobertura_opcion_2_1_select" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <select class="form-control" id="cobertura_opcion_2_2_select" name="cobertura_opcion_2_2_select"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                  <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>

                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_cobertura_opcion_2_2_select" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <select class="form-control" id="cobertura_opcion_2_3_select" name="cobertura_opcion_2_3_select"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                    <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>

                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_cobertura_opcion_2_3_select" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>

                                </td>
                                <td>
                                  <select class="form-control" id="cobertura_opcion_2_4_select" name="cobertura_opcion_2_4_select"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                    <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>
                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_cobertura_opcion_2_4_select" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <select class="form-control" id="cobertura_opcion_2_5_select" name="cobertura_opcion_2_5_select"disabled>
                                        <option style="font-size: 12px;" value="0">SELECCIONA TIPO COBERTURA</option>
                                    <option style="font-size: 12px;" value="AMPARADA">AMPARADA</option>
                                    <option style="font-size: 12px;" value="EXCLUIDA">EXCLUIDA</option>
                                   
                                  </select>
                                    <div> 
                                   <center>
                                        <span id="error_cobertura_opcion_2_5_select" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>  
                              </tr>

                             
                             

                              <tr>
                                <td colspan="6" style="text-align:center;font-weight: bold;">DESGLOSE DE PRIMA</td>
                              </tr>
                              <!-----------------forma de pago-------------->
                              <tr>
                                <td style="font-size: 12px;font-weight: bold;">FORMA DE PAGO</td>
                                
                                <td colspan="5">
                                  <select class="form-control" name="forma_de_pago" id="forma_de_pago" style="text-align-last:center;"disabled>
                                    <option value="0">SELECCIONA LA FORMA DE PAGO</option>
                                    <option value="ANUAL">ANUAL</option>
                                    <option value="SEMESTRAL">SEMESTRAL</option>
                                    <option value="TRIMESTRAL">TRIMESTRAL</option>
                                    <option value="MENSUAL">MENSUAL</option>
                                  </select>
                                   <div> 
                                   <center>
                                        <span id="error_forma_de_pago" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                              </tr>
                              <!-----------------------prima neta anual-------------------->
                              <tr>
                                  <td style="font-size: 12px;font-weight: bold;">PRIMA NETA ANUAL</td>
                                  <td>
                                    <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" name="cantidad_prima_neta_opcion1" class="form-control" readonly id="cantidad_prima_neta_opcion1" step="0.01" ></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_cantidad_prima_neta_opcion1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                    <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0"  step="0.01" name="cantidad_prima_neta_opcion2" class="form-control" readonly="" id="cantidad_prima_neta_opcion2"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_cantidad_prima_neta_opcion2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center> 
                                  </div>
                                </td>
                                <td>
                                    <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" step="0.01" name="cantidad_prima_neta_opcion3" class="form-control" readonly="" id="cantidad_prima_neta_opcion3"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_cantidad_prima_neta_opcion3" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                    <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" step="0.01" name="cantidad_prima_neta_opcion4" class="form-control" readonly="" id="cantidad_prima_neta_opcion4"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_cantidad_prima_neta_opcion4" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                    <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" min="0" step="0.01" name="cantidad_prima_neta_opcion5" class="form-control" readonly="" id="cantidad_prima_neta_opcion5"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_cantidad_prima_neta_opcion5" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>

                              </tr>
  <!--------------------------prima total anual------------------------>
                              <tr>
                                <td style="font-size: 12px;font-weight: bold;">PRIMA TOTAL ANUAL</td>
                                <td>
                                    <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text"  name="cantidad_total_anual_opcion_1" class="form-control" id="cantidad_total_anual_opcion_1"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_cantidad_total_anual_opcion_1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                    <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text"  name="cantidad_total_anual_opcion_2" class="form-control" id="cantidad_total_anual_opcion_2"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_cantidad_total_anual_opcion_2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                    <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text"  name="cantidad_total_anual_opcion_3" class="form-control" id="cantidad_total_anual_opcion_3"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_cantidad_total_anual_opcion_3" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                    <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text"  name="cantidad_total_anual_opcion_4" class="form-control" id="cantidad_total_anual_opcion_4"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_cantidad_total_anual_opcion_4" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                    <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text"   name="cantidad_total_anual_opcion_5" class="form-control" id="cantidad_total_anual_opcion_5"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_cantidad_total_anual_opcion_5" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                              </tr>
    <!----------------------primer pago--------------------------------------->
                              <tr>
                                <td style="font-size: 12px;font-weight: bold;">PRIMER PAGO</td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" name="primer_pago_opcion_1" class="form-control" id="primer_pago_opcion_1" disabled></div>
                                  </div>
                                  <div> 
                                   <center>
                                        <span id="error_primer_pago_opcion_1" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" name="primer_pago_opcion_2" class="form-control" id="primer_pago_opcion_2"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_primer_pago_opcion_2" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" name="primer_pago_opcion_3" class="form-control" id="primer_pago_opcion_3"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_primer_pago_opcion_3" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" name="primer_pago_opcion_4" class="form-control" id="primer_pago_opcion_4"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_primer_pago_opcion_4" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                                <td>
                                  <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" name="primer_pago_opcion_5" class="form-control" id="primer_pago_opcion_5"disabled></div>
                                  </div>
                                   <div> 
                                   <center>
                                        <span id="error_primer_pago_opcion_5" style="text-align:center;color:#b71c1c; font-weight: bold; display: none; padding-top: 10px;font-size: 14px;"></span>
                                      </center>
                                  </div>
                                </td>
                              </tr>
                              <!---------------subsecuentes---------------->
                              <tr>
                                <td></td>
                                <td></td>
                                <td  colspan="2" style="text-align:center;font-weight: bold;">SUBSECUENTES: <span id="cantidad_subsecuentes"></span></td>
                                <td></td>
                                <td></td>
                              </tr>
                              <tr>
                                <td></td>
                                <td>
                                   <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" name="subsecuente_opcion1" class="form-control" id="subsecuente_opcion1" readonly=""></div>
                                  </div>
                                </td>
                                <td>
                                   <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" name="subsecuente_opcion2" class="form-control" id="subsecuente_opcion2" readonly=""></div>
                                  </div>
                                </td>
                                <td>
                                   <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" name="subsecuente_opcion3" class="form-control" id="subsecuente_opcion3" readonly=""></div>
                                  </div>
                                </td>
                                <td>
                                   <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" name="subsecuente_opcion4" class="form-control" id="subsecuente_opcion4" readonly=""></div>
                                  </div>
                                </td>
                                <td>
                                   <div style="overflow: hidden;">
                                    <div style="display: inline-block;;"><span>$</span></div>
                                    <div style="display: inline-block;;width: 85%"><input style="width: 100%;" type="text" name="subsecuente_opcion5" class="form-control" id="subsecuente_opcion5" readonly=""></div>
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                          </thead>
                        </table>
                       
                      </div>

                      <div class="col-lg-12" style="text-align:center; margin-top: 2%;">
                        <button type="button" class=" guardar_formulario_cotizacion btn btn-sm btn-primary">GUARDAR</button>
                      </div>
            




                  </div>

                    
                  </form>

 </div>


  <div id="contenedor_genetal_tabla_contizaciones_renovaciones" class="col-lg-12 table-responsive" style="display: none;">
                          <!---  <center><legend>LISTA DE COTIZACIONES</legend></center>
                          -->
                          <div id="contenedor_tabla_nuevas_cotizaciones" class="col-lg-12" style="display: none;">

                            
                                <center> <legend style="margin-bottom: 2%;">NUEVAS COTIZACIONES</legend></center>
                            <label>FECHA INICIAL</label>
                            <input type="date" name="fecha_inicial_nueva" id="fecha_inicial_nueva">
                            <label>FECHA FINAL</label>
                            <input type="date" name="fecha_final_nueva" id="fecha_final_nueva">
                            <table id="tabla_lista_cotizaciones" class="table table-hover  table-condensed">
                              <thead>
                                <tr>
                                 <th class="select-filter" style="font-size: 12px;">REALIZO</th>
                                  <th style="font-size: 12px;">FECHA ALTA</th>  
                                  <th style="font-size: 12px;">HORA SOLICITADA</th> 
                                  <th style="font-size: 12px;">CONTACTO</th>
                                  <th class="select-filter"  style="font-size: 12px;">TIPO CONTACTO</th>
                                   <th style="font-size: 12px;">PROSPECTO</th>
                                  <th style="font-size: 12px;">DESCRIPCION VEHICULO</th>
                                 <th style="font-size: 12px;">CANTIDAD OPCIONES COTIZADAS</th>
                                  <th style="font-size: 12px;">ASEGURADORA (Objetivo minimo concretar)</th>
                                  <th style="font-size: 12px;">PAQUETE SOLICITADO</th>
                                  <th style="font-size: 12px;">FORMA DE PAGO</th>
                                  <th style="font-size: 12px;">PRIMA NETA ANUAL</th>
                                  <th style="font-size: 12px;">PRIMA TOTAL ANUAL</th>
                                 <th style="font-size: 12px;">HORA DE ENVIO</th>
                                  <th style="font-size: 12px;">TIEMPO DE RESPUESTA (minutos)</th>
                                  <th class="select-filter"  style="font-size: 12px;">ESTATUS DE COTIZACION</th>
                                  <th style="font-size: 12px;">PDF</th>
                                  <th  style="font-size: 12px;;">CONCRETAR</th>
                                  <th style="font-size: 12px;">ASEGURADORA CONCRETADA</th>
                                  <th style="font-size: 12px;">PRIMA NETA ANUAL CONCRETADA</th>
                                  <th style="font-size: 12px;">PRIMA TOTAL ANUAL CONCRETADA</th>
                                  <th style="font-size: 12px;">NUMERO POLIZA</th>
                                  <th style="font-size: 12px;">INICIO VIGENCIA</th>
                                  <th style="font-size: 12px;">PRIMER PAGO</th>
                                   <th style="font-size: 12px;">MOTIVOS</th>
                                  
                                </tr>
                               
                              </thead>
                              <tbody>
                                <tr>
                                  
                                 <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                <td style="font-size: 10px;"></td>
                                 <td style="font-size: 10px;"></td>
                                </tr>
                              </tbody>
                              <tfoot>
                                <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                           
                           <div class="col-lg-12" id="contenedor_tabla_renovaciones" style="display: none;">
                                   <center> <legend style="margin-bottom: 2%;">RENOVACIONES</legend></center>
                            <label>FECHA INICIAL</label>
                            <input type="date" name="fecha_inicial_renovacion" id="fecha_inicial_renovacion">
                            <label>FECHA FINAL</label>
                            <input type="date" name="fecha_final_renovacion" id="fecha_final_renovacion">
                            <table id="tabla_lista_renovaciones" class="table table-hover  table-condensed">
                              <thead>
                                <tr>
                                   <th class="select-filter" style="font-size: 12px;">REALIZO</th>
                                  <th style="font-size: 12px;">FECHA ALTA</th>  
                                  <th style="font-size: 12px;">FECHA VENCIMIENTO</th> 
                                  <th style="font-size: 12px;">ASEGURADORA ACTUAL</th>
                                  <th style="font-size: 12px;">POLIZA A RENOVAR</th>  
                                  <th style="font-size: 12px;">CONTACTO</th>
                                  <th class="select-filter"  style="font-size: 12px;">TIPO CONTACTO</th>
                                  <th style="font-size: 12px;">PROSPECTO</th>
                                  <th style="font-size: 12px;">DESCRIPCION VEHICULO</th>
                                  <th style="font-size: 12px;">CANTIDAD OPCIONES COTIZADAS</th>
                                  <th style="font-size: 12px;">ASEGURADORA (Objetivo minimo concretar)</th>
                                  <th style="font-size: 12px;">PAQUETE SOLICITADO</th>
                                  <th style="font-size: 12px;">FORMA DE PAGO</th>
                                  <th style="font-size: 12px;">PRIMA NETA ANUAL</th>
                                  <th style="font-size: 12px;">PRIMA TOTAL ANUAL</th>
                                  <th style="font-size: 12px;">FECHA DE ENVIO COTIZACION</th>
                                  <th style="font-size: 12px;">ANTICIPO RENOVACION (dias)</th>
                                  <th class="select-filter"  style="font-size: 12px;">ESTATUS DE COTIZACION</th>
                                  <th style="font-size: 12px;">PDF</th>
                                  <th  style="font-size: 12px;">CONCRETAR</th>
                                  <th style="font-size: 12px;">ASEGURADORA CONCRETADA</th>
                                  <th style="font-size: 12px;">PRIMA NETA ANUAL CONCRETADA</th>
                                  <th style="font-size: 12px;">PRIMA TOTAL ANUAL CONCRETADA</th>
                                  <th style="font-size: 12px;">NUMERO POLIZA</th>
                                  <th style="font-size: 12px;">INICIO VIGENCIA</th>
                                  <th style="font-size: 12px;">PRIMER PAGO</th>
                                  <th style="font-size: 12px;">FECHA ENVIO POLIZA</th>
                                  <th style="font-size: 12px;">MOTIVOS</th>
                                  
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                </tr>
                              </tbody>
                              <tfoot>
                                <tr>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                </tr>
                              </tfoot>
                            </table>
                           </div>

                           
                             
                           </div>
