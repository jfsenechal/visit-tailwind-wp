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

export default function oljf() {
    Alpine.directive('foo')
    window.Alpine = Alpine
    window.Alpine.start()
    console.log(`map for lll`);
}