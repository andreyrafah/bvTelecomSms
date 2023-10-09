<?php

use Andreyrafah\BvTelecomSms\InvalidNumberException;

it('test size 10 number should throw a exception', function () {
    $bvTelecomSms = new \Andreyrafah\BvTelecomSms\BvTelecomSms();
    $bvTelecomSms->send('1234567890', 'teste');

})->throws(InvalidNumberException::class, 'The size has to be 11 characters');

it('test size 12 number should throw a exception', function () {
    $bvTelecomSms = new \Andreyrafah\BvTelecomSms\BvTelecomSms();
    $bvTelecomSms->send('123456789012', 'teste');

})->throws(InvalidNumberException::class, 'The size has to be 11 characters');

it('test ddd invalid should throw a exception', function () {
    $bvTelecomSms = new \Andreyrafah\BvTelecomSms\BvTelecomSms();
    $bvTelecomSms->send('20345678901','teste');

})->throws(InvalidNumberException::class, 'DDD Invalid');
