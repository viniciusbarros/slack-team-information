<?php

namespace TeamInfo;

/**
 * Class SlackRetriever
 * @package TeamInfo
 *
 * Author: Vinicius Barros
 *
 */
class SlackRetriever
{

    private $token;
    private $getUsersMethod = 'users.list';
    private $slackRequestUrl = 'https://slack.com/api/';

    public function __construct($token)
    {
        $this->token = $token;
    }


    public function getUsers()
    {
        $url = $this->slackRequestUrl . $this->getUsersMethod . '?token=' . $this->token . '&pretty=1';
        $request_type = defined('REQUEST_METHOD') ? REQUEST_METHOD : 'POST';
        $return = $this->performRequest($url, $request_type);

        if (isset($return['error'])) {
            echo '<span style="color:red">An error was found: "' . $return['error'] . '"</span>';
            echo '<pre><strong style="color:#4149ff; background-color:#e7f6ff; font-size:16px;">', __FILE__, ' on line ', __LINE__, ' (' . date("d/m/Y H:i:s", filectime(__FILE__)) . ')</strong><fieldset style="background-color: #F2F8FF;">';
            print_r($return);
            echo '<hr>';
            debug_print_backtrace();
            die('</pre>');
        }

        return $return;
    }

    /**
     * @param        $url
     * @param string $type
     * @param array  $post_fields
     *
     * @return mixed
     */
    private function performRequest($url, $type = 'GET', $post_fields = array())
    {
        $curl = curl_init();

        $options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        );

        if (strtoupper($type) == 'POST') {
            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_POSTFIELDS] = $post_fields;
        }

        //Set options
        curl_setopt_array($curl, $options);

        $resp = curl_exec($curl);

        if (!$resp) {
            $return = false;
        } else {
            $result = json_decode($resp, true);
            $return = $result;
        }

        curl_close($curl);

        return $return;
    }

    /**
     * @param string $apiMethod
     *
     * @return mixed
     */
    public function request($apiMethod, $parameters = array(), $requestType = 'POST')
    {
        $url = $this->slackRequestUrl . $apiMethod . '?token=' . $this->token . '&pretty=1';
        if (!in_array(strtoupper($requestType), array('POST', 'GET'))) {
            $request_type = 'POST';
        }

        return $this->performRequest($url, $requestType, $parameters);
    }

}