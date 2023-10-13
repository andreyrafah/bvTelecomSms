<?php

namespace Andreyrafah\BvTelecomSms;

use GuzzleHttp\Client;

class BvTelecomSms
{
    /**
     * @var array
     */
    const DDD_NOT_IN_USE = [20, 23, 25, 26, 29, 30, 36, 39, 52, 57, 58, 59, 60, 78, 80, 90];

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $message;

    /**
     * @throws InvalidNumberException
     */
    public function send(string $phone, string $message): array
    {
        $this->phone = $phone;
        $this->message = $message;

        $this->validatePhone();

        return $this->fetch();
    }

    /**
     * @throws \Exception
     */
    public function validatePhone(): void
    {
        $this->validateLength();
        $this->validateDdd();
    }

    private function validateLength()
    {
        if (strlen($this->phone) != 11) {
            throw new InvalidNumberException('The size has to be 11 characters');
        }
    }

    private function validateDdd(): void
    {
        $ddd = substr($this->phone, 0, 2);
        if (in_array($ddd, self::DDD_NOT_IN_USE)) {
            throw new InvalidNumberException('DDD Invalid');
        }
    }

    public function fetch(): array
    {
        $guzzle = new Client([
            'base_uri' => config('bvtelecomsms.base_uri'),
            'headers' => [
                'ApiKey' => config('bvtelecomsms.api-key'),
            ],
        ]);

        $response = $guzzle->post('/webhook/api/delivery/single-sms', [
            'json' => [
                'celular' => $this->phone,
                'mensagem' => $this->message,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
