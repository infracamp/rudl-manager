# Setup rudl-manager

## Provision a docker swarm (single node instance)

```
sudo docker swarm init
```

*(If you have multiple IP's assigned to your network interface, you
might need to specify the advertise address the swarm manager listens
to by  adding `--advertise-addr xxx.xxx.xxx.xxx`)*

