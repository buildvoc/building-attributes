# About building-height.co.uk

Building-Hight is a system that can determine the attributes of historical buildings in England.
The building part (height, absoluteheightminimum, absoluteheightmaximum, heightconfidencelevel, etc) can be defined just by uploading a photo.

You can try to upload a photo of buildings in England, In a few seconds, the system will track detailed building attributes from the photo you uploaded. Let's try it, [building-height.co.uk](https://building-height.co.uk).

# About this repository

This repository for serving building-height.co.uk API calls. It has been developed by experienced cartographers using PHP language based on Laravel and PostGIS.


## How to use this repository

No authentication is required to access this api. You simply enter the required parameters for each endpoint. Below is a complete list of available endpoints.

- [Gallery](https://api.buildingshistory.co.uk/api/documentation#/Gallery).
- [Image](https://api.buildingshistory.co.uk/api/documentation#/Image).
- [Geo](https://api.buildingshistory.co.uk/api/documentation#/Geo).
- [SX Data](https://api.buildingshistory.co.uk/api/documentation#/SX%20Data).
- [Topography Layer SX](https://api.buildingshistory.co.uk/api/documentation#/Topography%20Layer%20SX).


## Building-Hight Open API

You can simulate all API call in this [Building-Hight Swagger API Documentation](https://api.buildingshistory.co.uk/api/documentation).


## Gallery

### [GET] /api/v1/galleries/
API endpoint to get all galleries

## Image

### [GET] /api/v1/images/
API endpoint to get all available images

## Geo

### [GET] /api/v1/geo/
API endpoint to get all geos.

### [POST] /api/v1/geo/
API endpoint to post geo.

### [GET] /api/v1/geo/nearest
API endpoint to get nearest geos.

### [POST] /api/v1/geo/upload
API endpoint to upload geo.

## SX-Data

### [GET] /api/v1/sx-data/
API endpoint to get all sx-data.

## Topography Layer SX

### [GET] /api/v1/layer-sx/
API endpoint to get all layer-sx.

### [GET] /api/v1/layer-sx/nearest
API endpoint to get nearest layer-sx data.

