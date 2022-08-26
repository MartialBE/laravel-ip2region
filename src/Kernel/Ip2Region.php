<?php
namespace Martialbe\LaravelIp2region\Kernel;

use Martialbe\LaravelIp2region\Kernel\XdbSearcher;

class Ip2Region  
{
    /**
     * @var string
     */
    private $dbPath;

    /**
     * @var \Martialbe\LaravelIp2region\Kernel\XdbSearcher
     */
    private $xdbObj;

    /**
     * @var string
     */
    private $region;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $area;

    /**
     * @var string
     */
    public $state;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $isp;

    /**
     * @param string $path
     */
    public function __construct($path = null)
    {
       $this->dbPath = $path ?: __DIR__.'/../db/ip2region.xdb';
    }

    public function setIndex($index)
    {
        $this->xdbObj = XdbSearcher::newWithVectorIndex($this->dbPath, $index);
        return $this;
    }

    public function setDbcache($dbcache)
    {
        $this->xdbObj = XdbSearcher::newWithBuffer($dbcache);
        return $this;
    }

    /**
     * @param string $ip
     * @return self
     */
    public function ip(string $ip)
    {
        $this->region = $this->getXdbObj()->search($ip);
        if($this->region) {
            $regionArr = explode("|", $this->region);
            $this->country = $regionArr[0] ?: "";
            $this->area    = $regionArr[1] ?: "";
            $this->state   = $regionArr[2] ?: "";
            $this->city    = $regionArr[3] ?: "";
            $this->isp     = $regionArr[4] ?: "";
        }

        return $this;
    }

    /**
     * @return \Martialbe\LaravelIp2region\Kernel\XdbSearcher
     */
    private function getXdbObj()
    {
        if(!$this->xdbObj){
            $this->xdbObj = XdbSearcher::newWithFileOnly($this->dbPath);
        }

        return $this->xdbObj;
    }

    /**
     * 获取索引
     *
     */
    public function loadVectorIndexFromFile()
    {
        return XdbSearcher::loadVectorIndexFromFile($this->dbPath);
    }

    /**
     * 获取数据库缓存
     *
     */
    public function loadContentFromFile()
    {
        return XdbSearcher::loadContentFromFile($this->dbPath);
    }

    public function getDbPath()
    {
        return $this->dbPath;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            "country" => $this->country,
            "area"    => $this->area,
            "state"   => $this->state,
            "city"    => $this->city,
            "isp"     => $this->isp,
        ];
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->region;
    }

    public function __toString()
    {
        return $this->toString();
    }

}
