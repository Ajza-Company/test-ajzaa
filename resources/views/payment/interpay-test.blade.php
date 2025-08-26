<!DOCTYPE html>
<html>
<head>
    <title>InterPay Payment Test</title>
    <script src="https://ecomspghostedpage.softpos-ksa.com/js/interpay.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .amount-display {
            background: #28a745;
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
            font-size: 24px;
            font-weight: bold;
        }
        .payment-form {
            min-height: 300px;
            border: 2px solid #ddd;
            border-radius: 8px;
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .loading {
            display: inline-block;
            width: 30px;
            height: 30px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .error {
            color: #dc3545;
            text-align: center;
            padding: 20px;
            background: #f8d7da;
            border-radius: 5px;
            border: 1px solid #f5c6cb;
        }
        .success {
            color: #155724;
            text-align: center;
            padding: 20px;
            background: #d4edda;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
        }
        .status {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-family: monospace;
            font-size: 12px;
            border-left: 4px solid #007bff;
        }
        .btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>InterPay Payment Test</h1>
        
        <div class="amount-display">
            Test Amount: SAR 1.01
        </div>
        
        <div class="status" id="status">
            <strong>Status:</strong> Ready to generate payment tokens
        </div>
        
        <div style="text-align: center; margin: 20px 0;">
            <button class="btn" id="generateBtn" onclick="generateTokens()">Generate Payment Tokens</button>
        </div>
        
        <div class="payment-form" id="paymentForm">
            <div style="text-align: center;">
                <div class="loading"></div>
                <p>Click "Generate Payment Tokens" to start</p>
            </div>
        </div>
    </div>

    <script>
        let generatedTokens = null;
        
        async function generateTokens() {
            const btn = document.getElementById('generateBtn');
            const status = document.getElementById('status');
            const paymentForm = document.getElementById('paymentForm');
            
            // Disable button and show loading
            btn.disabled = true;
            btn.innerHTML = 'Generating...';
            status.innerHTML = '<strong>Status:</strong> Generating tokens from InterPay...';
            paymentForm.innerHTML = '<div style="text-align: center;"><div class="loading"></div><p>Generating tokens...</p></div>';
            
            try {
                // Call InterPay API to generate tokens
                const response = await fetch('/api/interpay/generate-tokens', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        amount: 1.01,
                        currency: 'SAR',
                        ecommerce_order_id: 'TEST_' + Date.now(),
                        customer: {
                            name: 'Test Customer',
                            email: 'test@example.com',
                            address1: '123 Test Street',
                            city: 'Riyadh',
                            country_code: 'SAU'
                        }
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    generatedTokens = data.data;
                    
                    status.innerHTML = `
                        <strong>Status:</strong> ✅ Tokens generated successfully!<br>
                        <strong>Token:</strong> ${data.data.token ? 'SET (' + data.data.token.length + ' chars)' : 'MISSING'}<br>
                        <strong>Auth Token:</strong> ${data.data.auth_token ? 'SET (' + data.data.auth_token.length + ' chars)' : 'MISSING'}
                    `;
                    
                    // Initialize InterPay iframe
                    initializePayment();
                    
                } else {
                    throw new Error(data.message || 'Failed to generate tokens');
                }
                
            } catch (error) {
                console.error('Error generating tokens:', error);
                status.innerHTML = `<strong>Status:</strong> ❌ Error: ${error.message}`;
                paymentForm.innerHTML = `
                    <div class="error">
                        <h3>Token Generation Failed</h3>
                        <p>${error.message}</p>
                        <button class="btn" onclick="generateTokens()">Try Again</button>
                    </div>
                `;
            } finally {
                // Re-enable button
                btn.disabled = false;
                btn.innerHTML = 'Generate Payment Tokens';
            }
        }
        
        function initializePayment() {
            if (!generatedTokens || !generatedTokens.token || !generatedTokens.auth_token) {
                document.getElementById('paymentForm').innerHTML = `
                    <div class="error">
                        <h3>Missing Tokens</h3>
                        <p>Cannot initialize payment without valid tokens.</p>
                    </div>
                `;
                return;
            }
            
            try {
                console.log('Initializing InterPay with tokens:', {
                    token: generatedTokens.token,
                    authToken: generatedTokens.auth_token
                });
                
                const interpay = new Interpay("pk_live_X8JMzT0ZfUOGvA7CI5qSRQLKswZyAEUUzJFywmDAe29KCY7L7Lp350NmsxkcSJaivo");
                
                const model = {
                    amount: "1.01",
                    token: generatedTokens.token,
                    authToken: generatedTokens.auth_token,
                };
                
                // Create the hosted field
                interpay.CreateHostedFieldElement(model).then(function(iframe) {
                    console.log('InterPay iframe created successfully!');
                    
                    // Clear loading and show iframe
                    document.getElementById('paymentForm').innerHTML = '';
                    document.getElementById('paymentForm').appendChild(iframe);
                    
                    // Add success message
                    const successDiv = document.createElement('div');
                    successDiv.className = 'success';
                    successDiv.innerHTML = '<p>✅ Payment form loaded successfully!</p>';
                    document.getElementById('paymentForm').appendChild(successDiv);
                    
                    // Add payment button
                    const payBtn = document.createElement('button');
                    payBtn.className = 'btn';
                    payBtn.innerHTML = 'Submit Payment';
                    payBtn.onclick = () => submitPayment(interpay, iframe);
                    document.getElementById('paymentForm').appendChild(payBtn);
                    
                }).catch(function(error) {
                    console.error('Error creating InterPay iframe:', error);
                    document.getElementById('paymentForm').innerHTML = `
                        <div class="error">
                            <h3>❌ InterPay Error</h3>
                            <p>Failed to load payment form: ${error.message}</p>
                        </div>
                    `;
                });
                
            } catch (error) {
                console.error('Error initializing InterPay:', error);
                document.getElementById('paymentForm').innerHTML = `
                    <div class="error">
                        <h3>❌ Initialization Error</h3>
                        <p>Error: ${error.message}</p>
                    </div>
                `;
            }
        }
        
        function submitPayment(interpay, iframe) {
            const payBtn = document.querySelector('#paymentForm button');
            payBtn.disabled = true;
            payBtn.innerHTML = 'Processing...';
            
            interpay.SubmitCardDetails(iframe).then(
                function (value) {
                    console.log('Payment Response:', value);
                    document.getElementById('paymentForm').innerHTML = `
                        <div class="success">
                            <h3>🎉 Payment Successful!</h3>
                            <p>Your payment has been processed successfully.</p>
                            <p><strong>Transaction ID:</strong> ${value.transactionId || 'N/A'}</p>
                        </div>
                    `;
                },
                function (error) {
                    console.error('Payment Error:', error);
                    document.getElementById('paymentForm').innerHTML = `
                        <div class="error">
                            <h3>❌ Payment Failed</h3>
                            <p>Error: ${error.message || 'Unknown error'}</p>
                            <button class="btn" onclick="generateTokens()">Try Again</button>
                        </div>
                    `;
                }
            );
        }
    </script>
</body>
</html>
