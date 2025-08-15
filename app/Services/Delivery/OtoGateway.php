<?php

namespace App\Services\Delivery;

use App\DTOs\PaymentRequestDTO;
use App\DTOs\PaymentResponseDTO;
use App\Exceptions\PaymentGatewayException;
use App\Models\Order;
use App\Services\Payment\PaymentGatewayInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OtoGateway
{
    private string $baseUrl;
    private string $refreshToken;
    public function __construct()
    {
        $this->baseUrl = config('services.delivery.oto.base_url');
        $this->refreshToken = config('services.delivery.oto.refresh_token');
    }

    /**
     * @throws ConnectionException
     */
    public function createShipment(Order $order)
    {
        $accessToken = $this->generateAccessToken();
        return $this->createOrder($accessToken, $order);
    }

    /**
     * @throws ConnectionException
     */
    public function generateAccessToken()
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl . 'refreshToken', [
            'refresh_token' => $this->refreshToken
        ]);

        if ($response->successful() && $response->json()->success) {
            return $response->json()->access_token;
        }

        return null;
    }

    /**
     * @throws ConnectionException
     */
    public function createOrder(string $accessToken, Order $order)
    {
        $client = $order->user;
        $address = $order->address;
        $addressMetadata = json_decode($address->metadata, true);
        $items = [];
        $products = $order->orderProducts;
        // create items array
        foreach ($products as $product) {
            $items[] = [
                'productId' => encodeString($product->store_product_id),
                'name' => $product->storeProduct->product->localized->name,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'sku' => $product->storeProduct->product->part_number
            ];
        }
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ])->post($this->baseUrl . 'createOrder', [
            'orderId' => $order->order_id,
            'ref1' => encodeString($order->id),
            'createShipment' => true,
            'storeName' => $order->store->company->localized()->name,
            'payment_method' => 'paid',
            'amount' => $order->amount,
            'amount_due' => 0,
            'currency' => 'SAR',
            'customer' => [
                'name' => $client->name,
                'mobile' => $client->full_mobile,
                'address' => $address->address,
                'district' => $addressMetadata['subLocality'],
                'city' => $addressMetadata['locality'],
                'country' => $addressMetadata['isoCountryCode'],
                'postcode' => $addressMetadata['postalCode'],
                'lat' => $address->latitude,
                'lon' => $address->longitude,
                'refID' => encodeString($client->id)
            ],
            'items' => $items
        ]);

        if ($response->successful()) {
            Log::info('Shipment order created successfully: ' . $response->body());
            return $response->json();
        } else {
            Log::error('Error creating shipment order: ' . $response->body());
            return null;
        }
    }
}
