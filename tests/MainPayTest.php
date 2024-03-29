<?php

namespace MainPay\Tests;

use PHPUnit\Framework\TestCase;
use MainPay\MainPay;

class MainPayTest extends TestCase
{
    const PRODUCTION_BASE_URL = 'http://api.mainpay.test';
    const SANDBOX_BASE_URL = 'http://api.sandbox.mainpay.test';

    protected $mainpay;
    protected $mainpay_sandbox;
    protected $server_key = 'test-server-key';
    protected $transaction;

    protected function setUp()
    {
        parent::setUp();

        $this->mainpay = new MainPay([
            'server_key' => $this->server_key,
            'production' => true,
        ]);
        $this->mainpay->setBaseUrl(self::PRODUCTION_BASE_URL);

        $this->mainpay_sandbox = new MainPay([
            'server_key' => $this->server_key,
            'production' => false,
        ]);
        $this->mainpay_sandbox->setBaseUrl(self::SANDBOX_BASE_URL);

        $this->transaction = [
            'order_id' => 'ORDER-111',
            'amount' => 1000000,
            'customer' => [
                'first_name' => 'Test',
                'last_name' => 'MainPay',
                'email' => 'test@mainpay.id',
                'phone' => '081234566789',
            ],
            'billing_address' => [
                'first_name' => 'Test',
                'last_name' => 'MainPay',
                'email' => 'test@mainpay.id',
                'phone' => '081234566789',
                'address' => 'Lengkong',
                'city' => 'Bandung',
                'postal_code' => '40264',
                'country' => 'ID',
            ],
            'shipping_address' => [
                'first_name' => 'Test',
                'last_name' => 'MainPay',
                'email' => 'test@mainpay.id',
                'phone' => '081234566789',
                'address' => 'Lengkong',
                'city' => 'Bandung',
                'postal_code' => '40264',
                'country' => 'ID',
            ],
            'items' => [
                [
                    'item_id' => 'ITEM-111',
                    'name' => 'MainPay T-Shirt',
                    'price' => 250000,
                    'quantity' => 1,
                ],
                [
                    'item_id' => 'ITEM-112',
                    'name' => 'MainPay Jacket',
                    'price' => 750000,
                    'quantity' => 1,
                ],
            ],
            'enabled_payments' => ['permata_va', 'bni_va', 'bca_va'],
        ];
    }

    public function testCreateMainPayInstance()
    {
        $mainpay = new MainPay([
            'server_key' => $this->server_key,
            'production' => true,
        ]);

        $this->assertTrue($mainpay->getProduction());
        $this->assertSame($this->server_key, $mainpay->getServerKey());
        $this->assertSame('https://api.mainpay.id', $mainpay->getBaseUrl());
    }

    public function testCreateMainPayInstanceInSandbox()
    {
        $mainpay = new MainPay([
            'server_key' => $this->server_key,
            'production' => false,
        ]);

        $this->assertFalse($mainpay->getProduction());
        $this->assertSame($this->server_key, $mainpay->getServerKey());
        $this->assertSame('https://api.sandbox.mainpay.id', $mainpay->getBaseUrl());
    }

    public function testCreateTransaction()
    {
        $response = $this->mainpay->createTransaction($this->transaction);

        $this->assertArrayHasKey('production', $response);
        $this->assertArrayHasKey('token', $response);
        $this->assertArrayHasKey('redirect_url', $response);
        $this->assertTrue($response['production']);
    }

    public function testCreateTransactionInSandbox()
    {
        $response = $this->mainpay_sandbox->createTransaction($this->transaction);

        $this->assertArrayHasKey('production', $response);
        $this->assertArrayHasKey('token', $response);
        $this->assertArrayHasKey('redirect_url', $response);
        $this->assertFalse($response['production']);
    }

    public function testGetTransactions()
    {
        $response = $this->mainpay->getTransactions();

        $this->assertArrayHasKey('production', $response);
        $this->assertArrayHasKey('transactions', $response);
        $this->assertTrue($response['production']);
    }

    public function testGetTransactionsInSandbox()
    {
        $response = $this->mainpay_sandbox->getTransactions();

        $this->assertArrayHasKey('production', $response);
        $this->assertArrayHasKey('transactions', $response);
        $this->assertFalse($response['production']);
    }

    public function testGetTransaction()
    {
        $response = $this->mainpay->getTransaction('19b749f4-3d20-4de9-ab4a-bcb35a9d1291');

        $this->assertArrayHasKey('production', $response);
        $this->assertArrayHasKey('transaction', $response);
        $this->assertSame('19b749f4-3d20-4de9-ab4a-bcb35a9d1291', $response['transaction']['id']);
        $this->assertTrue($response['production']);
    }

    public function testGetTransactionInSandbox()
    {
        $response = $this->mainpay_sandbox->getTransaction('19b749f4-3d20-4de9-ab4a-bcb35a9d1291');

        $this->assertArrayHasKey('production', $response);
        $this->assertArrayHasKey('transaction', $response);
        $this->assertSame('19b749f4-3d20-4de9-ab4a-bcb35a9d1291', $response['transaction']['id']);
        $this->assertFalse($response['production']);
    }

    public function testGetTransactionItems()
    {
        $response = $this->mainpay->getTransactionItems('19b749f4-3d20-4de9-ab4a-bcb35a9d1291');

        $this->assertArrayHasKey('production', $response);
        $this->assertArrayHasKey('items', $response);
        $this->assertTrue($response['production']);
    }

    public function testGetTransactionItemsInSandbox()
    {
        $response = $this->mainpay_sandbox->getTransactionItems('19b749f4-3d20-4de9-ab4a-bcb35a9d1291');

        $this->assertArrayHasKey('production', $response);
        $this->assertArrayHasKey('items', $response);
        $this->assertFalse($response['production']);
    }

    public function testGetTransactionStatus()
    {
        $response = $this->mainpay->getTransactionStatus('19b749f4-3d20-4de9-ab4a-bcb35a9d1291');

        $this->assertArrayHasKey('production', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertTrue($response['production']);
    }

    public function testGetTransactionStatusInSandbox()
    {
        $response = $this->mainpay_sandbox->getTransactionStatus('19b749f4-3d20-4de9-ab4a-bcb35a9d1291');

        $this->assertArrayHasKey('production', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertFalse($response['production']);
    }
}
