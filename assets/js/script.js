$(document).ready(function () {
  $("#search-input").on("input", function () {
    var searchQuery = $(this).val().toLowerCase();
    $(".accordion").each(function () {
      var found = false;
      $(this)
        .find(".product")
        .each(function () {
          var productName = $(this).find("h3").text().toLowerCase();

          if (productName.includes(searchQuery)) {
            $(this).show(); 
            found = true;
          } else {
            $(this).hide();
          }
        });

      if (found) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
  });
});

function toggleAccordion(clickedHeader) {
  const allContent = document.querySelectorAll(".accordion-content");
  const allHeaders = document.querySelectorAll(".accordion-header span");

  allContent.forEach((content) => {
    if (content !== clickedHeader.nextElementSibling) {
      content.classList.remove("show");
    }
  });

  allHeaders.forEach((span) => {
    if (span !== clickedHeader.querySelector("span")) {
      span.textContent = "+";
    }
  });

  const content = clickedHeader.nextElementSibling;
  const span = clickedHeader.querySelector("span");
  content.classList.toggle("show");

  // Das Symbol wechseln
  if (span.textContent === "+") {
    span.textContent = "-";
  } else {
    span.textContent = "+";
  }
}

function toggleMenu() {
  var menuPopup = document.getElementById("menu-popup");
  if (menuPopup.style.display === "block") {
    menuPopup.style.display = "none";
  } else {
    menuPopup.style.display = "block";
  }
}

$(document).ready(function(){
  setTimeout(function(){
      $('.success-message').fadeOut('slow');
  }, 3000);
});

$(document).ready(function(){
  setTimeout(function(){
      $('.error-message').fadeOut('slow');
  }, 3000);
});

function openEditForm(product) {
  // Alle benötigten Elemente abrufen
  const productIdField = document.getElementById("edit_product_id");
  const nameField = document.getElementById("edit_name");
  const preisField = document.getElementById("edit_preis");
  const beschreibungField = document.getElementById("edit_beschreibung");
  const kategorieField = document.getElementById("edit_kategorie");
  const allergienContainer = document.getElementById("edit_allergien");
  const editContainer = document.getElementById("editContainer");

  // Prüfen, ob alle Elemente vorhanden sind
  if (!productIdField || !nameField || !preisField || !beschreibungField || !kategorieField || !allergienContainer || !editContainer) {
    console.error("Ein oder mehrere Elemente des Bearbeitungsformulars fehlen im DOM.");
    return;
  }

  // Felder mit den Produktdaten befüllen
  productIdField.value = product.id;
  nameField.value = product.name;
  preisField.value = product.preis;
  beschreibungField.value = product.produktbeschreibung || "";
  kategorieField.value = product.kategorie;

  // Allergien: Vorherige Einträge löschen und Checkboxen neu erstellen
  allergienContainer.innerHTML = "";
  const allergien = product.allergien ? product.allergien.split(",") : [];
  const allergieWerte = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "12", "14"];
  
  allergieWerte.forEach(value => {
    const label = document.createElement("label");
    label.style.marginRight = "8px";
    const checkbox = document.createElement("input");
    checkbox.type = "checkbox";
    checkbox.name = "allergien[]";
    checkbox.value = value;
    if (allergien.includes(value)) {
      checkbox.checked = true;
    }
    label.appendChild(checkbox);
    label.appendChild(document.createTextNode(" " + value));
    allergienContainer.appendChild(label);
  });

  // Das Bearbeitungsformular anzeigen
  editContainer.style.display = "block";
}

function closeEditForm() {
  const editContainer = document.getElementById("editContainer");
  if (editContainer) {
    editContainer.style.display = "none";
  }
}


function confirmDelete() {
  return confirm("Bist du sicher, dass du dieses Produkt löschen möchtest?");
}


function setKategorie() {
  const selectedKategorie = document.getElementById('kategorieDropdown').value;
  document.getElementById('kategorieFeld').value = selectedKategorie;
}