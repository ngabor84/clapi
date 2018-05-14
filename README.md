[ ![Codeship Status for ngabor84/clapi](https://app.codeship.com/projects/af733ad0-39b4-0136-f9e6-528c5e546e82/status?branch=master)](https://app.codeship.com/projects/289973)

# Clapi

Simple cli tool for API testing

## Installation
### with [phive](https://github.com/phar-io/phive)
```shell
phive install clapi
```
### manually
```shell
# download the phar file and the public key
wget https://github.com/ngabor84/clapi/releases/download/0.1.0/clapi.phar
wget https://github.com/ngabor84/clapi/releases/download/0.1.0/clapi.phar.asc

# verify the phar with gpg
gpg --keyserver pgp.mit.edu --recv-keys 0xcd54be34da0a1a97
gpg --verify clapi.phar.asc clapi.phar

# add execution permission
sudo chmod +x clapi.phar

# move the downloaded files into usr/local/bin
sudo mv clapi.phar /usr/local/bin/clapi
```

## Usage
```shell
# simple request
clapi call http://example.api/tests/1234

available options:
-X --method   Specify the http method (default is GET)
-d --payload  Add payload
-a --auth     Set authentication type (supported: basic, escher, wsse)
-u --key      Set the key for the authentication
-p --secret   Set the secret for the authentication
-s --scope    Set the scope for escher authentication
-H --header   Add custom header to request

example:
clapi call -a basic -u TestUser -p TestPass123 -X POST -d '{"key": "value"}' -H "X-Custom-Header: Test" http://example.api/tests 
```