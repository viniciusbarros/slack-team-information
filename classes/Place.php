<?php
namespace TeamInfo;

/**
 * User: Vinicius
 * Date: 04/05/2016
 */
class Place
{
    private $name;
    private $displayIndex;
    private $alias = array();
    private $people = array();
    private $searchable = true;

    public function __construct($name = '', $displayIndex = 0, $alias = array(),$searchable=true)
    {
        $this->setName($name);
        $this->setDisplayIndex($displayIndex);
        $this->setAlias($alias);
        $this->setSearchable($searchable);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getDisplayIndex()
    {
        return $this->displayIndex;
    }

    /**
     * @param int $displayIndex
     * @return $this
     */
    public function setDisplayIndex($displayIndex)
    {
        $this->displayIndex = (int)$displayIndex;
        return $this;
    }

    /**
     * @return array
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param array $alias
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @return array
     */
    public function getPeople()
    {
        return $this->people;
    }

    /**
     * @param array $person
     * @return $this
     */
    public function addPerson($person = array())
    {
        if(!empty($person)){
            $this->people[] = $person;
        }
        return $this;

    }

    /**
     * @return boolean
     */
    public function isSearchable()
    {
        return $this->searchable;
    }

    /**
     * @param boolean $searchable
     * @return $this
     */
    public function setSearchable($searchable)
    {
        $this->searchable = $searchable;
        return $this;
    }

    /**
     * Checks if a person should be in this group
     *
     * @param string $name
     *
     * @return bool
     */
    public function checkPerson($name){
        $pattern = "/(" . strtolower(implode('|', $this->getAlias())) . ")/";
        preg_match($pattern, strtolower($name), $matches);
        $ret = (isset($matches[1]) && !empty($matches[1]));
        if (!$ret){
//            echo $name,' Not found in ', strtolower(implode('|', $this->getAlias())), '<br>';
        }else{
//            echo $name,' Found in ',  $this->getName(), '<br>';
        }
        return $ret;
    }



}