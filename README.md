# rudl-manager

Manage docker swarm clusters. Instead of offering a complex frontend,
ruld-manager will clone a configuration repository and update the
cluster configuration along these config-files.

## [Setting up rudl-manager](doc/setup/setup.md)

- Development: Setup a docker swarm manager node by running
  ```
  sudo docker swarm init
  ```

- Production: You must define some Secrets. See our setup-guide
  [Setup Guide for production environments](doc/setup/production.md)








## Development

Create a `.kickstartconfig` inside this project.

```
## Allow Container to communicate with host docker

KICKSTART_DOCKER_RUN_OPTS="$KICKSTART_DOCKER_RUN_OPTS -v /var/run/docker.sock:/var/run/docker.sock"
```



