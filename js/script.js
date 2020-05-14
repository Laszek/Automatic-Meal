function redirect(){
    document.location.href = "index.php?kat="+this.getAttribute("id");
}

window.onload = function() {
    var nord_btn = document.querySelector("#new_order");
    nord_btn.addEventListener("click", redirect);
    var ords_btn = document.querySelector("#orders");
    ords_btn.addEventListener("click", redirect);
    var menu_btn = document.querySelector("#menu");
    menu_btn.addEventListener("click", redirect);
    var wkrs_btn = document.querySelector("#workers");
    wkrs_btn.addEventListener("click", redirect);
    
};
