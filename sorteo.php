<!DOCTYPE html>
<?php

session_start();

include 'FuncionesPHP/funciones.php';
include 'FuncionesPHP/consultas.php';


//error_reporting(E_ERROR | E_PARSE);

$conn = conectarse();

?>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Brother's Cup PHP</title>
         <script src="Scripts/slotmachine.min.js"></script>
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="Estilos/styles.css" type="text/css" media="screen" />
       <link rel="stylesheet" href="Estilos/jquery.slotmachine.css" type="text/css" media="screen" />
   <link rel="stylesheet" href="Estilos/estilos.css" type="text/css" media="screen" />
  
   
   <!-- Fuente: https://github.com/josex2r/jQuery-SlotMachine -->
   
    </head>
    <body style="background: transparent;">
    <center>

 <div id="randomize">
      <div>
        <h1>¡Sorteo!</h1>

        <div class="row">
          <div class="col-sm-4">
            <div>
              <div id="machine1" class="randomizeMachine">
                  <?php getEquiposSorteo($conn); ?>
              </div>
            </div>
            <div id="machine1Result" class="col-xs-4 machineResult"></div>
          </div>

          <div class="col-sm-4">
            <div>
              <div id="machine2" class="randomizeMachine">
                  <?php getEquiposSorteo($conn); ?>
              </div>
            </div>
            <div id="machine2Result" class="col-xs-4 machineResult"></div>
          </div>

          <div class="col-sm-4">
            <div>
              <div id="machine3" class="randomizeMachine">
                  <?php getEquiposSorteo($conn); ?>
              </div>
            </div>
            <div id="machine3Result" class="col-xs-4 machineResult"></div>
          </div>
        
          <div class="btn-group btn-group-justified" role="group">
            <button id="randomizeButton" type="button" class="button buttonBlue">SORTEAR</button>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-10 offset-sm-1">
            <pre><code id="codeBlock2"></code></pre>
          </div>
        </div>
      </div>
    </div>
    <script id="codeScript2">
const btn = document.querySelector('#randomizeButton');
const results = {
  machine1: document.querySelector('#machine1Result'),
  machine2: document.querySelector('#machine2Result'),
  machine3: document.querySelector('#machine3Result')
};
const el1 = document.querySelector('#machine1');
const el2 = document.querySelector('#machine2');
const el3 = document.querySelector('#machine3');
const machine1 = new SlotMachine(el1, { active: 0 });
const machine2 = new SlotMachine(el2, { active: 1 });
const machine3 = new SlotMachine(el3, { active: 2 });

function onComplete(active){
    ind = this.active;
  //results[this.element.id].innerText = ind;
}

btn.addEventListener('click', () => {
  machine1.shuffle(5, onComplete);
  setTimeout(() => machine2.shuffle(5, onComplete), 500);
  setTimeout(() => machine3.shuffle(5, onComplete), 1000);
});
    </script>
    
    </center>
    </body>
</html>