@extends('layouts.customer')

@section('content')

<style>
    .cart-section {
        padding: 60px 8%;
        max-width: 1200px;
        margin: 0 auto;
        color: #4A2C2A;
    }

    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 40px;
    }

    .cart-title {
        font-family: 'Cooper Black', serif;
        font-size: 3rem;
        margin: 0;
    }

    .continue-shopping {
        font-family: 'Poppins', sans-serif;
        color: #4A2C2A;
        text-decoration: underline;
        font-weight: 500;
        font-size: 1rem;
    }

    /* TABLE LAYOUT STYLES */
    .cart-table-header {
        display: grid;
        grid-template-columns: 50px 1.5fr 1fr 100px;
        padding-bottom: 15px;
        border-bottom: 1px solid #AEA9A0;
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        font-weight: 400;
        text-align: center;
    }

    .text-left { text-align: left; }

    /* ITEM ROW STYLES */
    .cart-item {
        display: grid;
        grid-template-columns: 50px 1.5fr 1fr 100px;
        align-items: center;
        padding: 25px 0;
        border-bottom: 1px solid rgba(174, 169, 160, 0.2);
    }

    .item-checkbox {
        width: 18px;
        height: 18px;
        accent-color: #4A2C2A;
    }

    .item-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
    }

    .item-details h4 {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .item-details p {
        margin: 5px 0 0 0;
        font-size: 0.9rem;
        opacity: 0.8;
    }

    /* BUTTON AND INPUT STYLES */
    .quantity-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
    }

    .quantity-control {
        border: 1.5px solid #4A2C2A;
        border-radius: 8px;
        display: flex;
        align-items: center;
        padding: 4px 12px;
        gap: 15px;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
    }

    .qty-btn {
        background: none;
        border: none;
        color: #4A2C2A;
        cursor: pointer;
        font-size: 1.2rem;
        font-weight: 600;
        padding: 0 5px;
    }

    .delete-btn {
        background: none;
        border: none;
        color: #4A2C2A;
        cursor: pointer;
        font-size: 1rem;
        opacity: 0.7;
        transition: all 0.2s;
    }

    .delete-btn:hover {
        opacity: 1;
        color: #e74c3c;
        transform: scale(1.1);
    }

    .item-total {
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        text-align: right;
    }

    /* FOOTER AND SUMMARY STYLES */
    .cart-footer {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-top: 50px;
        padding-top: 30px;
        border-top: 1px solid rgba(174, 169, 160, 0.3);
    }

    .instructions-box { flex: 0 1 45%; }
    .instructions-box label {
        display: block;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 12px;
    }

    .instructions-box textarea {
        width: 100%;
        height: 120px;
        border: 1px solid #4A2C2A;
        border-radius: 15px;
        padding: 15px;
        resize: none;
        font-family: 'Poppins', sans-serif;
        background: transparent;
        outline: none;
    }

    .char-counter {
        font-family: 'Poppins', sans-serif;
        font-size: 0.8rem;
        color: #7a5c5a;
        text-align: right;
        margin-top: 5px;
    }

    .cart-summary {
        text-align: right;
        flex: 0 1 40%;
    }

    .subtotal-container {
        display: flex;
        justify-content: flex-end;
        align-items: baseline;
        gap: 20px;
        margin-bottom: 10px;
    }

    .subtotal-label {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        font-size: 1.5rem;
    }

    .subtotal-value {
        font-family: 'Poppins', sans-serif;
        font-size: 1.3rem;
        font-weight: 400;
        min-width: 150px;
        text-align: right;
    }

    .tax-shipping-text {
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        opacity: 0.8;
        margin-bottom: 30px;
    }

    .checkout-btn {
        background: #FDF9F0;
        color: #4A2C2A;
        border: 1.5px solid #4A2C2A;
        padding: 12px 60px;
        border-radius: 30px;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .checkout-btn:hover {
        background: #4A2C2A;
        color: #FDF9F0;
        transform: translateY(-2px);
    }

    .empty-cart-msg {
        text-align: center;
        padding: 100px 0;
        font-family: 'Poppins', sans-serif;
    }

    .btn-go-menu {
        text-decoration: none;
        display: inline-block;
        padding: 12px 35px;
        background: #FDF9F0;
        color: #4A2C2A;
        border: 1.5px solid #4A2C2A;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-go-menu:hover {
        background: #4A2C2A;
        color: #FDF9F0;
    }

    /* MOBILE RESPONSIVENESS */
    @media (max-width: 768px) {
        .cart-table-header { display: none; }
        .cart-item {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 15px;
        }
        .item-info { flex-direction: column; }
        .item-total { text-align: center; font-weight: 700; }
        .cart-footer { flex-direction: column; gap: 40px; }
        .instructions-box, .cart-summary { flex: 1 1 100%; text-align: left; }
        .subtotal-container { justify-content: flex-start; }
    }
</style>

<div class="cart-section">
    <div class="cart-header">
        <h1 class="cart-title">Your Cart</h1>
        @if(session('cart') && count(session('cart')) > 0)
            <a href="{{ route('menu.pastries') }}" class="continue-shopping">Continue Shopping</a>
        @endif
    </div>

    {{-- CHECK IF SESSION CART HAS ITEMS --}}
    @if(session('cart') && count(session('cart')) > 0)
        <div class="cart-table-header">
            <div></div>
            <div class="text-left">Product</div>
            <div>Quantity</div>
            <div style="text-align: right;">Total</div>
        </div>

        @php $subtotal = 0; @endphp
        
        {{-- LOOP THROUGH ITEMS IN THE CART SESSION --}}
        @foreach(session('cart') as $id => $details)
            @php $subtotal += $details['price'] * $details['quantity']; @endphp
            <div class="cart-item" data-item-id="{{ $id }}">
                <input type="checkbox" class="item-checkbox" data-id="{{ $id }}" data-price="{{ $details['price'] }}" data-quantity="{{ $details['quantity'] }}" checked>
                
                <div class="item-info">
                    <img src="{{ asset($details['image']) }}" class="item-image" alt="{{ $details['name'] }}">
                    <div class="item-details">
                        <h4>{{ $details['name'] }}</h4>
                        <p>{{ number_format($details['price'], 2) }} PHP</p>
                    </div>
                </div>

                <div class="quantity-wrapper">
                    {{-- DATA-ID ATTRIBUTE FOR JAVASCRIPT SELECTION --}}
                    <div class="quantity-control" data-id="{{ $id }}">
                        <button class="qty-btn minus-btn">-</button>
                        <span class="qty-display">{{ $details['quantity'] }}</span>
                        <button class="qty-btn plus-btn">+</button>
                    </div>
                    
                    {{-- FORM TO REMOVE ITEM FROM CART --}}
                    <form action="{{ route('cart.remove') }}" method="POST" style="margin: 0;">
                        @csrf
                        <input type="hidden" name="id" value="{{ $id }}">
                        <button type="submit" class="delete-btn">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>

                <div class="item-total">
                    {{ number_format($details['price'] * $details['quantity'], 2) }} PHP
                </div>
            </div>
        @endforeach
        
        <div class="cart-footer">
            <div class="instructions-box">
                <label for="special-instructions">Order Special Instructions:</label>
                <textarea id="special-instructions" name="instructions" placeholder="Anything we should know?" maxlength="500"></textarea>
                <div class="char-counter">
                    <span id="char-count">0</span> / 500 characters
                </div>
            </div>

            <div class="cart-summary">
                <div class="subtotal-container">
                    <span class="subtotal-label">Subtotal:</span>
                    <span class="subtotal-value" id="subtotal-display">{{ number_format($subtotal, 2) }} PHP</span>
                </div>
                <p class="tax-shipping-text">Taxes and shipping calculated at checkout</p>
                
                {{-- REDIRECT TO SHIPPING/CHECKOUT PAGE --}}
                <form action="{{ route('checkout') }}" method="GET" id="checkout-form">
                    <input type="hidden" name="selected_items" id="selected-items-input" value="">
                    <input type="hidden" name="instructions" id="instructions-input" value="">
                    <button type="submit" class="checkout-btn" id="checkout-btn">Check out</button>
                </form>
            </div>
        </div>

    {{-- SHOW THIS MESSAGE IF CART IS EMPTY --}}
    @else
        <div class="empty-cart-msg">
            <h3>Your cart is empty!</h3>
            <p>Looks like you haven't added any pastries or coffee yet.</p>
            <div style="margin-top: 25px;">
                <a href="{{ route('menu.pastries') }}" class="btn-go-menu">Go to Menu</a>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const subtotalDisplay = document.getElementById('subtotal-display');
        const checkoutBtn = document.getElementById('checkout-btn');
        const selectedItemsInput = document.getElementById('selected-items-input');
        const instructionsInput = document.getElementById('instructions-input');
        const instructionsTextarea = document.getElementById('special-instructions');
        const charCount = document.getElementById('char-count');

        // UPDATE CHARACTER COUNTER
        function updateCharCount() {
            charCount.textContent = instructionsTextarea.value.length;
        }
        instructionsTextarea.addEventListener('input', updateCharCount);
        updateCharCount();

        // CALCULATE SUBTOTAL AND UPDATE CHECKOUT STATE
        function updateCartState() {
            let subtotal = 0;
            let selectedIds = [];

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const price = parseFloat(checkbox.dataset.price);
                    const quantity = parseInt(checkbox.dataset.quantity);
                    subtotal += price * quantity;
                    selectedIds.push(checkbox.dataset.id);
                }
            });

            // UPDATE SUBTOTAL DISPLAY
            subtotalDisplay.textContent = subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' PHP';
            
            // UPDATE SELECTED ITEMS INPUT
            selectedItemsInput.value = selectedIds.join(',');

            // ENABLE/DISABLE CHECKOUT BUTTON
            if (selectedIds.length === 0) {
                checkoutBtn.disabled = true;
                checkoutBtn.style.opacity = '0.5';
                checkoutBtn.style.cursor = 'not-allowed';
            } else {
                checkoutBtn.disabled = false;
                checkoutBtn.style.opacity = '1';
                checkoutBtn.style.cursor = 'pointer';
            }
        }

        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            e.preventDefault();
            instructionsInput.value = instructionsTextarea.value;
            this.submit();
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateCartState);
        });

        updateCartState();

        // LISTEN FOR QUANTITY BUTTON CLICKS
        document.querySelectorAll('.qty-btn').forEach(button => {
            button.addEventListener('click', function() {
                const container = this.closest('.quantity-control');
                const id = container.getAttribute('data-id');
                const display = container.querySelector('.qty-display');
                let currentQty = parseInt(display.innerText);

                if (this.classList.contains('plus-btn')) {
                    currentQty++;
                } else if (this.classList.contains('minus-btn') && currentQty > 1) {
                    currentQty--;
                }

                updateQuantity(id, currentQty);
            });
        });

        // SEND PATCH REQUEST TO UPDATE CART SESSION
        function updateQuantity(id, qty) {
            fetch("{{ route('cart.update') }}", {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    id: id,
                    quantity: qty
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(() => {});
        }
    });
</script>
@endsection