#!/usr/bin/env python3

import os


if __name__ == "__main__":
    PATH = "storage/app/public/data"
    images = [i for i in os.listdir(PATH) if str(i).lower().endswith('jpg')]
    geojson = [i for i in os.listdir(PATH) if str(i).lower().endswith('geojson')]

    print({
        "images": images,
        "geojson": geojson
    })