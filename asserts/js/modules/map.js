import L from 'leaflet'

export default class Map {

    static init () {
        let map = document.querySelector('#map')
        if (map === null) {
            return
        }
        let center = [map.dataset.lat, map.dataset.lng]
        map = L.map('map').setView(center, 13)
        let token = 'pk.eyJ1IjoiZ2VsZWV0eiIsImEiOiJjazVpMmF1ZDYwOWFpM2xtbTJ6cGo5OTEwIn0.nq6hywv7r9NikPv5ynGVRg'
        L.tileLayer(`https://api.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}.png?access_token=${token}`, {
            maxZoom: 18,
            minZoom: 12,
            attribution: '© <a href="https://www.mapbox.com/feedback/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map)
        L.marker(center).addTo(map)
    }
}



