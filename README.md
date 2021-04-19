# Softlead Challenge
Shortening URL application similar to TinyURL or Bitly.
### Usage
> PHP Version 7.3.27
>
> The app is working on 'localhost/URL-shortening-service/' only and any usage of other ports (such as localhost:8000, etc.) for starting the web server will result in malfunction due to the *.htaccess* that was used for implementing the routing system.
>
> **NOTE**: If the user has already shortened 5 URLs and tries to enter a new one, then he will not be allowed to do so and he will get an error message but if he tries to shorten a URL that is already in the database he will get the short URL but no other entry will be inserted into the database.
### Credentials used for connecting to the MySQL Database:
> host = "localhost"
>
> db_name = "softlead_challenge"
>
> username = "root"
>
> password = ""
> 
> **In order to create the database and the tables you just need to access 'localhost/URL-shortening-service/', for example. Check the DB credentials before in config/Database.php**
### Endpoint for listing all active URLs
> **GET** &nbsp; localhost/URL-shortening-service/process/getActiveUrlsProcess
##### Example of response:
```
{
    "success": true,
    "info": "URLs successfully retrieved.",
    "data": [
        {
            "short_url": "psxeyfw3fv",
            "long_url": "www.digi24.ro/stiri/economie/companii/o-companie-israeliana-de-drone-vrea-sa-mute-productia-unuia-dintre-modele-in-romania-1488059",
            "expiration": "2021-04-07 22:46:57"
        },
        {
            "short_url": "oingoa4h7w",
            "long_url": "digi24.ro/stiri/actualitate/oms-o-legatura-intre-vaccinul-astrazeneca-si-formarea-de-cheaguri-sangvine-este-plauzibila-dar-nu-confirmata-1488041",
            "expiration": "2021-04-07 22:47:04"
        }
    ]
}
```
### Routing system used: *[PHP Router](https://github.com/phprouter/main)*
