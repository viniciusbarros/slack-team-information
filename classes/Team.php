<?php

namespace TeamInfo;

use TeamInfo\Person;

/**
 * User: Vinicius
 * Date: 04/05/2016
 */
class Team
{
    private $members = array();

    public function __construct($slackInfo)
    {

    }

    /**
     * @return array
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param array $members
     */
    public function setMembers($members)
    {
        $this->members = $members;
    }

    public function getUser($id){
        $return = false;
        foreach($this->members as $member){
            if($member['id'] == $id){
                $return = new Person($member);
                break;
            }
        }

        return $return;
    }



}