<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAMBAR</title>
    <style>
    
    body{

    }
    .slidecontainer {
  width: 100%;
}

.slider {
  -webkit-appearance: none;
  width: 100%;
  height: 25px;
  background: #d3d3d3;
  outline: none;
  opacity: 0.7;
  -webkit-transition: .2s;
  transition: opacity .2s;
}

.slider:hover {
  opacity: 1;
}

.slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 25px;
  height: 25px;
  background: #04AA6D;
  cursor: pointer;
}

.slider::-moz-range-thumb {
  width: 25px;
  height: 25px;
  background: #04AA6D;
  cursor: pointer;
}
.tengah{
    width: 100%;
height: 100%;
margin-left: auto;
margin-right: auto;

}
#img {  
margin-top:0;
}  

    </style>
</head>
<body>
<div class='tengah'>

<center><img src="<?=$_GET['gambar']?>" id='img' alt=""><br/></center>
</div>
<div class="slidecontainer">


<h3>Rotasi Gambar</h3>
  <input type="range" min="1" max="4" value="0" class="slider" id="myRange">
  <p>Value: <span id="demo"></span></p>
</div>


<script>
var slider = document.getElementById("myRange");
var output = document.getElementById("demo");
output.innerHTML = slider.value;

slider.onchange = function() {
    var isi = this.value;
    var rotasi = 0;
    if(isi == 1){
        rotasi = 0;
    } 
    else if(isi == 2)
    {
        rotasi = 90;
    }
    else if(isi == 3)
    {
        rotasi = 270;
    }
    else if(isi == 4)
    {
        rotasi = 360;
    }
   
    document.querySelector("#img").style.transform = `rotate(${rotasi}deg)`;
    document.querySelector("#img").style.marginTop = "0px";
    
}
</script>

</html>