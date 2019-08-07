# Viewer Authentication API demo

[Viewer Authentication API](https://developers.video.ibm.com/channel-api/viewer-authentication-api.html) 
lets you implement custom authentication for authenticating viewers of live and recorded videos served by IBM Video Streaming.

## Requirements

* PHP >= 7.0
* IBM Video Streaming account
* Authentication settings for Viewer Authentication API

## Installation

1. Download [Composer](https://getcomposer.org/download)
  

3. Download dependencies with Composer:

  ```shell
  php composer.phar install
  ```

## Usage
The authentication screen is the app's main page. The /auth endpoint does the authentication. 
Valid email address with the password 'ibm' is accepted, otherwise the authentication will
fail.

## License

This project is licensed under the terms of the [MIT License (MIT)](LICENSE).
