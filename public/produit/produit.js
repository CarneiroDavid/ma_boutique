var btnShow = document.getElementById('produit_showForm');
btnShow.onclick =(evt)=>{
    const div = document.getElementById('form');
    if(div.hasAttribute('hidden')){
        div.removeAttribute('hidden');
    }else{
        div.setAttribute('hidden', true);
    }
}