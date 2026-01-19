@extends('layouts.customer')

@section('content')
<style>
    .faqs-section {
        padding: 40px 5%;
        max-width: 900px;
        margin: 0 auto;
        text-align: center;
    }

    .page-title {
        font-family: 'Cooper Black', serif;
        font-size: 2.2rem;
        color: #4A2C2A;
        margin-bottom: 30px;
        letter-spacing: -0.5px;
    }

    .faq-container {
        background-color: transparent;
        border: 2px solid #4A2C2A; 
        border-radius: 30px;
        padding: 40px;
        display: flex;
        gap: 40px;
        text-align: left;
        box-shadow: 0px 8px 20px rgba(74, 44, 42, 0.05);
    }

    .faq-column {
        flex: 1;
    }

    .faq-item {
        margin-bottom: 25px;
    }

    .faq-item h3 {
        font-family: 'Poppins', sans-serif;
        font-weight: 700; 
        font-size: 1rem;
        color: #4A2C2A;
        margin-bottom: 6px;
    }

    .faq-item p {
        font-family: 'Poppins', sans-serif;
        font-weight: 400;
        font-size: 0.85rem;
        color: #4A2C2A;
        line-height: 1.5;
        margin: 0;
    }

    @media (max-width: 850px) {
        .faq-container {
            flex-direction: column;
            padding: 25px;
            gap: 0;
        }
        .page-title {
            font-size: 1.8rem;
        }
    }
</style>

<div class="faqs-section">
    <h1 class="page-title">Frequently Asked Questions</h1>

    <div class="faq-container">
        <div class="faq-column">
            <div class="faq-item">
                <h3>1. Do you deliver?</h3>
                <p>Yes, we do deliver within Metro Manila areas from Monday to Saturday (except holidays).</p>
            </div>

            <div class="faq-item">
                <h3>2. How much is the delivery fee?</h3>
                <p>We offer a flat rate delivery fee of 100 PHP for all orders within Metro Manila areas.</p>
            </div>

            <div class="faq-item">
                <h3>3. Can I pick up my order instead?</h3>
                <p>You can certainly select the "Pick Up" option during the checkout process if you'd prefer to collect your order in person. Our pick-up location is conveniently located at Quezon City, Metro Manila.</p>
            </div>

            <div class="faq-item">
                <h3>4. Can I choose the delivery date?</h3>
                <p>Yes, you may select your preferred delivery date upon checkout to ensure your order arrives exactly when you need it. Just choose your desired date on the shipping page before completing your purchase.</p>
            </div>
        </div>

        <div class="faq-column">
            <div class="faq-item">
                <h3>5. Do you offer bulk orders for events?</h3>
                <p>Yes, we cater to large events and office meetings. Please place your bulk orders at least 3 days in advance to ensure we have everything freshly prepared for you.</p>
            </div>

            <div class="faq-item">
                <h3>6. How should I store my pastries?</h3>
                <p>To keep them fresh, store our pastries in an airtight container at room temperature for up to 2 days.</p>
            </div>

            <div class="faq-item">
                <h3>7. How do I track my order?</h3>
                <p>To track your order, simply head to the navigation bar at the top of the page and locate the Track Order icon positioned conveniently between the search bar and your shopping cart. Click on that icon to access the tracking page, where you can instantly check the current status and progress of your order.</p>
            </div>
        </div>
    </div>
</div>
@endsection