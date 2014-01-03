<?php
namespace Machigai\GameBundle\Entity;

class User
{
    protected $nickname;

    protected $currentPoint;

    protected $createdAt;

    protected $updatedAt;

    public function getNickname()
    {
        return $this->nickname;
    }

    public function setNickname($nickname)
    {
        $this->name = $nickname;
    }

    public function getCurrentPoint()
    {
        return $this->currentPoint;
    }

    public function setCurrentPoint($currentPoint)
    {
        $this->currentPoint = $currentPoint;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    protected function setCreatedAt()
    { //TODO: implemente Here!
      //  $this->createdAt = $;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    protected function setUpdatedAt()
    { //TODO: implement Here!
      // $this->updatedAt = ;
    }
}
