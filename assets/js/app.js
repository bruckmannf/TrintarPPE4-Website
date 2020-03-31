var $ = require('jquery');

require('../css/app.css');

require('select2')

$('select').select2()


import Places from 'places.js'

let inputAddress = document.querySelector('#magasin_adresse')
if (inputAddress !== null) {
    let place = Places({
        container: inputAddress
    })
    place.on('change', e => {
        document.querySelector('#magasin_ville').value = e.suggestion.city
        document.querySelector('#magasin_departement').value = e.suggestion.county
        document.querySelector('#magasin_pays').value = e.suggestion.country
        document.querySelector('#property_lat').value = e.suggestion.latlng.lat
        document.querySelector('#property_lng').value = e.suggestion.latlng.lng
    })
}
