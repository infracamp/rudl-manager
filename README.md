# rudl-manager
Manage docker swarm clusters


## Development

Create a `.kickstartconfig` inside this project.

```
## Allow Container to communicate with host docker

KICKSTART_DOCKER_RUN_OPTS="$KICKSTART_DOCKER_RUN_OPTS -v /var/run/docker.sock:/var/run/docker.sock"
```