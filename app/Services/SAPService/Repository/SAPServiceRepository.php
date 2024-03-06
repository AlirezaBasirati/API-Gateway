<?php

namespace App\Services\SAPService\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route as RouteFacade;

class SAPServiceRepository implements SAPServiceInterface
{
    public function getToken(): array
    {
        $res = Http::withBasicAuth(env('SAP_USERNAME', 'guest'), env('SAP_PASSWORD', 'guest'))
            ->withHeaders(['X-CSRF-Token' => 'fetch'])
            ->get(env('SAP_BASE_URL', 'guest') . "/ZSD_CREATE_SALES_ORDER_SRV_01/HeaderSet('')");

        foreach ($res->cookies() as $cookie) {
            foreach ((array)$cookie as $item) {
                $cookie[] = $item['Name'] . '=' . $item['Value'];
            }
        }

        $cookie[] = 'path=/';
        $cookie[] = 'HttpOnly';
        $cookie = implode(';', $cookie);

        return [
            'csrf_token' => $res->header('x-csrf-token'),
            'cookie_token' => $cookie
        ];
    }

    public function sendOrder($data, $shouldMap = true): array
    {
        if($shouldMap)
            $data = $this->mapOrderData($data);

        $token = $this->getToken();

        $response = Http::withBasicAuth(env('SAP_USERNAME', 'guest'), env('SAP_PASSWORD', 'guest'))
            ->withHeaders([
                'X-CSRF-Token' => $token['csrf_token'],
                'Cookie' => $token['cookie_token'],
                'Authorization' => env('SAP_AUTHORIZATION_HEADER', 'guest'),
            ])
            ->post(env('SAP_BASE_URL', 'http://localhost') . "/ZSD_CREATE_SALES_ORDER_SRV_01/HeaderSet",
                $data
            );

        return [
            'body' => $response->body(),
            'status' => $response->status(),
        ];
    }

    public function mapOrderData($data): array
    {
        $payment_method = $this->paymentMethodMapping($data['gateway_pos'] ?: $data['gateway']);
        foreach ($data['all_items'] as $rowNo => $item) {
            $rowNo += 10;
            $material = explode('-', $item['sku']);
            $items[] = [
                'ItmNumber' => strval($rowNo),
                'Material' => $material[1],
                'Plant' => 'D000',
                'TargetQty' => (string)$item['qty_ordered'],
                'StoreLoc' => (string)$data['storage_location'],
                'TargetQu' => 'PC',
            ];

            $conditionSet[] = [
                'ItmNumber' => strval($rowNo),
                'CondType' => 'ZPR0',
                'CondValue' => (string)(($item['base_original_price'] - round($item['base_discount_amount'] / $item['qty_ordered']))),
                'Currency' => 'IRR',
                'CondUnit' => 'PC',
            ];

            $scheduleSet[] = [
                'ItmNumber' => strval($rowNo),
                'ReqQty' => (string)$item['qty_ordered'],
            ];
        }

        $conditionSet[] = [
            'ItmNumber' => '0000',
            'CondType' => 'ZFRO',
            'CondValue' => $data['base_shipping_amount'],
            'Currency' => 'IRR',
        ];

        $conditionSet[] = [
            "ItmNumber" => "0000",
            "CondType" => "ZWAL",
            "CondValue" => (string)$data['e_wallet'],
            "Currency" => "IRR"
        ];


        $address_arr = $data['customer_address'];
        $address_str = explode(' - ', $address_arr);
        foreach ($address_str as $k => $address_part) {
            if (!empty($address_part))
                ${'address_part_' . $k} = substr($address_part, 0, 40);

        }

        $shipping_method = $data['shipping_method'];
        switch ($shipping_method) {
            case 'normal':
                $delivery_date = (string)date('Ymd', strtotime($data['delivery_date']));
                $delivery_time = (string)$data['delivery_timeslot'];
                $delivery_id = match ($delivery_time) {
                    '9-12' => '1',
                    '12-15' => '2',
                    '15-18' => '3',
                    '18-21' => '4',
                };

                break;

            default:
                $delivery_date = date('Ymd');
                break;
        }

        $partnerSet = [
            [
                'ItmNumber' => '10',
                'Name' => $data['customer_firstname'] . ' ' . $data['customer_lastname'],
                'Telephone' => (string)$data['customer_mobile_no'],
                'Country' => 'IR',
                'City' => $data['city'],
                'Address' => 'Tehran',
                'AddrOrig' => 'B',
                'AddrLink' => '9999',
                'PartnRole' => 'AG',
                'PartnNumb' => '1000700000',
                'Street' => (isset($address_part_0) && !empty($address_part_0)) ? $address_part_0 : "",
                'StrSuppl1' => "",
                'StrSuppl2' => "",
                'StrSuppl3' => "",
                'Location' => "",
            ]
        ];

        return [
            'd' => [
                "Name" => (string)$data['ref_id'],
                "Doc_Date" => (string)date('Ymd', strtotime($data['fire_at'])),
                "Pmnttrms" => $payment_method,
                'Salesdocumentin' => '',
                'DocType' => 'ZO01', // Sale
                'SalesOrg' => '1000',
                'DistrChan' => 'OS',
                'Division' => 'R1',
                'PurchNoC' => env('APP_ENV') == 'staging' ? 'ST' . substr($data['increment_id'], 2) : $data['increment_id'], // Order ID
                'Ref1' => env('APP_ENV') == 'staging' ? 'ST' . substr($data['increment_id'], 2) : $data['increment_id'], // Order ID for customer
                'DlvTime' => (isset($delivery_id) && !empty($delivery_id)) ? strval($delivery_id) : '', // Delivery time slot ID
                'ReqDateH' => (isset($delivery_date) && !empty($delivery_date)) ? strval($delivery_date) : '', // Delivery Date
                'NAVRESULT' => [],
                'ItemSet' => $items,
                'ConditionSet' => $conditionSet,
                'scheduleSet' => $scheduleSet,
                'PartnerSet' => $partnerSet
            ]
        ];
    }

    private function paymentMethodMapping($payment_method): string
    {
        return match ($payment_method) {
            'cashondelivery', 'cache' => "Z001",
            'paymentsamantoken', 'samantoken' => "Z002",
            'mellat' => "Z003",
            'free' => "Z004",
            default => "NONE",
        };
    }
}
