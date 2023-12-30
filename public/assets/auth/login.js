var cta = document.querySelector(".cta");
var check = 0;

cta.addEventListener('click', function(e){
    var text = e.target.nextElementSibling;
    var loginText = e.target.parentElement;
    text.classList.toggle('show-hide');
    loginText.classList.toggle('expand');
    if(check == 0)
    {
        cta.innerHTML = `<span >▲</span>`;
        check = 1;
    }
    else
    {
        cta.innerHTML = `<span >▼</span>`;
        check = 0;
    }
})
