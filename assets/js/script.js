//script.js


//Das lass ich mal drin
/*Service Worker for PWA
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/bss_test/PWA/sw.js').then(function(registration) {
    console.log('Service Worker registered with scope:', registration.scope);
  }).catch(function(error) {
    console.log('Service Worker registration failed:', error);
  });
}*/




//Neuer code für Softdrinks
// Funktion zum Anzeigen der Bestätigung und Hinzufügen des Produkts bei Bestätigung
function showConfirmationModal(callback) {
    const modal = document.getElementById('confirmationModal');
modal.style.display = 'flex';

document.getElementById('confirmBtn').onclick = function () {
    modal.style.display = 'none';
    callback(true);
};

document.getElementById('cancelBtn').onclick = function () {
    modal.style.display = 'none';
    callback(false);
};
}

// Funktion zum Anzeigen des Bestätigungsdialogs und Hinzufügen des Softdrinks
function selectSoftdrinkVariant(select) {
const productData = select.value ? JSON.parse(select.value) : null;
if (productData) {
    showConfirmationModal((confirmed) => {
        if (confirmed) {
            addToCart(productData);
            showConfirmationMessage("Der Softdrink wurde dem Warenkorb hinzugefügt");
        }
    });
}
}

// Funktion zur Anzeige der Bestätigungsnachricht
function showConfirmationMessage(message) {
const messageBox = document.createElement("div");
messageBox.classList.add("confirmation-message");
messageBox.innerHTML = `${message} <span class="close-btn">×</span>`;

document.body.appendChild(messageBox);

messageBox.querySelector(".close-btn").addEventListener("click", () => {
    messageBox.remove();
});

setTimeout(() => {
    messageBox.remove();
}, 2000);
}







$(document).ready(function() {
// Event-Listener für die Eingabe im Suchfeld
$('#search-input').on('input', function() {
var searchQuery = $(this).val().toLowerCase();

// Schleife durch jedes Accordion und überprüfe, ob eines der Produkte den Suchbegriff im Titel enthält
$('.accordion').each(function() {
    var found = false;  // Flag, um zu überprüfen, ob mindestens ein Produkt gefunden wurde

    // Schleife durch die Produkte innerhalb des Accordions
    $(this).find('.product').each(function() {
        var productName = $(this).find('h3').text().toLowerCase();

        // Wenn der Produktname den Suchbegriff enthält, zeige das Produkt an
        if (productName.includes(searchQuery)) {
            $(this).show();  // Zeige das Produkt an
            found = true;    // Setze das Flag auf true
        } else {
            $(this).hide();  // Verstecke das Produkt
        }
    });

    // Wenn kein Produkt innerhalb des Accordions den Suchbegriff enthält, verstecke das gesamte Accordion
    if (found) {
        $(this).show();  // Zeige das Accordion, da mindestens ein passendes Produkt gefunden wurde
    } else {
        $(this).hide();  // Verstecke das Accordion, wenn kein passendes Produkt gefunden wurde
    }
});
});
});


let cart = [];

function toggleAllergyMap() {
    const modal = document.getElementById("allergyModal");
    if (modal.style.display === "block") {
        modal.style.display = "none"; // Schließen
    } else {
        modal.style.display = "block"; // Öffnen
    }
}

function showAllergyInfo() {
    const allergyMap = {
        "a": "Enthält glutenhaltige Weizenerzeugnisse",
        "b": "Enthält Krebstiererzeugnisse",
        "c": "Enthält Eierzeugnisse",
        "d": "Enthält Fischerzeugnisse",
        "e": "Enthält Erdnusserzeugnisse",
        "f": "Enthält Sojabohnenerzeugnisse",
        "g": "Enthält Milcherzeugnisse (laktosehaltig)",
        "h": "Enthält Schalenfrüchte/Nusserzeugnisse",
        "i": "Enthält Sellerieerzeugnisse",
        "j": "Enthält Senferzeugnisse",
        "k": "Enthält Sesamsamenerzeugnisse",
        "l": "Enthält Schwefeldioxid/Sulfite",
        "m": "Enthält Lupinenerzeugnisse",
        "n": "Enthält Weichtiererzeugnisse",
        "1": "Mit Farbstoff",
        "2": "Mit Konservierungsstoff",
        "3": "Mit Antioxidationsmittel",
        "4": "Mit Geschmacksverstärker",
        "5": "Geschwefelt",
        "6": "Mit Süßungsmittel",
        "7": "Enthält eine Phenylalaninquelle",
        "8": "Phosphathaltig",
        "9": "Chininhaltig",
        "10": "Koffeinhaltig"
    };

    let info = '';
    for (const [key, value] of Object.entries(allergyMap)) {
        info += `<strong>${key}:</strong> ${value}<br>`;
    }
    
    document.getElementById('allergy-info').innerHTML = info;
    document.getElementById('allergy-modal').style.display = 'block';
}

function closeModal() {
    document.getElementById('allergy-modal').style.display = 'none';
}

// Schließe das Modal, wenn der Benutzer außerhalb des Modals klickt
window.onclick = function(event) {
    const modal = document.getElementById('allergy-modal');
    if (event.target === modal) {
        modal.style.display = "none";
    }
}




//aus kunde.php
function toggleAccordion(clickedHeader) {
        // Alle Accordion-Inhalte holen
        const allContent = document.querySelectorAll('.accordion-content');
        const allHeaders = document.querySelectorAll('.accordion-header span');

        // Accordion-Inhalte und Symbole durchlaufen
        allContent.forEach(content => {
            if (content !== clickedHeader.nextElementSibling) {
                content.classList.remove('show'); // Schließen
            }
        });

        allHeaders.forEach(span => {
            if (span !== clickedHeader.querySelector('span')) {
                span.textContent = '+'; // Standard-Symbol
            }
        });

        // Das aktuelle Accordion ein- oder ausklappen
        const content = clickedHeader.nextElementSibling;
        const span = clickedHeader.querySelector('span');
        content.classList.toggle('show');

        // Das Symbol wechseln
        if (span.textContent === '+') {
            span.textContent = '-';
        } else {
            span.textContent = '+';
        }
    }
    
    
//test accordion
function toggleAccordion(header) {
    const content = header.nextElementSibling;
    content.classList.toggle('show');
}
// test accordion

function toggleCart() {
    const cart = document.getElementById('cart');
    cart.classList.toggle('active');
}

/*NEU FÜR ZUTATEN*/

function addToCart(product) {
console.log('Product Object:', product);
console.log(`Adding product ${product.id} to cart`);
console.log(`Produktkategorie: ${product.kategorie}`);

if (!product.id) {
console.error('Product ID is undefined!');
return;
}

if (product.kategorie === 'Pizza' && !product.sizeId) { 
console.log('Eine Pizza ohne Größe wurde ausgewählt. Öffne das Größen-Popup.');
showSizeSelectionPopup(product);
return;
}

const getZutaten = $.ajax({
url: '../getDateien/get_zutaten.php',
type: 'POST',
data: { produkt_id: product.id },
dataType: 'json'
});

const getSossen = $.ajax({
url: '../getDateien/get_sossen.php',
type: 'POST',
data: { produkt_id: product.id },
dataType: 'json'
});

$.when(getZutaten, getSossen).done(function(zutatenResponse, sossenResponse) {
const zutaten = Array.isArray(zutatenResponse[0]) ? zutatenResponse[0] : [];
const sossen = Array.isArray(sossenResponse[0]) ? sossenResponse[0] : [];

if (zutaten.error) {
    console.error('Fehler bei der Abfrage der Zutaten:', zutaten.error);
} 
if (sossen.error) {
    console.error('Fehler bei der Abfrage der Soßen:', sossen.error);
}

// Popup nur anzeigen, wenn mindestens eine Zutat oder Soße vorhanden ist
if (zutaten.length > 0 || sossen.length > 0) {
    showZutatenPopup(product, zutaten, sossen);
} else {
    handleProductWithoutZutaten(product);
}
}).fail(function() {
console.error('Fehler bei einer der Abfragen für Zutaten oder Soßen.');
});
}



function handleProductWithoutZutaten(product) {
// Wenn das Produkt ein Getränk benötigt
if (product.free_drink == 1 && !product.drink) {
console.log('Showing drink selection popup for product:', product);
showDrinkSelectionPopup(product);
} else {
// Wenn das Produkt kein Getränk benötigt oder bereits ein Getränk gewählt wurde
addProductToCart(product);
}
calculateTotalPrice(); // Gesamtpreis berechnen und anzeigen
}

function handleProductWithZutaten(product, selectedZutaten, selectedSossen) {
// Setze die ausgewählten Zutaten im Produkt
product.zutaten = selectedZutaten; // Füge die neuen Zutaten dem Produkt hinzu
product.sossen = selectedSossen; // Füge die ausgewählten Soßen dem Produkt hinzu

// Wenn das Produkt ein Getränk benötigt
if (product.free_drink == 1 && !product.drink) {
console.log('Showing drink selection popup for product:', product);
showDrinkSelectionPopup(product);
} else {
// Wenn das Produkt kein Getränk benötigt oder bereits ein Getränk gewählt wurde
addProductToCart(product);
}

calculateTotalPrice(); // Gesamtpreis berechnen und anzeigen
}



//PIZZA

function showSizeSelectionPopup(product) {
console.log('Sending AJAX request to get pizza sizes for product ID:', product.id);

$.ajax({
url: '../getDateien/get_pizza_sizes.php',
type: 'POST',
data: { produkt_id: product.id },
dataType: 'json',
success: function(sizes) {
    console.log('Größenoptionen erhalten:', sizes);
    if (sizes.length > 0) {
        let sizeListContent = '<h2>Wählen Sie die Größe Ihrer Pizza</h2>';
        sizes.forEach(size => {
            sizeListContent += `
                <label>
                    <input type="radio" name="pizza-size" value="${size.groessen_id}" data-price="${size.preis}">
                    ${size.groesse} - ${size.preis}€
                </label><br>
            `;
        });
        
        $('#size-list').html(sizeListContent);
        $('#popup-overlay').show();
        $('#size-selection-popup').show();

        $('#confirm-size').off('click').on('click', function() {
            const selectedSizeId = $('input[name="pizza-size"]:checked').val();
            const selectedSizePrice = $('input[name="pizza-size"]:checked').data('price');
            
            if (selectedSizeId) {
                product.sizeId = selectedSizeId;
                product.preis = selectedSizePrice;
                $('#size-selection-popup').hide();
                $('#popup-overlay').hide();
                addProductToCart(product);
                calculateTotalPrice();  // Berechne den Gesamtpreis neu

            } else {
                alert('Bitte wählen Sie eine Größe aus.');
            }
        });

        $('#close-size-popup').off('click').on('click', function() {
            $('#size-selection-popup').hide();
            $('#popup-overlay').hide();
        });
    } else {
        alert('Keine Größenoptionen für dieses Produkt verfügbar.');
    }
},
error: function(jqXHR, textStatus, errorThrown) {
    console.error('Fehler beim Laden der Größenoptionen:', textStatus, errorThrown);
    alert('Fehler beim Laden der Größenoptionen.');
}
});
}


function calculateSosseCost() {
const selectedSossenCount = $('.sosse-checkbox:checked').length;
const extraSossen = selectedSossenCount > 1 ? selectedSossenCount - 1 : 0; // Erste Soße kostenlos
return extraSossen * 0.5; // Jede zusätzliche Soße kostet 0,50 €
}


function calculateZutatenCost() {
let zutatenCost = 0;

// Gehe durch alle ausgewählten Zutaten
$('.zutat-checkbox:checked').each(function() {
const zutatId = parseInt($(this).val(), 10); // Hol die ID der Zutat

// Überprüfe die ID und addiere entsprechend die Kosten
if (zutatId === 12 || zutatId === 14 || zutatId === 15) {
    zutatenCost += 1.0; // 1€ für Zutaten mit ID 12, 14 oder 15
} else if (zutatId === 13) {
    zutatenCost += 1.5; // 1,50€ für Zutat mit ID 13
}
});

return zutatenCost;
}

// Funktion zum Anzeigen des Popups
function showZutatenPopup(product, zutaten, sossen) {
var zutatenList = $('#zutaten-list');
var sossenList = $('#sossen-list'); // Liste für Soßen hinzufügen

zutatenList.empty();
sossenList.empty();

// Zutaten mit Checkboxen oder Radio-Buttons hinzufügen
zutatenList.append('<ul>');  // Beginne eine Liste für Zutaten
zutaten.forEach(function(zutat) {

let extraCostText = '';

// Bestimme den Preis basierend auf der zutat.id
if (zutat.id == 12 || zutat.id == 14 || zutat.id == 15) {
    extraCostText = ' (+1€)';
} else if (zutat.id == 13) {
    extraCostText = ' (+1,50€)';
}


if (zutat.id == 9 || zutat.id == 10 || zutat.id == 11) {
    // Radio-Button für exklusive Auswahl zwischen ID 9, 10 und 11
    zutatenList.append(`
    <div>
        <label>
            <input type="radio" name="exclusive-zutat" class="zutat-radio" value="${zutat.id}" ${product.zutaten && product.zutaten.some(selectedZutat => selectedZutat.id == zutat.id) ? 'checked' : ''}>
            ${zutat.name}${extraCostText}
        </label>
    </div>
    `);
}  else {
    // Standard-Checkbox für andere Zutaten
    var isChecked = product.zutaten && product.zutaten.some(selectedZutat => selectedZutat.id == zutat.id);
    zutatenList.append(`
    <div>
        <label>
            <input type="checkbox" class="zutat-checkbox" value="${zutat.id}" ${isChecked ? 'checked' : ''}>
            ${zutat.name}${extraCostText}
        </label>
    </div>
    `);
}
});

zutatenList.append('</ul>'); // Liste für Zutaten abschließen


// Soßen als Liste untereinander anzeigen
if (sossen && sossen.length > 0) {
sossenList.append('<ul>'); // Beginne eine Liste für Soßen
sossen.forEach(function(sosse) {
    const isChecked = product.sossen && product.sossen.some(selectedSosse => selectedSosse.id == sosse.id);
    sossenList.append(`
    <div>
        <label>
            <input type="checkbox" class="sosse-checkbox" value="${sosse.id}" ${isChecked ? 'checked' : ''}>
            ${sosse.name}
        </label>
    </div>
    `);
});
sossenList.append('</ul>'); // Liste für Soßen abschließen
}

// Initiale Kostenanzeige für zusätzliche Soßen
const initialAdditionalCost = product.additionalSosseCost || 0;
const initialZutatenCost = product.additionalZutatenCost || 0;
$('#additional-sosse-cost').remove(); // Entferne vorherige Anzeige, falls vorhanden
$('#sossen-list').append(`<div id="additional-sosse-cost"><strong>Zusätzliche Soßenkosten: ${initialAdditionalCost.toFixed(2)} € </strong></div>`);
$('#additional-zutaten-cost').remove(); // Entferne vorherige Anzeige, falls vorhanden
$('#zutaten-list').append(`<div id="additional-zutaten-cost"><strong>Zusätzliche Zutatenkosten: ${initialZutatenCost.toFixed(2)} € </strong></div>`);


// Event-Listener für die Soßen-Checkboxen, um die Kosten bei jeder Änderung zu aktualisieren
$('.sosse-checkbox').on('change', function() {
const additionalCost = calculateSosseCost();
$('#additional-sosse-cost').html(`<strong>Zusätzliche Soßenkosten: ${additionalCost.toFixed(2)} € </strong>`);
});

$('.zutat-checkbox, .zutat-radio').on('change', function() {
const additionalZutatenCost = calculateZutatenCost();
$('#additional-zutaten-cost').html(`<strong>Zusätzliche Zutatenkosten: ${additionalZutatenCost.toFixed(2)} € </strong>`);
});

// Popup und Overlay anzeigen
$('#popup-overlay').show();
$('#zutaten-popup').show();

// Schließen-Button-Handler
$('#close-popup').click(function() {
$('#zutaten-popup').hide();
$('#popup-overlay').hide();
});

// Event-Listener für den Bestätigen-Button entfernen, bevor er neu hinzugefügt wird
$('#confirm-zutaten').off('click');

// Wenn der User bestätigt, das Produkt hinzuzufügen
$('#confirm-zutaten').click(function() {
var selectedZutaten = [];
var selectedSossen = [];

// Sammle die ausgewählten Radio-Buttons (für exklusive Zutaten)
$('.zutat-radio:checked').each(function() {
    var zutatId = $(this).val();
    var zutatName = $(this).closest('label').text().trim();
    selectedZutaten.push({ id: zutatId, name: zutatName });
});

// Sammle die ausgewählten Checkboxen (für normale Zutaten)
$('.zutat-checkbox:checked').each(function() {
    var zutatId = $(this).val();
    var zutatName = $(this).closest('label').text().trim();
    selectedZutaten.push({ id: zutatId, name: zutatName });
});

// Sammle die ausgewählten Soßen
$('.sosse-checkbox:checked').each(function() {
    var sosseId = $(this).val();
    var sosseName = $(this).closest('label').text().trim();
    selectedSossen.push({ id: sosseId, name: sosseName });
});

// Berechne zusätzliche Soßenkosten und speichere im Produkt
const additionalCost = calculateSosseCost();
const additionalZutatenCost = calculateZutatenCost();
product.additionalSosseCost = additionalCost;
product.additionalZutatenCost = additionalZutatenCost;

$('#zutaten-popup').hide();
$('#popup-overlay').hide();
handleProductWithZutaten(product, selectedZutaten, selectedSossen);
});
}


// Event-Listener für den Stift im Warenkorb (Zutaten bearbeiten)
function editZutaten(product) {
console.log(`Bearbeite Zutaten für Produkt ${product.id}`);

// Ajax-Anfragen für Zutaten und Soßen des Produkts
const getZutaten = $.ajax({
url: '../getDateien/get_zutaten.php',
type: 'POST',
data: { produkt_id: product.id },
dataType: 'json'
});

const getSossen = $.ajax({
url: '../getDateien/get_sossen.php',
type: 'POST',
data: { produkt_id: product.id },
dataType: 'json'
});

// Verarbeitung der Daten nach Erhalt
$.when(getZutaten, getSossen).done(function(zutatenResponse, sossenResponse) {
const zutaten = Array.isArray(zutatenResponse[0]) ? zutatenResponse[0] : [];
const sossen = Array.isArray(sossenResponse[0]) ? sossenResponse[0] : [];

// Zeige das Popup nur, wenn Zutaten oder Soßen vorhanden sind
if (zutaten.length > 0 || sossen.length > 0) {
    showZutatenPopup(product, zutaten, sossen); // Popup mit den aktuellen Daten anzeigen

    // Event-Listener für Bestätigen-Button registrieren
    $('#confirm-zutaten').off('click').on('click', function() {
        var selectedZutaten = [];
        var selectedSossen = [];

        // Sammle die ausgewählten exklusiven Zutaten
        $('.zutat-radio:checked').each(function() {
            selectedZutaten.push({ id: $(this).val(), name: $(this).closest('label').text().trim() });
        });

        // Sammle die ausgewählten Checkbox-Zutaten
        $('.zutat-checkbox:checked').each(function() {
            selectedZutaten.push({ id: $(this).val(), name: $(this).closest('label').text().trim() });
        });

        // Sammle die ausgewählten Soßen (Mehrfachauswahl)
        $('.sosse-checkbox:checked').each(function() {
            selectedSossen.push({ id: $(this).val(), name: $(this).closest('label').text().trim() });
        });

        // Erzeuge Schlüssel, um zu überprüfen, ob sich das Produkt geändert hat
        const selectedZutatenString = selectedZutaten.map(z => z.id).sort().join(',');
        const selectedSossenString = selectedSossen.map(s => s.id).sort().join(',');
        const oldZutatenString = product.zutaten ? product.zutaten.map(z => z.id).sort().join(',') : '';
        const oldSossenString = product.sossen ? product.sossen.map(s => s.id).sort().join(',') : '';
        
        // Erzeuge neuen uniqueKey zur Identifikation des Produkts
        const newUniqueKey = `${product.id}-${product.drink || ''}-${selectedZutatenString}-${selectedSossenString}-${product.sizeId || ''}`;

        if (newUniqueKey === product.uniqueKey) {
            console.log("Keine Änderungen an den Zutaten oder Soßen - Produkt bleibt unverändert.");
        } else {
            // Entferne das alte Produkt und füge das neue hinzu
            removeFromCart(product.uniqueKey);

            // Prüfen, ob ein Produkt mit den neuen Zutaten und Soßen bereits existiert
            const existingProductWithNewZutaten = cart.find(item => item.uniqueKey === newUniqueKey);

            if (existingProductWithNewZutaten) {
                // Produkt bereits im Warenkorb, erhöhe nur die Menge
                existingProductWithNewZutaten.quantity += product.quantity;
                console.log("Produkt mit denselben Zutaten gefunden, Menge erhöht.");
            } else {
                // Aktualisiere das Produkt mit den neuen Zutaten und Soßen
                product.zutaten = selectedZutaten;
                product.sossen = selectedSossen;
                product.uniqueKey = newUniqueKey;
                
                // Berechne zusätzliche Soßenkosten und speichere sie im Produkt
                product.additionalSosseCost = calculateSosseCost();
                product.additionalZutatenCost = calculateZutatenCost();

                
                addProductToCart(product);
                console.log("Neues Produkt mit geänderten Zutaten hinzugefügt.");
            }
        }

        // Popup schließen und Warenkorb neu rendern
        $('#zutaten-popup').hide();
        $('#popup-overlay').hide();
        renderCart();
    });
} else {
    alert('Keine Zutaten oder Soßen für dieses Produkt verfügbar.');
}
}).fail(function() {
console.error('Fehler bei der Abfrage der Zutaten oder Soßen.');
});
}

function calculateSosseCost() {
const selectedSossenCount = $('.sosse-checkbox:checked').length;
const extraSossen = selectedSossenCount > 1 ? selectedSossenCount - 1 : 0; // Erste Soße kostenlos
return extraSossen * 0.5; // Jede zusätzliche Soße kostet 0,50 €
}






//editSize

// Funktion zur Bearbeitung der Größe
function editSize(product) {
console.log(`Bearbeite Größe für Produkt ${product.id}`);

$.ajax({
url: '../getDateien/get_pizza_sizes.php',
type: 'POST',
data: { produkt_id: product.id },
dataType: 'json',
success: function(sizes) {
    if (sizes.length > 0) {
        let sizeListContent = '<h2>Wählen Sie die Größe Ihrer Pizza</h2>';
        sizes.forEach(size => {
            sizeListContent += `
                <label>
                    <input type="radio" name="pizza-size" value="${size.groessen_id}" data-price="${size.preis}" ${product.sizeId == size.groessen_id ? 'checked' : ''}>
                    ${size.groesse} - ${size.preis}€
                </label><br>
            `;
        });

        $('#size-list').html(sizeListContent);
        $('#popup-overlay').show();
        $('#size-selection-popup').show();

        $('#confirm-size').off('click').on('click', function() {
            const selectedSizeId = $('input[name="pizza-size"]:checked').val();
            const selectedSizePrice = parseFloat($('input[name="pizza-size"]:checked').data('price')) || product.preis;
            console.log(`Gesetzter Preis vor Hinzufügen zum Warenkorb: ${product.preis}`);  
            console.log("Selected Size Price:", selectedSizePrice);
            

         
            // Prüfen, ob sich die Größe wirklich geändert hat
            if (selectedSizeId === product.sizeId) {
                console.log("Keine Änderungen an der Größe - Produkt bleibt unverändert.");
            } else {
                const newUniqueKey = `${product.id}-${product.drink || ''}-${product.zutaten ? product.zutaten.map(z => z.id).sort().join(',') : ''}-${selectedSizeId}`;
                
                // Produkt mit alter Größe aus dem Warenkorb entfernen
                removeFromCart(product.uniqueKey);

                // Prüfen, ob ein Produkt mit der neuen Größe bereits existiert
                const existingProductWithNewSize = cart.find(item => item.uniqueKey === newUniqueKey);

                if (existingProductWithNewSize) {
                    existingProductWithNewSize.quantity += product.quantity;
                    console.log(`Neue Menge des Produkts: ${existingProductWithNewSize.quantity}`);
                } else {
                    product.sizeId = selectedSizeId;
                    product.preis = selectedSizePrice;
                    product.uniqueKey = newUniqueKey;
                    console.log(`Gesetzter Preis vor dem Hinzufügen: ${product.preis}`); // Preis prüfen
                    addProductToCart(product);
                }
            }

            // Popup schließen
            $('#size-selection-popup').hide();
            $('#popup-overlay').hide();
            renderCart();
            calculateTotalPrice();
        });

        $('#close-size-popup').off('click').on('click', function() {
            $('#size-selection-popup').hide();
            $('#popup-overlay').hide();
        });
    } else {
        alert('Keine Größenoptionen für dieses Produkt verfügbar.');
    }
},
error: function() {
    console.error('Fehler beim Laden der Größenoptionen.');
}
});
}







/*NEU FÜR ZUTATEN*/



function addProductToCart(product) {
console.log(`Produktpreis beim Hinzufügen zum Warenkorb: ${product.preis}`); // Preis hier erneut prüfen
const selectedDrink = product.drink || '';  // Leerer String, falls kein Getränk
const selectedZutaten = product.zutaten ? product.zutaten.map(z => z.id).sort().join(',') : ''; 
const selectedSossen = product.sossen ? product.sossen.map(s => s.id).sort().join(',') : ''; // Neue Zeile für Soßen
const selectedSize = product.sizeId || '';  

const uniqueKey = `${product.id}-${selectedDrink}-${selectedZutaten}-${selectedSossen}-${selectedSize}`; // Unique Key anpassen

console.log(`Erstellter uniqueKey: ${uniqueKey}`);

const existingProduct = cart.find(item => item.uniqueKey === uniqueKey);

if (existingProduct) {
existingProduct.quantity++;
console.log('Produkt bereits vorhanden, erhöhe Menge:', existingProduct);
} else {
const newProduct = JSON.parse(JSON.stringify(product)); // Tiefe Kopie erstellen
newProduct.uniqueKey = uniqueKey;
newProduct.quantity = 1;

 // Setze den Preis entsprechend der ausgewählten Größe und Soßenkosten, falls vorhanden
//newProduct.preis = product.preis + product.additionalSosseCost; // Addiere zusätzliche Soßenkosten

// Neu für Soßen - Hier könnte eine Logik hinzugefügt werden, falls der Preis für Soßen relevant ist
// Beispiel: if (product.sossenPreis) { newProduct.preis += product.sossenPreis; }

cart.push(newProduct);
console.log('Neues Produkt hinzufügen:', newProduct);
}

renderCart();
updateCartCount();
calculateTotalPrice();
}



//SOFTDRINKUNTERTEILUNG

// Funktion zur Anzeige des Softdrink-Popups
function showSoftdrinkSelectionPopup(product, materialType) {
let existingPopup = document.querySelector('.softdrink-selection-popup');
if (existingPopup) {
console.log('Softdrink-Popup existiert bereits.');
return; // Kein neues Popup hinzufügen, wenn es bereits existiert
}

// Pop-up erstellen und dem Body hinzufügen
const popup = document.createElement('div');
popup.className = 'softdrink-selection-popup';
popup.innerHTML = `
<h3>Wählen Sie Ihren Softdrink (${materialType})</h3>
<select id="softdrinkSelect">
    <option value="">Bitte wählen Sie einen Softdrink</option>
</select>
<button id="confirmSoftdrink">Bestätigen</button>
<button id="closeSoftdrinkPopup">Schließen</button>
`;
document.body.appendChild(popup);

loadSoftdrinkOptions(materialType); // Softdrink-Varianten basierend auf Material laden

document.getElementById('confirmSoftdrink').addEventListener('click', function () {
const selectedSoftdrinkId = document.getElementById('softdrinkSelect').value;
const selectedSoftdrinkName = document.querySelector(`#softdrinkSelect option[value="${selectedSoftdrinkId}"]`).textContent;

if (selectedSoftdrinkId) {
    // Produkt mit Softdrink-Variante aktualisieren
    product.softdrinkId = selectedSoftdrinkId;
    product.softdrinkName = selectedSoftdrinkName;

    // Softdrink dem Warenkorb hinzufügen
    addProductToCart(product);

    // Pop-up schließen
    const popup = document.querySelector('.softdrink-selection-popup');
    if (popup) {
        popup.remove();
    }
} else {
    alert('Bitte wählen Sie einen Softdrink aus.');
}
});

// Event-Listener für Schließen hinzufügen
document.getElementById('closeSoftdrinkPopup').addEventListener('click', function () {
const popup = document.querySelector('.softdrink-selection-popup');
if (popup) {
    popup.remove(); // Pop-up entfernen
}
});
}

// Funktion zum Laden der Softdrink-Optionen basierend auf Material (Dose oder Glas)
function loadSoftdrinkOptions(materialType) {
const select = document.getElementById('softdrinkSelect');
if (!select) {
console.error('Softdrink select element not found!');
return;
}

// AJAX-Anfrage, um die Softdrink-Varianten basierend auf Material zu holen
const xhr = new XMLHttpRequest();
xhr.open('GET', `../getDateien/get_softdrinks.php?material=${materialType}`, true);
xhr.onload = function() {
if (xhr.status === 200) {
    try {
        const softdrinks = JSON.parse(xhr.responseText);

        // Alle bestehenden Optionen löschen
        select.innerHTML = '<option value="">Bitte wählen Sie einen Softdrink</option>';

        // Neue Optionen hinzufügen
        softdrinks.forEach(softdrink => {
            const option = document.createElement('option');
            option.value = softdrink.id;
            option.textContent = softdrink.name;
            select.appendChild(option);
        });

    } catch (e) {
        console.error('Fehler beim Parsen der JSON-Antwort:', e);
    }
} else {
    console.error('Fehler beim Laden der Softdrinks:', xhr.status);
}
};
xhr.send();
}




//SOFTDRINKUNTERTEILUNG



function showDrinkSelectionPopup(product) {
let existingPopup = document.querySelector('.drink-selection-popup');
if (existingPopup) {
console.log('Pop-up existiert bereits.');
return; // Kein neues Pop-up hinzufügen, wenn es bereits existiert
}

// Pop-up erstellen und dem Body hinzufügen
const popup = document.createElement('div');
popup.className = 'drink-selection-popup';
popup.innerHTML = `
<h3>Wählen Sie Ihr kostenloses Getränk</h3>
<select id="drinkSelect">
    <option value="">Bitte wählen Sie ein Getränk</option>
</select>
<button id="confirmDrink">Bestätigen</button>
<button id="closePopup">Schließen</button>
`;
document.body.appendChild(popup);

loadDrinkOptions(); // Getränkeoptionen laden

document.getElementById('confirmDrink').addEventListener('click', function () {
const selectedDrinkId = document.getElementById('drinkSelect').value;
const selectedDrinkName = document.querySelector(`#drinkSelect option[value="${selectedDrinkId}"]`).textContent;

if (selectedDrinkId) {
    const oldDrink = product.drink;
    const oldDrinkName = product.drinkName;

    // Entferne das Produkt mit dem alten Getränk aus dem Warenkorb
    const selectedZutaten = product.zutaten ? product.zutaten.map(z => z.id).sort().join(',') : ''; 
    const selectedSossen = product.sossen ? product.sossen.map(s => s.id).sort().join(',') : ''; // Soßen hinzufügen

    const oldUniqueKey = `${product.id}-${oldDrink || ''}-${selectedZutaten}-${selectedSossen}-${product.sizeId || ''}`;
    console.log(`Produkt mit altem Getränk wird entfernt: ${oldUniqueKey}`);
    removeFromCart(oldUniqueKey); // Produkt mit altem Getränk entfernen

    // Aktualisiere das Produkt mit den neuen Getränkinformationen
    product.drink = selectedDrinkId;
    product.drinkName = selectedDrinkName;  // Speichere auch den Getränkenamen

    // Füge das Produkt mit dem neuen Getränk wieder hinzu
    console.log(`Produkt mit neuem Getränk wird hinzugefügt: ${product.drinkName}`);
    addProductToCart(product);

    // Pop-up schließen
    const popup = document.querySelector('.drink-selection-popup');
    if (popup) {
        popup.remove(); // Pop-up entfernen
    }
} else {
    alert('Bitte wählen Sie ein Getränk aus.'); // Warnung, falls kein Getränk ausgewählt wurde
}
});

// Event-Listener für Schließen hinzufügen
document.getElementById('closePopup').addEventListener('click', function () {
const popup = document.querySelector('.drink-selection-popup');
if (popup) {
    popup.remove(); // Pop-up entfernen
}
});
}


function loadDrinkOptions() {
const select = document.getElementById('drinkSelect');
if (!select) {
    console.error('Drink select element not found!');
    return;
}

// AJAX-Anfrage, um die Getränke aus der Datenbank zu holen
const xhr = new XMLHttpRequest();
xhr.open('GET', '../getDateien/get_drinks.php', true);
xhr.onload = function() {
    if (xhr.status === 200) {
        try {
            const drinks = JSON.parse(xhr.responseText);

            // Alle bestehenden Optionen löschen
            select.innerHTML = '<option value="">Bitte wählen Sie ein Getränk</option>';

            // Dose-Getränke
            if (drinks.Dose && drinks.Dose.length > 0) {
                const doseGroup = document.createElement('optgroup');
                doseGroup.label = "Dose";
                drinks.Dose.forEach(drink => {
                    const option = document.createElement('option');
                    option.value = drink.id;
                    option.textContent = drink.name;
                    doseGroup.appendChild(option);
                });
                select.appendChild(doseGroup);
            }

            // Glasflasche-Getränke
            if (drinks.Glas && drinks.Glas.length > 0) {
                const glasGroup = document.createElement('optgroup');
                glasGroup.label = "Glasflasche";
                drinks.Glas.forEach(drink => {
                    const option = document.createElement('option');
                    option.value = drink.id;
                    option.textContent = drink.name;
                    glasGroup.appendChild(option);
                });
                select.appendChild(glasGroup);
            }

        } catch (e) {
            console.error('Fehler beim Parsen der JSON-Antwort:', e);
        }
    } else {
        console.error('Fehler beim Laden der Getränke:', xhr.status);
    }
};
xhr.send();
}



// Aktualisiere den Warenkorb-Zähler
function updateCartCount() {
    const itemCount = cart.reduce((total, item) => total + item.quantity, 0);
    const cartCountElement = document.getElementById('cart-count');
    cartCountElement.textContent = itemCount;

    // Füge die "bump"-Klasse hinzu und entferne sie nach der Animation
    cartCountElement.classList.add('bump');
    setTimeout(() => {
        cartCountElement.classList.remove('bump');
    }, 200); // Die Zeit muss mit der CSS-Transition übereinstimmen
}


// Initial Button disabled
const buyNowButton = document.getElementById('buy-now');
buyNowButton.disabled = true;
buyNowButton.classList.add('disabled'); // Button initial ausgegraut

// Element für die Meldung, wenn keine Option ausgewählt wurde
const errorMessage = document.getElementById('error-message');



// Funktion zur Überprüfung, ob der Button aktiviert/deaktiviert werden soll
function updateButtonState() {
    const totalPrice = cart.reduce((total, item) => {
    const itemPrice = parseFloat(item.preis) || 0;
    const itemQuantity = parseInt(item.quantity) || 0;
    const additionalSosseCost = parseFloat(item.additionalSosseCost) || 0;
    const additionalZutatenCost = parseFloat(item.additionalZutatenCost) || 0;
    
    //const selectedCity = document.getElementById('stadtDropdown').value; // Stadt aus Dropdown
    
    // Berechne den Preis für dieses Produkt inklusive Zusatzkosten und multipliziere mit der Menge
return total + ((itemPrice + additionalSosseCost + additionalZutatenCost) * itemQuantity);
}, 0);

const paymentMethodSelected = document.querySelector('input[name="payment_method"]:checked');
    
    if (totalPrice > 0) {
        buyNowButton.disabled = false;
        buyNowButton.classList.remove('disabled');
        errorMessage.textContent = ''; // Nachricht leeren
        
        // Überprüfen, ob eine Zahlungsmethode gewählt wurde
        if (!paymentMethodSelected) {
            errorMessage.style.display = 'block';  // Fehlermeldung anzeigen
            errorMessage.textContent = 'Bitte wählen Sie eine Zahlungsmethode.';
            document.querySelector('.payment-method').style.border = '2px solid red';  // Zeige roten Rand um Zahlungsmethode
            return;
        } else {
            errorMessage.style.display = 'none';  // Fehlermeldung ausblenden, wenn eine Zahlungsmethode gewählt wurde
            document.querySelector('.payment-method').style.border = 'none';
        }

    } else {
        buyNowButton.disabled = true;
        buyNowButton.classList.add('disabled');
        errorMessage.textContent = 'Bitte füge zuerst etwas in den Warenkorb hinzu.';
    }
}
    

// Event-Listener für Änderungen bei der Zahlungsmethode hinzufügen
document.querySelectorAll('input[name="payment_method"]').forEach((radio) => {
radio.addEventListener('change', updateButtonState);
});

// Initialisiere die Seite und überprüfe die Verfügbarkeit der Lieferung beim Laden
document.addEventListener('DOMContentLoaded', function() {
//checkDeliveryAvailability(); // Überprüfung, ob Lieferung verfügbar ist
updateButtonState(); // Initialer Buttonstatus
});



// Überprüfe, ob eine Zahlungsmethode gewählt wurde
function proceedToCheckout(event) {
event.preventDefault();  // Verhindere das automatische Absenden des Formulars

const paymentMethodSelected = document.querySelector('input[name="payment_method"]:checked');

// Überprüfen, ob eine Zahlungsmethode gewählt wurde
if (!paymentMethodSelected) {
    errorMessage.style.display = 'block';  // Fehlermeldung anzeigen
    errorMessage.textContent = 'Bitte wählen Sie eine Zahlungsmethode.';
    document.querySelector('.payment-method').style.border = '2px solid red';  // Zeige roten Rand um Zahlungsmethode
    return;
} else {
    errorMessage.style.display = 'none';  // Fehlermeldung ausblenden, wenn eine Zahlungsmethode gewählt wurde
    document.querySelector('.payment-method').style.border = 'none';
}

}




function calculateTotalPrice() {
let totalPrice = 0;

// Berechne den Gesamtpreis
cart.forEach(item => {
const itemPrice = parseFloat(item.preis) || 0;
const itemQuantity = parseInt(item.quantity) || 0;
const additionalSosseCost = parseFloat(item.additionalSosseCost) || 0; // Zusätzliche Soßenkosten aus dem Produkt
const additionalZutatenCost = parseFloat(item.additionalZutatenCost) || 0; // Zusätzliche Zutatenkosten aus dem Produkt

totalPrice += (itemPrice + additionalSosseCost + additionalZutatenCost) * itemQuantity; // Inklusive Soßenkosten

// Debugging-Ausgaben
console.log(`Artikel: ${item.name}, Preis: ${itemPrice}, Menge: ${itemQuantity}`);
});

console.log('Gesamtpreis berechnet:', totalPrice);

// Update der HTML-Anzeige
const totalPriceElement = document.getElementById('total-price');
if (totalPriceElement) {
totalPriceElement.textContent = `${totalPrice.toFixed(2)} €`;
} else {
console.error("Das Element mit der ID 'total-price' wurde nicht gefunden.");
}

// Button-State überprüfen
updateButtonState();
}



// Setze die Initialmeldungen und Buttonstatus direkt nach dem Laden der Seite
document.addEventListener('DOMContentLoaded', function () {
updateButtonState(); // Stelle sicher, dass der Button und die Nachricht richtig initialisiert sind

});



//PIZZAGRÖßEN
function getSizeLabel(sizeId) {
switch (sizeId) {
case "1":
    return "28cm";
case "2":
    return "32cm";
case "3":
    return "Familiengröße";
default:
    return "Standardgröße";
}
}




function renderCart() {
const cartItemsContainer = document.getElementById('cart-items');
cartItemsContainer.innerHTML = ''; // Vorherige Einträge löschen

// Definiere die ID-Listen für Glas und Dose
const softdrinkglas_ids = [11, 12, 13, 14, 15, 16];
const softdrinksdose_ids = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

// Temporäre Struktur, um die Produkte basierend auf ID, Getränk und Zutaten zusammenzuführen
const combinedCart = {};

cart.forEach(item => {
// Erstelle einen eindeutigen Schlüssel aus Produkt-ID, Getränk, Zutaten und Soßen
const selectedDrink = item.drink || '';
const selectedZutaten = item.zutaten ? item.zutaten.map(z => z.id).sort().join(',') : '';
const selectedSossen = item.sossen ? item.sossen.map(s => s.id).sort().join(',') : ''; // Soßen hinzufügen
const selectedSize = item.sizeId || '';

const key = `${item.id}-${selectedDrink}-${selectedZutaten}-${selectedSossen}-${selectedSize}`;

if (combinedCart[key]) {
    combinedCart[key].quantity += item.quantity;
} else {
    combinedCart[key] = { ...item }; // Erstelle eine Kopie des Produkts
}
});

Object.values(combinedCart).forEach(item => {
const cartItem = document.createElement('div');
cartItem.classList.add('cart-item');

// Größe mithilfe der getSizeLabel-Funktion formatieren
const sizeLabel = item.sizeId ? getSizeLabel(item.sizeId) : "Standard";

// Bestimme den Bildpfad und den Bildnamen abhängig von der ID des Artikels
let bildPfad;
let bildname;

console.log('Item ID:', item.id, 'Type:', typeof item.id);

if (softdrinkglas_ids.includes(Number(item.id))) {
    bildPfad = "uploads/coca-cola-473780_640.jpg";
    bildname = "Softdrinks Glasflasche 0,33l";
} else if (softdrinksdose_ids.includes(Number(item.id))) {
    bildPfad = "uploads/pexels-gustavo-santana-3928789-5860659.jpg";
    bildname = "Softdrinks Dose 0,33l";
} else {
    bildPfad = `uploads/${item.bild}`; // Fallback auf das individuelle Bild, falls vorhanden
    bildname = item.name; // Setze den Namen des Artikels als Fallback-Bildname
}

// Debugging-Informationen
console.log('Item:', item); // Protokolliere das gesamte Item-Objekt
console.log('Item ID:', item.id);
console.log('Bild Pfad:', bildPfad);
console.log('Bild Name:', bildname);


// Berechne den Gesamtpreis für das Produkt inklusive zusätzlicher Soßenkosten
const itemTotalPrice = (parseFloat(item.preis) + (parseFloat(item.additionalSosseCost) || 0) + (parseFloat(item.additionalZutatenCost) || 0)) * item.quantity;

// HTML für das Produkt im Warenkorb
cartItem.innerHTML = `
    <img src="${bildPfad}" alt="${bildname}">
    <div class="cart-item-content">
        <h4>${item.name}</h4>
        ${item.kategorie === 'Pizza' ? `<p>Größe: ${sizeLabel} <span class="edit-size" data-product-id="${item.id}" data-price="${item.preis}" title="Größe bearbeiten" style="cursor: pointer;">✏️</span></p>` : ''}
        ${item.drinkName ? `<p>Getränk: ${item.drinkName} <span class="edit-drink" data-product-id="${item.id}" data-drink-id="${item.drink}" title="Getränk bearbeiten" style="cursor: pointer;">✏️</span></p>` : ''}
        <p>${itemTotalPrice.toFixed(2)}€</p>
        ${item.additionalSosseCost ? `<p><strong>Zusätzliche Soßenkosten: ${item.additionalSosseCost.toFixed(2)} €</strong></p>` : ''}
        ${item.additionalZutatenCost ? `<p><strong>inkl. zusätzliche Zutatenkosten: ${item.additionalZutatenCost.toFixed(2)} €</strong></p>` : ''}
        ${item.zutaten && item.zutaten.length > 0 ? `Zutaten: ${item.zutaten.map(zutat => zutat.name).join(', ')}` : ''}
        ${item.sossen && item.sossen.length > 0 ? `<br>Soßen: ${item.sossen.map(sosse => sosse.name).join(', ')}` : ''} 
        ${(item.zutaten && item.zutaten.length > 0) || (item.sossen && item.sossen.length > 0) ? 
        `<span class="edit-zutaten" data-product-id="${item.id}" title="Zutaten bearbeiten" style="cursor: pointer;">Zutaten✏️</span>` 
        : ''}
    </div>
    <div class="cart-item-actions">
        <div class="quantity-buttons">
            <button class="decrease-quantity" data-uniqueKey="${item.uniqueKey}">-</button>
            <span>${item.quantity}</span>
            <button class="increase-quantity" data-uniqueKey="${item.uniqueKey}">+</button>
        </div>
        <button class="remove-button" type="button" data-uniqueKey="${item.uniqueKey}" title="Produkt entfernen">🗑️</button>
    </div>
`;

// Event-Listener für das Bearbeiten der Größe
cartItem.querySelector('.edit-size')?.addEventListener('click', function () {
    editSize(item);
});

// Event-Listener für das Bearbeiten der Zutaten
cartItem.querySelector('.edit-zutaten')?.addEventListener('click', function () {
    editZutaten(item);
});

// Event-Listener für das Bearbeiten des Getränks
cartItem.querySelector('.edit-drink')?.addEventListener('click', function () {
    showDrinkSelectionPopup(item);
});

cartItemsContainer.appendChild(cartItem);
});

// Entferne den alten Event-Listener, falls vorhanden
cartItemsContainer.removeEventListener('click', handleCartActions);

// Füge den Event-Listener für +, -, und Papierkorb hinzu
cartItemsContainer.addEventListener('click', handleCartActions);

// Hidden-Field mit den Warenkorbdaten aktualisieren
document.getElementById('orderItems').value = JSON.stringify(cart);
}



function handleCartActions(event) {
const target = event.target;
const uniqueKey = target.dataset.uniquekey;

// Bei Klick auf den "Papierkorb"-Button
if (target.classList.contains('remove-button')) {
const confirmDelete = confirm("Sind Sie sicher, dass Sie das Produkt entfernen möchten?");
if (confirmDelete) {
    removeFromCart(uniqueKey);  // Produkt aus dem Warenkorb entfernen
} else {
    event.preventDefault();  // Verhindert das Absenden des Formulars
}
}

// Bei Klick auf den "Minus"-Button
if (target.classList.contains('decrease-quantity')) {
const existingProduct = cart.find(item => item.uniqueKey === uniqueKey);

if (existingProduct) {
    if (existingProduct.quantity > 1) {
        existingProduct.quantity--;  // Menge reduzieren
    } else {
        // Wenn die Menge 1 ist und auf - gedrückt wird, Produkt entfernen
        const confirmDelete = confirm("Möchten Sie das Produkt entfernen?");
        if (confirmDelete) {
            removeFromCart(uniqueKey);  // Produkt aus dem Warenkorb entfernen
        }
    }
}
}

// Bei Klick auf den "Plus"-Button
if (target.classList.contains('increase-quantity')) {
const existingProduct = cart.find(item => item.uniqueKey === uniqueKey);
if (existingProduct) {
    existingProduct.quantity++;  // Menge erhöhen
}
}

// Warenkorb nach den Aktionen neu rendern
renderCart();
updateCartCount();
calculateTotalPrice();
}

// Diese Funktion gibt den Getränkenamen anhand der ID zurück
function getDrinkName(drinkId) {
const drink = drinksList.find(drink => drink.id === parseInt(drinkId));
return drink ? drink.name : 'Unbekanntes Getränk';
}

function handleCartActions(event) {
const target = event.target;

// Mengenreduzierung
if (target.classList.contains('decrease-quantity')) {
const uniqueKey = target.getAttribute('data-uniqueKey');
const product = cart.find(item => item.uniqueKey === uniqueKey);

if (product && product.quantity > 1) {
    // Reduziere die Menge um 1, wenn mehr als 1 vorhanden ist
    product.quantity--;
    renderCart();
    updateCartCount();
    calculateTotalPrice();
} else if (product && product.quantity === 1) {
    // Wenn die Menge auf 1 ist, frage, ob das Produkt entfernt werden soll
    const confirmDelete = confirm('Möchten Sie das Produkt wirklich aus dem Warenkorb entfernen?');
    if (confirmDelete) {
        // Produkt entfernen, wenn der Nutzer bestätigt
        removeFromCart(uniqueKey);
    } else {
        renderCart(); // Stelle sicher, dass der Warenkorb korrekt neu gerendert wird, wenn der Nutzer "Abbrechen" wählt
    }
}
}

// Mengenerhöhung
if (target.classList.contains('increase-quantity')) {
const uniqueKey = target.getAttribute('data-uniqueKey');
const product = cart.find(item => item.uniqueKey === uniqueKey);

if (product) {
    // Erhöhe die Menge um 1
    product.quantity++;
    renderCart();
    updateCartCount();
    calculateTotalPrice();
}
}

// Produkt entfernen (Papierkorb)
if (target.classList.contains('remove-button')) {
const uniqueKey = target.getAttribute('data-uniqueKey');
const confirmDelete = confirm('Möchten Sie das Produkt wirklich aus dem Warenkorb entfernen?');
if (confirmDelete) {
    // Produkt entfernen, wenn der Nutzer bestätigt
    removeFromCart(uniqueKey);
}
}
}


function removeFromCart(uniqueKey) {
// Finde das Produkt im Warenkorb anhand des uniqueKey
const productIndex = cart.findIndex(item => item.uniqueKey === uniqueKey);

if (productIndex > -1) {
// Produkt aus dem Warenkorb entfernen
console.log(`Produkt mit Key ${uniqueKey} wird aus dem Warenkorb entfernt.`);
cart.splice(productIndex, 1);  // Entferne das Produkt aus dem Warenkorb
renderCart();  // Warenkorb nach dem Entfernen neu rendern
updateCartCount();  // Warenkorb-Anzahl aktualisieren
calculateTotalPrice();  // Gesamtpreis berechnen
}
}

document.getElementById('cart-items').addEventListener('click', handleCartActions);


function updateQuantity(productId, drinkId, amount) {
console.log(`Update quantity for product ${productId} with drink ${drinkId} by ${amount}`);

// Finde das Produkt anhand der ID und ggf. des Getränks
const product = cart.find(item => item.id == productId && (item.drink == drinkId || !item.drink));

if (product) {
product.quantity += amount;

// Wenn die Menge 0 oder kleiner wird, soll das Produkt entfernt werden
if (product.quantity <= 0) {
    const confirmation = confirm("Sie sind dabei, das Produkt aus dem Warenkorb zu entfernen. Sind Sie sicher?");
    if (confirmation) {
        removeFromCart(productId, drinkId); // Produkt aus dem Warenkorb entfernen
    } else {
        product.quantity = 1; // Setze die Menge wieder auf 1, falls nicht entfernt werden soll
    }
} else {
    renderCart();  // Aktualisiere den Warenkorb, wenn das Produkt noch existiert
}

updateCartCount();  // Zähler aktualisieren
calculateTotalPrice();  // Gesamtpreis aktualisieren
}
}

cartItemsContainer.addEventListener('click', function (event) {
const target = event.target;
const productId = target.getAttribute('data-id');
const drinkId = target.getAttribute('data-drink');

if (target.classList.contains('increase-quantity')) {
updateQuantity(productId, drinkId, 1); // Menge erhöhen
} else if (target.classList.contains('decrease-quantity')) {
updateQuantity(productId, drinkId, -1); // Menge verringern
} else if (target.classList.contains('remove-button')) {
const confirmation = confirm("Sind Sie sicher, dass Sie dieses Produkt entfernen möchten?");
if (confirmation) {
    removeFromCart(productId, drinkId);  // Produkt entfernen
}
}
});
