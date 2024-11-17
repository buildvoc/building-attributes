# About building-height.co.uk

Building-Height is a system that can determine the attributes of historical buildings in England.
The building part (height, absoluteheightminimum, absoluteheightmaximum, heightconfidencelevel, etc) can be defined just by uploading a photo.

You can try to upload a photo of buildings in England, In a few seconds, the system will track detailed building attributes from the photo you uploaded. Let's try it, [building-height.co.uk](https://building-height.co.uk).

1. Upload an image
You can upload or capture from your camera with active GPS

1. Image metadata will be displayed
Extracted metadata from the image you uploaded will be displayed
![image](https://github.com/buildvoc/building-attributes/assets/76884997/21f30cec-8514-4a37-a5f7-4799fb6e996c)

1. Building height will be identified
The building height, map location of the building and its attributes will be displayed
![image](https://github.com/buildvoc/building-attributes/assets/76884997/dd625f81-0e23-483d-af12-0636372e084c)

# About this repository

This repository for serving building-height.co.uk API calls. It has been developed by experienced cartographers using PHP language based on Laravel and PostGIS.


## How to use this repository

No authentication is required to access this api. You simply enter the required parameters for each endpoint. Below is a complete list of available endpoints.

You can simulate all API call in this [Building-Height Swagger API Documentation](https://api.buildingshistory.co.uk/api/documentation).

### Version: 1.0.0

### https://api.buildingshistory.co.uk/api/v1/building-part/

#### GET
##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | List of building part |

### https://api.buildingshistory.co.uk/api/v1/building-part/nearest

#### GET
##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| latitude | query |  | Yes | double |
| longitude | query |  | Yes | double |
| distance | query |  | No | double |
| imagedirection | query |  | No | double |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Get nearest building part |

### https://api.buildingshistory.co.uk/api/v1/galleries/

#### GET
##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| name | query |  | No | string |
| slug | query |  | No | string |
| created_at | query |  | No | date |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Get all galleries |

### https://api.buildingshistory.co.uk/api/v1/galleries/to-brick-collections-format

#### GET
##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Get all galleries and show to_brick collections format |

### https://api.buildingshistory.co.uk/api/v1/galleries/sync

#### GET
##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Sync all galleries with brick_by_brick collections |

### https://api.buildingshistory.co.uk/api/v1/images/

#### GET
##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| gallery_id | query |  | No | string |
| filename | query |  | No | string |
| description | query |  | No | string |
| long_description | query |  | No | string |
| exif_data_latitude | query |  | No | string |
| exif_data_longitude | query |  | No | string |
| exif_data_altitude | query |  | No | string |
| exif_data_focal_length | query |  | No | string |
| exif_data_iso | query |  | No | string |
| exif_data_taken_at | query |  | No | date |
| exif_data_gps_img_direction | query |  | No | string |
| exif_data_gps_longitude_ref | query |  | No | string |
| exif_data_gps_latitude_ref | query |  | No | string |
| created_at | query |  | No | date |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Get all images |

### https://api.buildingshistory.co.uk/api/v1/images/to-brick-items-format

#### GET
##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| gallery_id | query |  | No | string |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Get all images and show to_brick items format |

### https://api.buildingshistory.co.uk/api/v1/images/sync

#### GET
##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Sync all images to brick_by_brick items |

### https://api.buildingshistory.co.uk/api/v1/geo/

#### GET
##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| minEasting | query |  | Yes | double |
| minNorthing | query |  | Yes | double |
| maxEasting | query |  | Yes | double |
| maxNorthing | query |  | Yes | double |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Get all geos |

#### POST
##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| geom | query |  | Yes | string |
| osid | query |  | Yes | string |
| toid | query |  | Yes | string |
| height_max | query |  | Yes | string |
| symbol | query |  | Yes | string |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Post Geo |

### https://api.buildingshistory.co.uk/api/v1/geo/nearest

#### GET
##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| latitude | query |  | Yes | double |
| longitude | query |  | Yes | double |
| radius | query |  | No | double |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Get nearest geos with radius |

### https://api.buildingshistory.co.uk/api/v1/geo/upload/

#### POST
##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| geom | query |  | Yes | string |
| osid | query |  | Yes | string |
| toid | query |  | Yes | string |
| height_max | query |  | Yes | string |
| symbol | query |  | Yes | string |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Upload Geo |

### https://api.buildingshistory.co.uk/api/v1/sx-data/

#### GET
##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Get all SX Data |

### https://api.buildingshistory.co.uk/api/v1/layer-sx/

#### GET
##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Get all Topography Layer SX Data |

### https://api.buildingshistory.co.uk/api/v1/layer-sx/nearest

#### GET
##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| latitude | query |  | Yes | double |
| longitude | query |  | Yes | double |
| radius | query |  | No | double |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Get nearest SX with radius |


## Building-Height Open API

You can simulate all API call in this [Building-Height Swagger API Documentation](https://api.buildingshistory.co.uk/api/documentation).


