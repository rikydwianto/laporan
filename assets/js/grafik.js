
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
