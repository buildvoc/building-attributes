import './bootstrap';
import * as osdatahub from "osdatahub";

const apiKey = import.meta.env.VITE_OS_API_KEY;
const collectionId = import.meta.env.VITE_OS_COLLECTION_ID;


// get input with query id
const queryInput = document.getElementById('query');
const searchForm = document.getElementById('search-form');

// add event listener to search button
searchForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const query = queryInput.value;

    osdatahub.ngd.features(apiKey, collectionId, {filter: query})
        .then(function (response) {
            console.log(response);

            // fill table with results
        })
        .catch(function (error) {
            alert(error);
        });
});

// async function find(query)
// {
//     axios.get(`https://api.os.uk/search/names/v1/find?query=${query}&key=${apiKey}`)
//         .then(function (response) {
//             /* For explanation and debugging purposes we display the full response from the API in the console */
//             console.log(JSON.stringify(response.data, null, 2));
//         });
// }
