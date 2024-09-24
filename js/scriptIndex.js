function toggleMenu() {
    var menu = document.getElementById("menu");
    var body = document.body; // Récupère le corps du document
    menu.classList.toggle("open");
    body.classList.toggle("blur"); // Ajoute ou enlève le flou
}
