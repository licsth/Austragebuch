function change(){
    //Checkbox im Austragebuch verarbeiten
    if(!document.getElementById('alleweg').classList.contains('checked')){
        window.location = 'austragebuch.php?show=away';
    } else{
        window.location = 'austragebuch.php?show=all';
    }
}