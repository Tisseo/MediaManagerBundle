README
======

What is MediaManagerBundle ?
-----------------------------

Bundle of Media Manager to manage all media in a project.
Dependence:

Requirements
-------------

1. [MediaManager (Component)](http://hg.prod.canaltp.fr/ctp/MediaManager.git/summary)
2. [NavitiaComponent](http://hg.prod.canaltp.fr/ctp/NavitiaComponent.git/summary)

Installation
-------------

You need composer to install the MediaManager.

1. Open your composer.json in your project
2. Add require "canaltp/media-manager": "dev-master"
2. Add require "canaltp/navitia": "dev-master"
3. Add url of the repository, 'http://packagist.canaltp.fr'
4. Please don't forget to set "post_max_size", "upload_max_filesize" and "max_file_uploads" options in your php.ini
5. Add configuration in your app/config/ __(not required)__

    // config.yml
    canal_tp_media_manager:
        tmp_dir: "/path/to/tmp"
        company_path: "/path/to/company.yml"
        navitia_path: "/path/to/navitia.yml"

    // company.yml
    name: "CanalTP"
    storage:
        type: "filesystem"
        path: "/tmp/my_storage/"
    strategy: "default"

    // navitia.yml
    url: "http://navitia2-ws.ctp.dev.canaltp.fr"
    format: "object"

    // composer.json
    {
        ...
        "require": {
            ...
            "canaltp/media-manager": "dev-master",
            "canaltp/navitia": "dev-master"
        },
        "repositories": [
            {
                "type": "composer",
                "url": "http://packagist.canaltp.fr"
            },
            ...
        ],
        ...
    }

How to use MediaManagerBundle ?
--------------------------------

__Coming Soon__

Running MediaManagerBundle Tests
---------------------------

__Coming Soon__

Contributing
-------------

1. Rémy Abi-Khalil - remy.abikhalil@canaltp.fr
