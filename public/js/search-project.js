// https://stackoverflow.com/questions/1909441/how-to-delay-the-keyup-handler-until-the-user-stops-typing

// fonction pour gérer le délais sur l'évènement KEYUP de l'input search-project
function delay(fn, ms) {
    let timer = 0;
    return function(...args) {
        clearTimeout(timer);
        timer = setTimeout(fn.bind(this, ...args), ms || 0);
    }
}

// évènement sur l'input search-project
$( document ).ready(function() {
    $('#search-project').keyup(delay(function(){
        if (this.value != "") {
            console.log(this.value)
        }
    },1000));
});