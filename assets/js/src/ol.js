import 'ol/ol.css';
import Map from 'ol/Map.js';
import OSM from 'ol/source/OSM.js';
import View from 'ol/View.js';
import {fromLonLat} from "ol/proj";
import Point from "ol/geom/Point";
import TileJSON from "ol/source/TileJSON";
import Feature from "ol/Feature";
import {Tile as TileLayer, Vector as VectorLayer} from "ol/layer";
import VectorSource from "ol/source/Vector";
import Alpine from 'alpinejs'

export default function oljf2() {
    let longitude = 50.226484;
    let latitude = 5.342961;

    const rome = new Feature({
        geometry: new Point(fromLonLat([latitude, longitude]))
    });

    const vectorSource = new VectorSource({
        features: [rome]
    });

    const vectorLayer = new VectorLayer({
        source: vectorSource
    });

    const map = new Map({
        target: 'openmap_offre',
        layers: [
            new TileLayer({
                source: new OSM(),
            }),
            vectorLayer
        ],
        view: new View({
            center: fromLonLat([latitude, longitude]),
            zoom: 12
        }),
    });
};