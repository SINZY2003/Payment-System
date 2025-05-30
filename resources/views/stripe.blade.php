<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment - Stripe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script async src="https://js.stripe.com/v3/buy-button.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .buy-button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f3f4f6; /* Light gray background */
        }
        .stripe-buy-button-wrapper {
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            text-align: center;
        }
        .header-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a202c; /* Dark gray text */
            margin-bottom: 1rem;
        }
        .subheader-text {
            color: #4a5568; /* Medium gray text */
            margin-bottom: 2rem;
        }
    </style>
</head>
<body class="buy-button-container">
    <div class="stripe-buy-button-wrapper">
        <h1 class="header-text">Purchase</h1>
        <p class="subheader-text">Click the button below to complete your secure transaction.</p>
        <stripe-buy-button
            buy-button-id="buy_btn_1RTkhfRaij3erJ39D7KY1lWe"
            publishable-key="pk_test_51RTkTIRaij3erJ39alE7uyXTo7c5MJeLifchZ2zPmbkYpeGIDbCvsTaYGHSVpDlyOKRpS8eByPJXYjWL0twLW4Ea00rZ0UBj3h"
        >
        </stripe-buy-button>
    </div>
</body>
</html>
