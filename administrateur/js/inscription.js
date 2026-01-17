function nextStep(form){
    const f = document.getElementById(form+"Form");
    const step1 = f.querySelector('.step1');
    const step2 = f.querySelector('.step2');
    step1.classList.remove('active'); step1.classList.add('hide');
    step2.classList.remove('hide'); step2.classList.add('active');
    document.getElementById("progress-"+form).style.width="100%";
}
function prevStep(form){
    const f = document.getElementById(form+"Form");
    const step1 = f.querySelector('.step1');
    const step2 = f.querySelector('.step2');
    step2.classList.remove('active'); step2.classList.add('hide');
    step1.classList.remove('hide'); step1.classList.add('active');
    document.getElementById("progress-"+form).style.width="50%";
}