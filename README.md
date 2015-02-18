README
======

What is MediaManagerBundle ?
-----------------------------

Bundle of Media Manager to manage all media in a project.


Requirement
-------------

1. [MediaManager (Component)](https://github.com/CanalTP/MediaManagerComponent)

Installation
-------------

You need composer to install the MediaManagerComponent.

1. Open your composer.json in your project
2. Add require "canaltp/media-manager": "dev-master"
4. Please don't forget to set "post_max_size", "upload_max_filesize" and "max_file_uploads" options in your php.ini
5. Add configuration in your app/config/ __(not required)__

    // config.yml
    canal_tp_media_manager:
        configurations:
            MyApplicationId:
                name: MyApplicationName
                storage:
                    type: filesystem
                    path: /my/storage/path/
                    url:  http://my-medias.local/
                strategy: CanalTP\MediaManager\Strategy\DefaultStrategy


How to use MediaManagerBundle ?
--------------------------------

__Coming Soon__

Running MediaManagerBundle Tests
---------------------------

__Coming Soon__

Contributing
-------------

1. Rémy Abi-Khalil - remy.abikhalil@canaltp.fr
