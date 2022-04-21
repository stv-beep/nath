function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
        console.log('geolocation not supported')
    }
}

function showPosition(position) {
    const coord = position.coords.latitude +" "+ position.coords.longitude;
    const xy = document.getElementsByClassName('xy');
    //provisional solution to save the coords, in each input
    for (var i=0;i<xy.length;i++){
        xy[i].value = coord;
    }
    
    return coord;
}