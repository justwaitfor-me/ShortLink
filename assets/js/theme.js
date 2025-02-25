document.getElementById('themeSwitcher').addEventListener('click', function () {
    document.body.classList.toggle('dark-mode');
});

var themeSwitcher = document.getElementById('themeSwitcher');
var header = document.getElementById('navbar');
themeSwitcher.addEventListener('click', function () {
    if (document.body.classList.contains('dark-mode')) {
        themeSwitcher.innerHTML = '<i class="bi bi-sun-fill"></i>';
        header.classList.add('bg-dark');
        header.classList.add('navbar-dark');
        header.classList.remove('bg-light'); 
        header.classList.remove('navbar-light');
        header.classList.remove('text-dark');
        header.classList.add('text-white');
    } else {
        themeSwitcher.innerHTML = '<i class="bi bi-moon-fill"></i>';
        header.classList.add('bg-light');
        header.classList.add('navbar-light');
        header.classList.remove('bg-dark');
        header.classList.remove('navbar-dark');
        header.classList.add('text-dark');
        header.classList.remove('text-white');
    }
});