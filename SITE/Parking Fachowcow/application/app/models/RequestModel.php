<?php

namespace app\models;

use app\libs\PDOHandler as pdo;
use app\core as core;

class RequestModel extends core\Model
{

    private $sql;

    function __construct()
    {
        $this->sql = new pdo\PDOHandler;
    }

    public function addRequest(array $requestInfo, array $requestData)
    {
        $date = $requestInfo['date'];
        $ip = $requestInfo['ip'];
        $country = $requestInfo['country'];
        $utmData = $requestInfo['utmData'];

        $addRequest = $this->sql->newQuery()->insert("applications", ["date", "client_ip", "country"], "'$date', '$ip', '$country'")->doQuery();

        $lastId = $this->sql->newQuery()->select('id')
                                        ->from('applications')
                                        ->order('id')
                                        ->limit(1, true)
                                        ->doQuery();

        $lastId = $lastId[0]['id'];

        if($addRequest and $utmData)
        {
            $utmAssocArr = [];

            foreach ($utmData as $utm)
            {
                $utmAssocArr[$utm[0]] = $utm[1];
            }

            $addUtmRequest = $this->sql->newQuery()->insert("applications_utm", ["application_id", "utm_source", "utm_medium", "utm_campaign", "utm_content", "utm_term"], "'$lastId', '$utmAssocArr[utm_source]', '$utmAssocArr[utm_medium]', '$utmAssocArr[utm_campaign]', '$utmAssocArr[utm_content]', '$utmAssocArr[utm_term]'")->doQuery();
        }
        
        if($addRequest)
        {

            foreach($requestData as $key => $field)
            {
                $result = $this->sql->newQuery()->insert("applications_values", ["application_id", "key", "value"], "$lastId, '$key', '$field'")->doQuery();
                if(!$result)
                {
                    switch (strtolower($requestData['lang'])) {
                        case 'ua':
                        case 'uk':
                        case 'ukr':
                            return REQ_ERROR_VALID_UA;
                            break;
                        case 'pl':
                        case 'pol':
                            return REQ_ERROR_VALID_PL;
                            break;
                        case 'en':
                        case 'eng':
                        case 'gb':
                            return REQ_ERROR_VALID_EN;
                            break;
                        default:
                            return REQ_ERROR_VALID_RU;
                    }
                }
            }

            if (TY_PAGE_ENABLE)
            {
                header("Location: " . TY_PAGE_PATH);
                return;
            }

            switch (strtolower($requestData['lang'])) {
                case 'ua':
                case 'uk':
                case 'ukr':
                    return REQ_SUCCESS_UA;
                    break;
                case 'pl':
                case 'pol':
                    return REQ_SUCCESS_PL;
                    break;
                case 'en':
                case 'eng':
                case 'gb':
                    return REQ_SUCCESS_EN;
                    break;
                default:
                    return REQ_SUCCESS_RU;
            }
        }

        switch (strtolower($requestData['lang'])) {
            case 'ua':
            case 'uk':
            case 'ukr':
                return REQ_ERROR_UA;
                break;
            case 'pl':
            case 'pol':
                return REQ_ERROR_PL;
                break;
            case 'en':
            case 'eng':
            case 'gb':
                return REQ_ERROR_EN;
                break;
            default:
                return REQ_ERROR_RU;
        }
    }

    public function sendEmailRequest(array $requestInfo, array $requestData)
    {
        $email = $this->sql->newQuery()->select('email')
                                        ->from('users')
                                        ->order('email')
                                        ->limit(1, true)
                                        ->doQuery();

        $email = $email[0]['email'];

        $utmData = $requestInfo['utmData'];

        $msg_content = "
            <h2 align='center' style='color: #333333; font: Arial, sans-serif; line-height: 30px; -webkit-text-size-adjust:none;'>Заявка с сайта " . $_SERVER['HTTP_HOST'] . "!</h2><br /><hr>
            <h3 align='center' style='color: #333333; font: Arial, sans-serif; line-height: 30px; -webkit-text-size-adjust:none;'>Данные клиента:</h3>
            <p style='color: #333333; font: Arial, sans-serif; -webkit-text-size-adjust:none;'>Заявка отравлена: $requestInfo[date];<br />
            Страна: $requestInfo[country]<br />
        ";

        foreach($requestData as $key => $field)
        {
            $msg_content .= "$key: $field <br />";
        }

        if ($utmData)
        {
            $msg_content .= "<hr><h3 align='center' style='color: #333333; font: Arial, sans-serif; line-height: 30px; -webkit-text-size-adjust:none;'>Данные по UTM-метке</h3><br />";
            foreach($utmData as $data)
            {
                $msg_content .= "$data[0]: $data[1] <br />";
            }
        }

        $send = mail ('mail@parkingfachowcow.eu', REQ_SUBJECT, $msg_content,"Content-type:text/html; charset = utf-8", REQ_WHO_MAILED);
    }

    public function getAllRequests()
    {
        $requests = $this->sql->newQuery()->select("id, date, is_checked, client_ip, country")
                                          ->from("applications")
                                          ->doQuery();

        if($requests)
        {
            $result = ['data' => $requests, 'status' => 'success'];
        } else {
            $result = ['status' => 'error'];
        }

        return $result;
    }

    public function getRequestById($id)
    {
        $request = $this->sql->newQuery()->select("date, client_ip, country")
                                         ->from("applications")
                                         ->where("id=$id")
                                         ->doQuery();

        $request = $request[0];

        $requestData = $this->sql->newQuery()->select("key, value")
                                             ->from("applications_values")
                                             ->where("application_id=$id")
                                             ->doQuery();

        $requestUtm = $this->sql->newQuery()->select("utm_source, utm_medium, utm_campaign, utm_content, utm_term")
                                            ->from("applications_utm")
                                            ->where("application_id=$id")
                                            ->doQuery();

       

        if ($request and $requestData)
        {
            foreach($requestData as $value)
            {
                $request[$value['key']] = $value['value'];
            }

        }

        if ($request and is_array($requestUtm))
        {
            foreach ($requestUtm[0] as $key => $value)
            {
                if($requestUtm[0][$key])
                {
                    $request[$key] = $value;
                }
            }
        }

        if ($request and $requestData)
        {
            $result = ['data' => $request, 'status' => 'success'];
        } else {
            $result = ['status' => 'error'];
        }

        return $result;
    }

    public function checkedApp($id)
    {
        $request = $this->sql->newQuery()->update("applications", ["is_checked"], ["'true'"], "id=$id")->doQuery();
        if ($request)
        {
            return ['status' => 'success'];
        }

        return ['status' => 'error'];
    }

}