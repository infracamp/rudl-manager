# rudl-manager
Manage docker swarm clusters

- [InfraCamp Homepage](http://infracamp.org)
- Configuration stored in git repository
- Provision services
- Event Logging and Notification
- Manage SSL-Certificates



## Development

Create a `.kickstartconfig` inside this project.

```
## Allow Container to communicate with host docker

KICKSTART_DOCKER_RUN_OPTS="$KICKSTART_DOCKER_RUN_OPTS -v /var/run/docker.sock:/var/run/docker.sock"
```


## Starting

- Generate a ssh pub/privatekey pair.
- Run the service and pass the Repository URL and the tag to checkout
- Pass a SECRET
- Specify subfolder for cloud config

## Generate a Git Repository

It will contain all the config files.
```


```

Add the 


## Config

### `cluster.json`

```
{
    hostname: "cluster.name.tld",
    host_ips: [
        "183.204.22.13"
    ],
    access_keys: [
        "wurst",
        "rotate_wurst"
    ],
    allow_users: [
        "admin:encryptedPassword:0.0.0.0/0
    ],
    provision_services: {
        
    }
}
```
Achtung: Access-keys: Es gibt immer mehrere, damit rotiert werden kann.
Der erste eintrag ist immer der aktuellste.

### Frontend

Für jeden Service wird eine Datei angelegt

`conf.d/<serviceA>.yml`
```
service:
    

```


## Letsencrypt integration

Cloudfront-Services must proxy all Requests to `/.acme/challange/*` to `http://rudl-manager/.acme/callange`.

Certs can be downloaded 








