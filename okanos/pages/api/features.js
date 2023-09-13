import * as osdatahub from 'osdatahub';

const env = process.env;

export default function handler(req, res) {
    osdatahub.ngd.features(env.OS_API_KEY, env.OS_COLLECTION_ID)
        .then(function (response) {
            res.status(200).json(response);
        })
        .catch(function (error) {
            console.log(error);

            res.status(500).json(error);
        });
}