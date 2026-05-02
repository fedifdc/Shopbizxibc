<?php

    class SmsGateway {

        static $baseUrl = "https://smsgateway.me";


        // function __construct($email,$password) {
        //     $this->email = $email;
        //     $this->password = $password;
        // }
        function __construct($token) {
            $this->token = $token;
        }

        function createContact ($name,$number) {
            return $this->makeRequest('/api/v3/contacts/create','POST',['name' => $name, 'number' => $number]);
        }

        function getContacts ($page=1) {
           return $this->makeRequest('/api/v3/contacts','GET',['page' => $page]);
        }

        function getContact ($id) {
            return $this->makeRequest('/api/v3/contacts/view/'.$id,'GET');
        }


        function getDevices ($page=1)
        {
            return $this->makeRequest('/api/v3/devices','GET',['page' => $page]);
        }

        function getDevice ($id)
        {
            return $this->makeRequest('/api/v3/devices/view/'.$id,'GET');
        }

        function getMessages($page=1)
        {
            return $this->makeRequest('/api/v3/messages','GET',['page' => $page]);
        }

        function getMessage($id)
        {
            return $this->makeRequest('/api/v3/messages/view/'.$id,'GET');
        }

        function sendMessageToNumber($to, $message, $device, $options=[]) {
            $query = array_merge(['phone_number'=>$to, 'message'=>$message, 'device_id' => $device], $options);
            return $this->makeRequest('/api/v4/message/send','POST',$query);
        }

        function sendMessageToManyNumbers ($to, $message, $device, $options=[]) {
            $query = array_merge(['number'=>$to, 'message'=>$message, 'device' => $device], $options);
            return $this->makeRequest('/api/v3/messages/send','POST', $query);
        }

        function sendMessageToContact ($to, $message, $device, $options=[]) {
            $query = array_merge(['contact'=>$to, 'message'=>$message, 'device' => $device], $options);
            return $this->makeRequest('/api/v3/messages/send','POST', $query);
        }

        function sendMessageToManyContacts ($to, $message, $device, $options=[]) {
            $query = array_merge(['contact'=>$to, 'message'=>$message, 'device' => $device], $options);
            return $this->makeRequest('/api/v3/messages/send','POST', $query);
        }

        function sendManyMessages ($data) {
            $query['data'] = $data;
            return $this->makeRequest('/api/v3/messages/send','POST', $query);
        }

        private function makeRequest ($url, $method, $fields=[]) {

            // $fields['email'] = $this->email;
            // $fields['password'] = $this->password;
            $headr[] = 'Accept:application/json';
            $headr[] = 'Content-Type:application/json';
            $headr[] = 'Authorization:'.$this->token;

            $url = smsGateway::$baseUrl.$url;

            $fieldsString = http_build_query($fields);


            $ch = curl_init();

            if($method == 'POST')
            {
                // curl_setopt($ch,CURLOPT_POST, count($fields));
                // curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($messages));
                curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode(array($fields)) );
            }
            else
            {
                $url .= '?'.$fieldsString;
            }

            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);
            curl_setopt($ch, CURLOPT_HEADER , false);  // we want headers
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $result = curl_exec ($ch);
            
            if ($result === false)
            {
                print_r('Curl error: ' . curl_error($ch)); exit();
            }

            $return['response'] = json_decode($result,true);

            if($return['response'] == false)
                $return['response'] = $result;

            $return['status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close ($ch);

            return $return;
        }
    }

?>