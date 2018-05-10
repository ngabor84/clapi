# Clapi

Simple cli tool for API testing

## Installation
```shell
# clone the git repository
git clone https://github.com/ngabor84/clapi.git clapi

# enter to the directory where you cloned the repo
cd clapi

# install the dependecies with composer
composer install

# build the php archive
make build

# copy the phar into usr/local/bin
cp bin/clapi.phar /usr/local/bin/clapi
cp bin/clapi.phar.pubkey /usr/local/bin/clapi.pubkey

# add execution permission
sudo chmod a+x /usr/local/bin/clapi
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