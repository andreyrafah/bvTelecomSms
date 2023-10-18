<?php

namespace Andreyrafah\BvTelecomSms;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;

class BvTelecomSms
{
    /**
     * @var array
     */
    const DDD_NOT_IN_USE = [20, 23, 25, 26, 29, 30, 36, 39, 52, 57, 58, 59, 60, 78, 80, 90];

    private string $phone;

    private string $message;

    private array $response;

    private int $idMessageOnDb;

    /**
     * @throws InvalidNumberException
     */
    public function send(string $phone, string $message): array
    {
        $this->phone = $phone;
        $this->message = $message;

        $this->validatePhone();
        $this->saveOnDb();
        $this->fetch();
        $this->updateDb();

        return $this->response;
    }

    public function updateDb(): void
    {
        if (key_exists('status', $this->response) && $this->response['status'] == 'error') {
            DB::table('sms_sent')->upsert([
                'id' => $this->idMessageOnDb,
                'status' => 'failed',
            ], ['id']);
            return;
        }

        if (key_exists('message', $this->response)) {
            $status = 'unknown';
            if ($this->response['message'] == 'Message sent successfully') {
                $status = 'sent';
            }

            DB::table('sms_sent')->upsert([
                'id' => $this->idMessageOnDb,
                'status' => $status,
            ], ['id']);
        }
    }

    public function saveOnDb(): void
    {
        $this->idMessageOnDb = DB::table('sms_sent')->insertGetId([
            'phone' => $this->phone,
            'message' => $this->message,
            'status' => 'pending',
        ]);
    }

    /**
     * @throws InvalidNumberException
     */
    public function validatePhone(): void
    {
        $this->validateLength();
        $this->validateDdd();
    }

    /**
     * @throws InvalidNumberException
     */
    private function validateLength(): void
    {
        if (strlen($this->phone) != 11) {
            throw new InvalidNumberException('The size has to be 11 characters');
        }
    }

    /**
     * @throws InvalidNumberException
     */
    private function validateDdd(): void
    {
        $ddd = substr($this->phone, 0, 2);
        if (in_array($ddd, self::DDD_NOT_IN_USE)) {
            throw new InvalidNumberException('DDD Invalid');
        }
    }

    public function fetch(): void
    {
        $guzzle = new Client([
            'base_uri' => config('bvtelecomsms.base_uri'),
            'headers' => [
                'ApiKey' => config('bvtelecomsms.api-key'),
            ],
        ]);

        try {
            $response = $guzzle->post('/webhook/api/delivery/single-sms', [
                'json' => [
                    'celular' => $this->phone,
                    'mensagem' => $this->message,
                ],
            ]);
        } catch (GuzzleException $e) {
            $this->response = ['status' => 'error', 'message' => $e->getMessage()];
            return;
        }

        $this->response = json_decode($response->getBody()->getContents(), true);
    }
}
