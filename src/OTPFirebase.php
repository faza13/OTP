<?php


namespace Faza13\OTP;


use Faza13\OTP\Contracts\OTPInterface;

class OTPFirebase implements OTPInterface
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * OTPFirebase constructor.
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function request($phone, array $options = [])
    {
        $params = [
            "phoneNumber" => $phone,
            "iosReceipt" => $options['iosReceipt'],
            "iosSecret" => $options['iosSecret'],
            "recaptchaToken" => $options['recaptchaToken'],
            "tenantId" => $options['tenantId']
        ];
        $client = new \GuzzleHttp\Client();

        try{
            $r = $client->request('POST', $this->getUrl('request'), [
                'json' => $options
            ]);
        }
        catch (\Exception $e) {
            Throw new \Exception("Request Failed");
        }

        $data = json_decode($r->getBody());
        return [
            "sessionId" => $data->sessionInfo
        ];
    }

    public function validate($phone, $code, $options = [])
    {
        $params = [
            "sessionInfo" => $options['sessionInfo'],
            "phoneNumber" => $phone,
            "code" => $code,
        ];

        $client = new \GuzzleHttp\Client();

        try{
            $r = $client->request('POST', $this->getUrl('validate'), [
                'json' => $params
            ]);
        }
        catch (\Exception $e) {
            Throw new \Exception("Validation Failed");
        }

        $data = json_decode($r->getBody());

        return [
            'phone' => $data['phoneNumber'],
            'success' => true,
        ];
    }

    /**
     * @param string $action
     * @return string
     * @throws \Exception
     */
    public function getUrl(string $action): string
    {
        switch ($action)
        {
            case "request":
                return  "https://identitytoolkit.googleapis.com/v1/accounts:sendVerificationCode?key=" . $this->apiKey;
                break;
            case "validate":
                return "https://identitytoolkit.googleapis.com/v1/accounts:signInWithPhoneNumber?key=" . $this->apiKey;
                break;
            default:
                Throw new \Exception("Action not found");
                break;
        }

    }
}
