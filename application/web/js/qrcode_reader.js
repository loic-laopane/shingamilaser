// crée le menu déroulant de choix de la caméra
var label = document.createElement('label');
label.setAttribute('for', 'videoInputLst');
label.innerHTML = 'Scan QRCode';
document.getElementById('cameraChoose').appendChild(label);

var s = document.createElement('select');
s.setAttribute('class', 'form-control');
s.id = 'videoInputLst';
document.getElementById('cameraChoose').appendChild(s);

s.addEventListener('change', function(){
    scanner.VideoInputChange(this.options[this.selectedIndex].value);
}, false);

navigator.mediaDevices.enumerateDevices().
then(function(devices){
    devices.forEach(function(device){
        var s = document.getElementById('videoInputLst')
        if(device.kind == 'videoinput'){
            var o = document.createElement('option');
            o.value = device.deviceId;
            o.text = device.label || 'Caméra '+(s.length + 1);
            s.appendChild(o);
        }
    });
})
    .catch(function(err){
        console.log(err.name + ": " + error.message);
    });

// callback scanner
function retour(qrData){
    var check = document.getElementById('autoadd');
    document.getElementById('ScanQRResult').innerHTML = '';
    scanner = 0;
    if(check.checked)
    {
        //alert(check.checked);
        autoAdd(qrData);
    }
    else {
        document.getElementById('numero').value = qrData;
        $('#form_search').submit();
    }

    //document.getElementById('ScanQRResult').innerHTML = 'Résultat du scan : '+qrData;

}

function autoAdd(qrData)
{
    var uri = document.getElementById('autoadd').getAttribute('data-href');
    $.ajax({
        url: uri,
        method: 'POST',
        dataType: 'json',
        data: 'game_id={{ game.id }}&qrData='+qrData,
        success: function(response) {
            document.getElementById('numero').value = qrData;
            if(response.status)
            {
                //Action si ok
            }
            else {
                //action si nok
            }
            window.location.reload();
        },
        error: function(xhr, status, error)
        {
            //action si error xhr
        }
    });
}


function retourError(){
    document.getElementById('ScanQRResult').innerHTML = 'Aucun QR trouvé';
    scanner.Stop();
    scanner = 0;
}

// init scanner
var scanner = 0;
document.getElementById('ScanQRStartStop').addEventListener('click', function(ev){
    if(scanner){
        document.getElementById('ScanQRResult').innerHTML = '';
        scanner.Stop();
        scanner = 0;

    }else{
        document.getElementById('ScanQRResult').innerHTML = 'Scan en cours...';
        scanner = new jsQRScan({
            'id': 'camera',
            'width': 320,
            'height': 240,
            'streamId': document.getElementById('videoInputLst').options[document.getElementById('videoInputLst').selectedIndex].value,
            'callbackSuccess': retour,
            'callbackEnd': retourError
        });
    }
}, false);