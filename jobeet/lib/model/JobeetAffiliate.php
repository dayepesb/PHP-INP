<?php

class JobeetAffiliate extends BaseJobeetAffiliate
{
    public function activate()
    {
        $this->setIsActive(true);

        return $this->save();
    }

    public function deactivate()
    {
        $this->setIsActive(false);

        return $this->save();
    }

    public function save(PropelPDO $con = null)
    {
        if (!$this->getToken()) {
            $this->setToken(sha1($this->getEmail() . rand(11111, 99999)));
        }

        return parent::save($con);
    }

    public function __toString()
    {
        return $this->getUrl();
    }
}
