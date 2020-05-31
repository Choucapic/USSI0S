<?php

require_once '../autoload.inc.php';

class cards
{
    private $uuid;

    private $name;

    private $rarity;

    private $scryfallId;

    private $scryfallIllustrationId;

    private $setcode;

    private $setLogo;

    private $hasFoil;

    private $hasNonFoil;

    private $priceFoil;

    private $priceNonFoil;

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getRarity()
    {
        return $this->rarity;
    }

    /**
     * @return mixed
     */
    public function getScryfallId()
    {
        return $this->scryfallId;
    }

    /**
     * @return mixed
     */
    public function getScryfallIllustrationId()
    {
        return $this->scryfallIllustrationId;
    }

    /**
     * @return mixed
     */
    public function getSetcode()
    {
        return $this->setcode;
    }

    /**
     * @return mixed
     */
    public function getSetLogo()
    {
        return $this->setLogo;
    }

    /**
     * @return mixed
     */
    public function getHasFoil()
    {
        return $this->hasFoil;
    }

    /**
     * @return mixed
     */
    public function getHasNonFoil()
    {
        return $this->hasNonFoil;
    }

    /**
     * @return mixed
     */
    public function getPriceFoil()
    {
        return $this->priceFoil;
    }

    /**
     * @return mixed
     */
    public function getPriceNonFoil()
    {
        return $this->priceNonFoil;
    }


}