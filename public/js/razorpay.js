async function initiateRazorpayPayment(orderId, amount, customerName, customerPhone) {
    try {
        const res  = await fetch('/api/payments/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                order_id: orderId,
                amount:   amount
            })
        });

        const data = await res.json();

        if (!data.success) {
            alert('Payment initiation failed. Please try again.');
            return;
        }

        const options = {
            key:         data.key,
            amount:      data.amount,
            currency:    data.currency,
            name:        'South Tiffins',
            description: 'Food Order Payment',
            order_id:    data.razorpay_order_id,
            prefill: {
                name:    customerName  || '',
                contact: customerPhone || ''
            },
            theme: {
                color: '#FF6B35'
            },
            handler: async function(response) {
                await verifyPayment(response, orderId);
            },
            modal: {
                ondismiss: function() {
                    console.log('Payment cancelled');
                }
            }
        };

        const rzp = new Razorpay(options);
        rzp.open();

    } catch (err) {
        console.error('Razorpay error:', err);
        alert('Payment failed. Please try again.');
    }
}

async function verifyPayment(response, orderId) {
    try {
        const res = await fetch('/api/payments/verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                razorpay_order_id:   response.razorpay_order_id,
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_signature:  response.razorpay_signature
            })
        });

        const data = await res.json();

        if (data.success) {
            window.location.href = '/menu/order-confirm?order_id=' + data.order_id;
        } else {
            alert('Payment verification failed. Contact support.');
        }

    } catch (err) {
        console.error('Verify error:', err);
        alert('Payment verification failed. Please contact support.');
    }
}