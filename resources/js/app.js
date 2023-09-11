import './bootstrap';
import * as osdatahub from "osdatahub";

const apiKey = "GE4Viu5Q2kB6jnFzoPnr3uGXhGwmm3iZ"

async function find() {
    axios.get('https://api.os.uk/search/names/v1/find?query=Southampton&key=' + apiKey)
        .then(function (response) {
            /* For explanation and debugging purposes we display the full response from the API in the console */
            console.log(JSON.stringify(response.data, null, 2));
        });
}

find();
