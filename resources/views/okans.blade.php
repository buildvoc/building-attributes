
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>OS NGD API – Features | Examples | Collections</title>
    <link rel="icon" type="image/x-icon" href="/os-favicon.ico" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i" />
    <link rel="stylesheet" href="https://labs.os.uk/public/os-api-branding/v0.3.1/os-api-branding.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v7.3.0/ol.css" />
    <link rel="stylesheet" href="okans/style.css" />
</head>
<body>
<div class="header">
    <div class="logo">
        <img src="okans/static/media/os-logo-charcoal.svg" alt="Ordnance Survey logo">
    </div>
</div>
<div class="container">
    <h3>OS NGD API – Features</h3>
    <h4>Retrieve items from a feature collection</h4>
    <select id="collections" name="collections"></select>
    <input id="bbox" name="bbox" type="checkbox" checked disabled>
    <label for="bbox">bbox</label>
    <button id="requery">Requery</button>
    <span class="loader"><div></div></span>
    <div>
        <input id="filter" name="filter" type="checkbox">
        <label for="filter">filter</label>
        <input type="text" id="cql" name="cql" size="36" disabled>
    </div>
    <div id="description"></div>
    <div id="href"><a href="#" target="_blank"></a></div>
    <div id="params">
        <table>
            <thead><tr><th>KEY</th><th>VALUE</th></tr></thead>
            <tbody></tbody>
        </table>
    </div>
    <div id="map" tabindex="-1"></div>
    <div id="popup" class="ol-popup">
        <a href="#" id="popup-closer" class="ol-popup-closer"></a>
        <div id="popup-content"></div>
    </div>
    <div id="count">Features returned: <span>0</span></div>
    <div id="links"><a class="nav">prev</a> | <a class="nav">next</a></div>
    <div>JavaScript:<pre></pre></div>
    <div>Python (GeoPandas):<pre></pre></div>
    <h4>What next?</h4>
    <div>For further reading on the OS NGD API – Features, refer to the <a href="#" target="_blank">technical specification</a>.</div>
</div>

<script src="https://labs.os.uk/public/os-api-branding/v0.3.1/os-api-branding.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/ol@v7.3.0/dist/ol.js"></script>
<script src="okans/public/osngd/config.js"></script>
<script src="okans/main.js"></script>
<script>

    let collectionId = 'bld-fts-buildingpart-1';

    const center = [ -3.541809, 50.727589 ];
    const zoom = 18;

    let objCollections;

    (async () => {
        const json = await fetch(`${config.ofaServiceUrl}/collections`).then(response => response.json());

        const collectionIds = json.collections.map(function(key, index) { return key.id });

        collectionIds.forEach(function(val, i) {
            const el = document.createElement('option');
            el.textContent = val;
            el.value = val;

            document.getElementById('collections').append(el);
        });

        objCollections = json;

        document.getElementById('collections').value = collectionId;

        update();
    })();

    // Add an event listener to handle when the user changes the collections drop-down.
    document.getElementById('collections').addEventListener('change', function(event) {
        collectionId = event.target.value;
        document.getElementById('filter').checked = false;
        document.getElementById('cql').disabled = true;
        update();
    });

    // Add an event listener to handle when the user clicks the 'Requery' button.
    document.getElementById('requery').addEventListener('click', function() {
        update();
    });

    // Add an event listener to handle when the user clicks the filter checkbox.
    document.getElementById('filter').addEventListener('change', function() {
        document.getElementById('cql').disabled = !this.checked;
    });

    //
    const navItems = document.querySelectorAll('.nav');
    navItems.forEach(element => {
        element.addEventListener('click', (e) => {
            e.preventDefault();
            if( e.target.hasAttribute('href') )
                request(e.target.href);
        });
    });

    /**
     *
     */
    function process(bool) {
        document.getElementById('collections').disabled = bool;
        document.getElementById('requery').disabled = bool;
        document.querySelectorAll('.loader div')[0].style.display = bool ? 'inline-block' : 'none';
        document.getElementById('filter').disabled = bool;
        document.getElementById('cql').disabled = bool ? bool : !document.getElementById('filter').checked;;
    }

    init();

</script>

</body>
</html>
