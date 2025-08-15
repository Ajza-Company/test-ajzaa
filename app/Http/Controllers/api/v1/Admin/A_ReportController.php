<?php

namespace App\Http\Controllers\api\v1\Admin;

use App\Enums\OrderDeliveryMethodEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentMethodsEnum;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class A_ReportController extends Controller
{
    public function createTaxInvoice(string $company_id, string $period): JsonResponse
    {
        $company = Company::findOrFail(decodeString($company_id));
        $orders = $this->getCompletedOrdersForPeriod($company, $period);
        $setting = ajzaSetting();

        $invoiceData = [
            'invoiceNo' => generateOrderId('Aj', 'tax'),
            'company' => $this->buildCompanyInvoiceData($company, $orders, $setting, $period)
        ];

        return response()->json($invoiceData);
    }

    private function getCompletedOrdersForPeriod(Company $company, string $period)
    {
        return $company->orders()
            ->where('status', OrderStatusEnum::COMPLETED)
            ->whereBetween('created_at', [
                $period . '-01',
                $period . '-31'
            ]);
    }

    private function buildCompanyInvoiceData(Company $company, $orders, $setting, string $period): array
    {
        return [
            'name' => $company->localized()->name,
            'vat_number' => $company->vat_number,
            'commercial_register' => $company->commercial_register,
            'period' => $period,
            'date' => now()->format('Y-m-d H:i:s'),
            'simple_invoice' => $this->buildSimpleInvoiceData($orders, $setting),
            'sales_summary' => $this->buildSalesSummaryData($orders),
            'commissions' => $this->buildCommissionsData($orders, $setting)
        ];
    }

    private function buildSimpleInvoiceData($orders, $setting): array
    {
        return [
            'orders_commission' => $orders->sum('commission') * $setting->order_percentage / 100,
            'orders_amount' => $orders->sum('amount')
        ];
    }

    private function buildSalesSummaryData($orders): array
    {
        $visaOrders = $orders->where('payment_method', PaymentMethodsEnum::CARD);
        $visaAmount = $visaOrders->sum('amount');
        $vatRate = 0.14;

        return [
            'credit_sales' => [
                'count' => $visaOrders->count(),
                'amount' => [
                    'total' => $visaAmount,
                    'vat' => $vatRate,
                    'total_with_vat' => $visaAmount * (1 + $vatRate)
                ]
            ]
        ];
    }

    private function buildCommissionsData($orders, $setting): array
    {
        return [
            'sales_commission' => [
                'ajza_commission' => $this->buildAjzaCommissionData($orders, $setting),
                'pickup_commission' => $this->buildPickupCommissionData($orders, $setting)
            ],
            'bank_commission' => [
                'success_commission' => $this->calculateBankCommission($orders, $setting)
            ]
        ];
    }

    private function buildAjzaCommissionData($orders, $setting): array
    {
        $totalAmount = $orders->sum('amount');
        $commissionTotal = $totalAmount * $setting->order_percentage / 100;
        $orderCount = $orders->count();

        return [
            'total' => $commissionTotal,
            'avg_rate' => $orderCount > 0 ? $commissionTotal / $orderCount : 0,
            'total_with_vat' => $totalAmount + $commissionTotal
        ];
    }

    private function buildPickupCommissionData($orders, $setting): array
    {
        $pickupOrders = $orders->where('delivery_method', OrderDeliveryMethodEnum::IN_STORE);
        $pickupAmount = $pickupOrders->sum('amount');
        $commissionTotal = $pickupAmount * $setting->order_percentage / 100;
        $pickupCount = $pickupOrders->count();

        return [
            'total' => $commissionTotal,
            'avg_rate' => $pickupCount > 0 ? $commissionTotal / $pickupCount : 0,
            'total_with_vat' => $pickupAmount + $commissionTotal
        ];
    }

    private function calculateBankCommission($orders, $setting): float
    {
        return $orders->where('payment_method', PaymentMethodsEnum::CARD)
                ->sum('amount') * $setting->order_percentage / 100;
    }
}
