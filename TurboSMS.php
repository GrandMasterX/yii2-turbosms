<?php

namespace sms\turbosms;

/**
 * Class TurboSMS
 * @package sms\turbosms
 */
class TurboSMS
{

    /**
     * @var array
     */
    protected $auth;
    /**
     * @var
     */
    protected $result;
    /**
     * @var
     */
    protected $client;
    /**
     * @var
     */
    protected $sms;
    /**
     * @var string
     */
    protected $signature;
    /**
     * @var string
     */
    protected $login = '';
    /**
     * @var string
     */
    protected $password = '';

    /**
     *
     */
    function __construct() {
        $this->signature = '';
        $this->auth = [
            'login' => $this->login,
            'password' => $this->password
        ];
    }

    /**
     * @param $number
     * @param $text
     * @return mixed
     */
    public function SendSms($number, $text)
    {

        header('Content-type: text/html; charset=utf-8');
        $this->client = new SoapClient ('http://turbosms.in.ua/api/wsdl.html');

        // autorize on server
        $this->result = $this->client->Auth($this->auth);
        // auth result
        //echo $this->result->AuthResult . '';

        // get number of allowed credits
        $this->result = $this->client->GetCreditBalance();

        //echo $result->GetCreditBalanceResult . '';

        // message MUST BE in UTF-8
        //$text = iconv ('windows-1251', 'utf-8', 'message');

        // data for send
        $this->sms = [
            'sender' => $this->signature,
            'destination' => $number,
            'text' => $text
        ];

        // send message for 1 number
        // author signature could containe letters and symbols. Allowed length - 11 символов.
        // Number must be FULL! With country and state code and with plus (example +3806666666)
        $this->result = $this->client->SendSMS($this->sms);

        // get send result
        return $this->result->SendSMSResult->ResultArray[0];

    }
}
