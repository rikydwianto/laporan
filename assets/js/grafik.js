
var sub_array = [];
var super_array = [];
for (var i = 1; i <= 3; i++) {
    sub_array.push(i);
    super_array.push(sub_array.slice(0));
}

var tgl = [];
var member = [];
var client = [];
var bayar = [];
var persen = [];
var dtd = [];
$.getJSON( url_link + "api/grafik.php?cab="+idcab, function( data ) {
    var items = [];
    
    $.each( data, function( i, field ) {
        tgl.push(data[i]['tgl_grafik']);
        member.push(data[i]['member']);
        client.push(data[i]['client']);
        bayar.push(data[i]['bayar']);
        persen.push(data[i]['persen']);
        dtd.push(data[i]['dtd']);
       

      });
    var xValues = [100,200,300,400,500,600,700,800,900,1000];

    new Chart("myChart", {
    type: "line",
    data: {
        labels: tgl,
        datasets: [
            { 
        data: persen,
        borderColor: "red",
        fill: false,
        label: "PERSENTASI BAYAR"
        },
            { 
        data: member,
        borderColor: "blue",
        fill: false,
        label: "ANGGOTA"
        },
         
            { 
        data: client,
        borderColor: "yellow",
        fill: false,
        label: "CLIENT"
        },
            { 
        data: bayar,
        borderColor: "green",
        fill: false,
        label: "ANGSURAN MASUK"
        }   ,
        { 
            data: dtd,
            borderColor: "black",
            fill: false,
            label: "CENTER DOOR TO DOOR"
            },
    
    ]
    },
    options: {
        legend: {display: true}
    }
    });
  });



  
  var kode = [];
  var total = [];

  $.getJSON( url_link + "api/grafik_donat.php?cab="+idcab, function( data ) {
    var items = [];
    
    $.each( data, function( i, field ) {
       kode.push(data[i]['kode']);
       total.push(data[i]['total']);
      // alert(data[i]['kode'])
      });


      var oilCanvas = document.getElementById("donat");

        Chart.defaults.global.defaultFontFamily = "Lato";
        Chart.defaults.global.defaultFontSize = 18;
        
        var oilData = {
            labels:kode,
            datasets: [
                {
                    data: total,
                    backgroundColor: [
                        // 'rgb(255, 99, 132)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 205, 86)',
                        'rgb(201, 203, 207)',
                        'rgb(54, 162, 235)',
                        'rgb(154, 62, 235)',
                        'rgb(14, 62, 25)'
                      ],
                    
                }]
        };
        
        var pieChart = new Chart(oilCanvas, {
            type: 'doughnut',
            data: oilData
        });

       // alert(kode);
  });

  


  
  const total_par = [];
  const total_os = [];
  const label = [];

function grafi_par(){
    
}