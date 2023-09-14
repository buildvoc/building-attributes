let map, popup, geojsonSource, geojsonLayer;

let ofaParams = {};

let objStyle = {};

//
let get_objCollectionId = function(collectionId) {
    const arrCollectionId = collectionId.split("-");
    let _objCollectionId = {
        theme: arrCollectionId[0],
        collection: arrCollectionId[1],
        featureType: arrCollectionId[2]
    };

    if( arrCollectionId.length == 4 )
        _objCollectionId.version = arrCollectionId[3];

    return _objCollectionId
};

// Return a BBOX string for the map view extent (with coordinates to six decimal places).
let get_strBBOX = function() {
    const bbox = ol.proj.transformExtent(
        map.getView().calculateExtent(map.getSize()),
        'EPSG:3857',
        'EPSG:4326'
    );
    return bbox.map(i => Number(i).toFixed(6)).toString();
}

// Return the filter parameters as an encoded URI string.
let get_strEncodedParameters = function(ofaParams) {
    return Object.keys(ofaParams)
        .map(paramName => paramName + '=' + encodeURI(ofaParams[paramName]))
        .join('&');
};

// Elements that make up the popup.
const container = document.getElementById('popup');
const content = document.getElementById('popup-content');
const closer = document.getElementById('popup-closer');

// Create an overlay to anchor the popup to the map.
const overlay = new ol.Overlay({
    element: container,
    autoPan: {
        animation: {
            duration: 250
        }
    }
});

/**
 * Add a click handler to hide the popup.
 * @return {boolean} Don't follow the href.
 */
closer.onclick = function () {
    overlay.setPosition(undefined);
    closer.blur();
    return false;
};

/*
 *
 */
function init() {
    //
    var extent = [ -3.5589524, 50.6991958, -3.4201197, 50.7909038 ];

    // Initialize the map object.
    map = new ol.Map({
        layers: [
            new ol.layer.Tile({
                source: new ol.source.XYZ({
                    url: `${config.zxyServiceUrl}/zxy/Light_3857/{z}/{x}/{y}.png`,
                    tileLoadFunction: (tile, src) => {
                        fetch(src, {
                            headers: {
                                'key': config.apiKey
                            }
                        })
                            .then(response => {
                                if( response.ok ) {
                                    return response
                                }
                                return Reject(response);
                            })
                            .then(response => response.blob())
                            .then(blob => {
                                const reader = new FileReader();
                                reader.onloadend = () => {
                                    const data = 'data:image/png;base64,' + btoa(reader.result);
                                    tile.getImage().src = data;
                                };
                                reader.readAsBinaryString(blob);
                            })
                            .catch(error => {});
                    }
                }),
                className: 'grayscale',
                opacity: 0.5
            })
        ],
        overlays: [ overlay ],
        target: 'map',
        view: new ol.View({
            projection: 'EPSG:3857',
            extent: ol.proj.transformExtent(extent, 'EPSG:4326', 'EPSG:3857'),
            minZoom: 14,
            maxZoom: 20,
            center: ol.proj.fromLonLat(center),
            zoom: zoom
        })
    });

    // Create a 'singleclick' event handler which displays a popup with some basic
    // HTML content.
    map.on('singleclick', function(evt) {
        const coordinate = evt.coordinate;

        overlay.setPosition(undefined);
        closer.blur();

        let selectedFeatures = [];

        const feature = map.forEachFeatureAtPixel(evt.pixel, function(feature, layer) {
            if( layer.get('name') === 'geojson' ) {
                selectedFeatures.push(feature);
            }
        });

        if( selectedFeatures.length > 0 ) {
            // Get the feature properties (excluding the geometry).
            let properties = selectedFeatures[0].getProperties();
            properties = (({ geometry, ...o }) => o)(properties);

            // Construct the popup content.
            let htmlContent = '<table style="font-size:1em">';
            for( let i in properties ) {
                let value = Array.isArray(properties[i]) ? JSON.stringify(properties[i]) : properties[i];
                htmlContent += `<tr><td>${i}</td><td>${value}</td></tr>`;
            }
            htmlContent += '<table>';

            content.innerHTML = htmlContent;
            overlay.setPosition(coordinate);
        }
    });

    // Use hasFeatureAtPixel() to indicate that the features are clickable by changing
    // the cursor style to 'pointer'.
    map.on('pointermove', function(evt) {
        if( evt.dragging ) return;

        const hit = map.hasFeatureAtPixel(evt.pixel, {
            layerFilter: function(layer) {
                return layer.get('name') === 'geojson';
            }
        });

        map.getViewport().style.cursor = hit ? 'pointer' : '';
    });

    //
    (async () => {
        //
        objStyle = await fetch(config.styleRulesUrl, { cache: "reload" }).then(response => response.json());

        // Add a layer for rendering the GeoJSON features on the map.
        geojsonSource = new ol.source.Vector();
        geojsonLayer = new ol.layer.Vector({
            name: 'geojson',
            source: geojsonSource,
            style: styleFunction,
            zIndex: 1
        });
        map.addLayer(geojsonLayer);
    })();
}

/**
 *
 */
function update() {
    objCollectionId = get_objCollectionId(collectionId);

    ofaParams.bbox = get_strBBOX();

    const elemBBOX = document.getElementById('bbox');
    if( document.body.contains(elemBBOX) && elemBBOX.checked === false )
        delete ofaParams.bbox;

    const elemCQL = document.getElementById('cql');
    if( document.body.contains(elemCQL) ) {
        delete ofaParams.filter;
        if( elemCQL.disabled === false && elemCQL.value !== '' )
            ofaParams.filter = elemCQL.value;
    }

    let url = `${config.ofaServiceUrl}/collections/${collectionId}/items?${get_strEncodedParameters(ofaParams)}`;

    request(url);
}

/**
 *
 */
function request(url) {
    process(true);

    overlay.setPosition(undefined);
    closer.blur();

    //
    const elemCollections = document.getElementById('collections');
    const elemDescription = document.getElementById('description');
    if( document.body.contains(elemCollections) && document.body.contains(elemDescription) ) {
        const selectedIndex = elemCollections.selectedIndex;
        elemDescription.innerText = objCollections.collections[selectedIndex].description;
    }

    document.querySelectorAll('#href a')[0].href = url;
    document.querySelectorAll('#href a')[0].innerText = url;

    navItems.forEach(element => { element.removeAttribute("href") });

    (async () => {
        alertify.defaults.transition = 'zoom';
        alertify.defaults.movable = false;

        const features = await fetch(url, {
            headers: {
                'Accept': 'application/geo+json',
                'key': config.apiKey
            }
        })
            .then(response => response.json())
            .catch((error) => {
                console.error('Error:', error);
            });

        process(false);

        geojsonSource.clear(true);

        //
        if(! features ) return;

        //
        if( features.code >= 400 && features.code < 600 ) {
            let message = `<p style="padding-bottom:8px;">${features.description}</p>`;
            if( features.hasOwnProperty('help') )
                message += `<p><a href="${features.help}" target="_blank">${features.help}</p>`;
            alertify.alert(`Error ${features.code}`, message);
            return false;
        }

        //
        const elemTitle = document.getElementById('title');
        if( document.body.contains(elemTitle) )
            elemTitle.innerText = features.links[0].title;

        document.getElementById('count').getElementsByTagName("span")[0].innerText = features.numberReturned;

        for( let i in features.links ) {
            if( features.links[i].rel == 'prev' ) {
                navItems[0].href = features.links[i].href;
            }
            else if( features.links[i].rel == 'next' ) {
                const _url = new URL(features.links[i].href);
                const _params = new URLSearchParams(_url.search);
                if( _params.get('offset') < 1000 )
                    navItems[1].href = features.links[i].href;
            }
        }

        geojsonSource.addFeatures(
            new ol.format.GeoJSON().readFeatures(features, {
                featureProjection: 'EPSG:3857'
            })
        );

        renderParams(url);
        renderCode(url);
    })();
}

/**
 *
 */
function renderParams(url) {
    document.querySelector('#params tbody').innerHTML = '';
    const urlParams = new URLSearchParams(url.split("?")[1]);
    for( const entry of urlParams.entries() ) {
        document.querySelector('#params tbody').innerHTML += `<tr><td>${entry[0]}</td><td>${entry[1]}</td></tr>`;
    }
}

/**
 *
 */
function renderCode(url) {
    document.getElementsByTagName('pre')[0].innerText =
        `(async () => {
    const features = await fetch('${url}', {
        headers: {
            'Accept': 'application/geo+json',
            'key': 'INSERT_API_KEY_HERE'
        }
    }).then(response => response.json());
    // do something with features
})();`;

    document.getElementsByTagName('pre')[1].innerText =
        `url = '${url}'
header = requests.get(url, headers={'key': 'INSERT_API_KEY_HERE'})
json = header.json()
gdf = geopandas.GeoDataFrame.from_features(json['features'])`;

}

/**
 * Returns data-driven style object for the GeoJSON features.
 */
function styleFunction(feature) {
    String.prototype.convertToRGBA = function(alpha = 1.0) {
        const aRgbHex = this.slice(1).match(/.{1,2}/g);
        const aRgb = [
            parseInt(aRgbHex[0], 16),
            parseInt(aRgbHex[1], 16),
            parseInt(aRgbHex[2], 16)
        ];
        return `rgba(${aRgb[0]},${aRgb[1]},${aRgb[2]},${alpha})`;
    }

    const styleOpt = objStyle['style-options'];

    const color = objStyle.themes[objCollectionId.theme]['analytical-colors']['collections'][objCollectionId.collection];

    const geomType = feature.getGeometry().getType();

    const isPoint = function(type) {
        return [ 'Point', 'MultiPoint' ].includes(type);
    }

    const fill = new ol.style.Fill({
        color: isPoint(geomType) ? color : color.convertToRGBA(styleOpt.fill['fill-opacity'])
    });

    const stroke = new ol.style.Stroke({
        color: isPoint(geomType) ? styleOpt.circle['circle-stroke-color'].convertToRGBA(styleOpt.circle['circle-stroke-opacity']) : color,
        width: styleOpt.line['line-width']
    });

    const styles = [
        new ol.style.Style({
            image: new ol.style.Circle({
                fill: fill,
                stroke: stroke,
                radius: styleOpt.circle['circle-radius']
            }),
            fill: fill,
            stroke: stroke,
        })
    ];

    return styles;
}
