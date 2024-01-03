<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="body">
        <div class="product">
            <h1>Product List</h1>
            <div class="products-container">
                @foreach ($products as $product)
                <div class="product-item">
                    <img src="{{ $product->image }}">
                    <h2>{{ $product->name }}</h2>
                    <p>{{ $product->description }}</p>
                    <p>${{ number_format($product->price, 2) }} </p>
                    @if(in_array($product->id, $ids ))
                    <div id="image_check"><img style="width:20px;"
                            src="/image/z5035234482273_3fb9b3b7417c43e0d658890ecec22480.jpg"></div>
                    @else
                    <button class="add-to-cart" data-product-id="{{ $product->id }}">Add to
                        cart</button>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        <div class="cart">
            <h1>Your cart <span class="total">${{$totalRounded}}</span></h1>
            <div class="cart-container">
                <p class="empty" style=" margin-left:20px"></p>
                @foreach ($carts as $cart)
                <div class="cart-item" data-id="{{ $cart->id }}">
                    <div>
                        <img src="{{ $cart->image }}">
                    </div>
                    <div>
                        <h3>{{ $cart->name }}</h3>
                        <p>${{ $cart->price}}</p>
                        <div class="cart-quantity">
                            <div class="reduction"><img
                                    src="/image/z5035234896467_795357deecf814a1ac1c8674091417e1.jpg"></div>
                            <div class="value">{{ $cart->quantity }} </div>
                            <div class="increase"><img src="/image/z5035235623506_612b4274465ce733c1b36a6c25ae94a9.jpg">
                            </div>
                            <div class="delete"><img src="/image/z5035235951059_01cc8cfd818f78388cd866b0048a3555.jpg"
                                    class="delete-item"> </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
</body>

</html>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //add vào giỏ hàng
    $(document).ready(function () {
        $('.add-to-cart').click(function () {
            var button = $(this);
            var productId = $(this).data('product-id');
            $.ajax({
                url: '/add-to-cart',
                method: 'POST',
                data: {
                    id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    // Tạo nội dung HTML cho mục giỏ hàng
                    var cartItemHtml = '<div class="cart-item" data-id="' + response.data.id + '">' +
                        '<div><img src="' + response.data.image + '"></div>' +
                        '<div>' +
                        '<h3>' + response.data.name + '</h3>' +
                        '<p>$' + response.data.price + '</p>' +
                        '<div class="cart-quantity">' +
                        '<div class="reduction"><img src="/image/z5035234896467_795357deecf814a1ac1c8674091417e1.jpg"></div>' +
                        '<div class="value">' + response.data.quantity + '</div>' +
                        '<div class="increase"><img src="/image/z5035235623506_612b4274465ce733c1b36a6c25ae94a9.jpg"></div>' +
                        '<div class="delete"><img src="/image/z5035235951059_01cc8cfd818f78388cd866b0048a3555.jpg" class="delete-item">' + '  </div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                    // Thêm vào container giỏ hàng
                    $('.cart-container').append(cartItemHtml);

                    $('.total').html('$' + response.$totalRounded);
                    $('.empty').html(' ');
                    var imageDiv = '<img style="width:20px;" src="/image/z5035234482273_3fb9b3b7417c43e0d658890ecec22480.jpg">';
                    button.replaceWith(imageDiv);
                }
            });
        });
    });
    //tang giam so luong
    $(document).ready(function () {
        $(document).on('click', '.reduction, .increase', function () {
            var cartItem = $(this).closest('.cart-item');
            var productId = cartItem.data('id');
            var valueElement = cartItem.find('.value');
            var currentValue = parseInt(valueElement.text());
            var newValue = $(this).hasClass('increase') ? currentValue + 1 : currentValue - 1;

            if (newValue >= 1) {
                valueElement.text(newValue);

                // Gửi yêu cầu AJAX để cập nhật cơ sở dữ liệu
                $.ajax({
                    url: '/update-cart',
                    method: 'POST',
                    data: {
                        id: productId,
                        quantity: newValue,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('.total').html('$' + response);
                    },
                    error: function () {
                    }
                });
            }
            else if (newValue === 0) {
                $.ajax({
                    url: '/delete-cart',
                    method: 'POST',
                    data: {
                        id: productId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('.total').html('$' + response.$totalRounded);
                        if (response.$check_empty == 1) {
                            $('.empty').html('Your cart is empty');
                        }
                        cartItem.remove();
                    },
                    error: function () {
                        console.log("Lỗi xảy ra khi xóa sản phẩm!");
                    }
                });
            }
        });
    });

    //xoa san pham
    $(document).ready(function () {
        $('.cart-container').on('click', '.delete-item', function () {
            var cartItem = $(this).closest('.cart-item');
            var productId = cartItem.data('id');

            $.ajax({
                url: '/delete-cart',
                method: 'POST',
                data: {
                    id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('.total').html('$' + response.$totalRounded);
                    if (response.$check_empty == 1) {
                        $('.empty').html('Your cart is empty');
                    }
                    cartItem.remove();
                },
                error: function () {
                    alert("Lỗi xảy ra khi xóa sản phẩm!");
                }
            });
        });
    });

</script>