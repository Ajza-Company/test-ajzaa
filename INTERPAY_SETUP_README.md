# InterPay Payment Gateway Setup

## 🚀 Overview
This guide explains how to set up InterPay payment gateway in your Laravel application.

## ⚙️ Configuration

### 1. Environment Variables
Add these variables to your `.env` file:

```env
# InterPay Payment Gateway
INTERPAY_PUBLIC_KEY=pk_live_X8JMzT0ZfUOGvA7CI5qSRQLKswZyAEUUzJFywmDAe29KCY7L7Lp350NmsxkcSJaivo
INTERPAY_SECRET_KEY=your_interpay_secret_key_here
INTERPAY_BASE_URL=https://ecomspghostedpage.softpos-ksa.com/
```

### 2. Callback URL Setup
In your InterPay dashboard, set the callback URL to:
```
{{merchantUrl}}/api/interpay/callback
```

## 🔧 Implementation Details

### Payment Flow
1. **Create Payment**: User initiates payment
2. **Redirect to InterPay**: User is redirected to InterPay hosted checkout
3. **Payment Processing**: User completes payment on InterPay
4. **Callback**: InterPay sends payment result to your callback URL
5. **Order Update**: Order status is updated based on payment result

### Callback Data Structure
```json
{
  "Id": "8dc3363d-ae45-40dc-a277-487dc7ba8127",
  "OrderId": "d8ea2803-801e-407c-9ad0-2e40959ff248",
  "Card": {
    "Brand": "MASTERCARD",
    "Country": "SA",
    "ExpMonth": 12,
    "ExpYear": 25,
    "LastFourDigit": "2805",
    "MaskedPAN": "520000****2805"
  },
  "TransactionTime": "2025-08-14T16:39:47.6254588+03:00",
  "Type": "card",
  "RRNumber": "728511979140",
  "STAN": "009709",
  "TransactionId": "8dc3363d-ae45-40dc-a277-487dc7ba8127",
  "ResponseText": "AUTHORIZED",
  "ResponseCode": "00",
  "ApprovalCode": "831000",
  "Status": "1",
  "Message": "Transaction Approved"
}
```

### Response Codes
- **00**: Success
- **01**: Failed
- **02**: Pending

### Status Values
- **1**: Success
- **0**: Failed
- **2**: Pending

## 🧪 Testing

### Test Card Details
- **Token**: `tok_VZ4sasL6Q02SWnzcZ-Bbgg`
- **Amount**: `1.01`

### Test Flow
1. Create a test order
2. Initiate payment with InterPay
3. Use test card details
4. Check callback logs
5. Verify order status update

## 📁 Files Modified

1. **`app/Services/Payment/InterPayGateway.php`** - Updated to work with InterPay
2. **`app/Http/Controllers/api/v1/Frontend/InterPayController.php`** - Enhanced callback handling
3. **`config/services.php`** - Added InterPay configuration
4. **`routes/api.php`** - InterPay callback route already exists

## 🔍 Logging

InterPay callbacks are logged to the `InterPay` channel. Check your logs for:
- Callback received data
- Payment success/failure
- Order updates
- Any errors

## 🚨 Important Notes

1. **Always validate callbacks** before processing payments
2. **Use HTTPS** for production callback URLs
3. **Test thoroughly** before going live
4. **Monitor logs** for any payment issues
5. **Handle errors gracefully** in your application

## 📞 Support

For InterPay support, contact their technical team or check their documentation.
