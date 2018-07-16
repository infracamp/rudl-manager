<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.06.18
 * Time: 14:41
 */

namespace RudlManager\Repo;


class GitRepo
{

    private $repoLocalPath;
    private $origin;
    private $originSshPrivateKey;

    public function __construct(string $repoLocalPath)
    {
        $this->repoLocalPath = $repoLocalPath;
    }

    public function setOrigin (string $repoUri)
    {
        $this->origin = $repoUri;
    }

    public function setAuthSshPrivateKey(string $privateKey)
    {
        $this->originSshPrivateKey = $privateKey;
    }

    public function isCloned() : bool
    {
        return file_exists($this->repoLocalPath. "/.git");
    }


    public function gitClone()
    {
        $cmd = "";
        if ($this->originSshPrivateKey !== null) {
            $sshKeyFile = "/tmp/id_ssh-".sha1($this->repoLocalPath);
            file_put_contents($sshKeyFile, $this->originSshPrivateKey);
            $cmd .= 'GIT_SSH_COMMAND="ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -i ' . $sshKeyFile . '" ';
        }

        $cmd .= "git clone :origin :localPath";
        return ks_exec($cmd, ["origin" => $this->origin, "localPath"=> $this->repoLocalPath]);
    }

    public function gitPull()
    {
        $cmd = "";
        if ($this->originSshPrivateKey !== null) {
            $sshKeyFile = "/tmp/id_ssh-".sha1($this->repoLocalPath);
            file_put_contents($sshKeyFile, $this->originSshPrivateKey);
            $cmd .= 'GIT_SSH_COMMAND="ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -i ' . $sshKeyFile . '" ';
        }

        $cmd .= "git -C :localPath pull";
        return ks_exec($cmd, ["localPath"=> $this->repoLocalPath]);
    }
}