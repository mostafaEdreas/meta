<script>
    var i = {{ old('product_id') && count(old('product_id')) > 0 ? count(old('product_id')) + 1 : 1 }};
    let products = @json($products);
    let subProduct = products;

    function appendProduct(btn) {
        var product = JSON.parse(btn.getAttribute('data-product'))
        btn.classList.remove("btn-primary");
        btn.classList.add("btn-success");
        var newRow = $(`
    <tr id="r${i}">
        <td class="counter" >${i}</td>
        <td>
            <select class="selectT pSelect form-select form-select-lg"  name="product_id[]" aria-label=".form-select-lg example">
                <option value="">اختر المنتج</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}"${('{{ $product->id }}' == product.id) ? 'selected' : ''}>{{ $product->name }}</option>
                @endforeach
            </select>  
        </td>
        <td><input type="text" name="purchase_price[]" value="0" class="purchase_price form-control form-control-lg"></td>
        <td><input type="text" name="sale_price[]" value="0" class="sale_price form-control form-control-lg"></td>
        <td><input type="text" name="quantity[]" value="1" class="quantity form-control form-control-lg"></td>
        <td><input type="text" name="discount_p[]" value="0" class="discount form-control form-control-lg"></td>
        <td>                            
            <select class=" type form-select form-select-lg" name="discount_type_p[]" aria-label=".form-select-lg example">
                <option value="amount">مبلغ</option>
                <option value="percent">نسبة مئوية</option>
            </select> 
        </td>
        <td><p class="total">0</p></td>
        <<td><a class="btn btn-danger" onclick="deleteRow(this)">حذف</a></td>
    </tr>`)
        $('#tbodyPurchase').append(newRow)
        setTimeout(() => {
            btn.classList.remove("btn-success");
            btn.classList.add("btn-primary");
        }, 1000);
        i++;
        newRow.find('.selectT').change();
        newRow.find('.selectT').select2({
            width: '300px',
            // Set the width explicitly
        });
    }
    getProduct()

    function getProduct() {
        productName = $('#productSearch').val();
        subProduct = products.filter(function(item) {
            return item.name.includes(productName)
        });
        $('#prodctsItems').html('')
        subProduct.forEach(function(product) {
            $('#prodctsItems').append(`
                <div class = "card col-xs-12 col-sm-6 col-md-4 col-lg-3 mt-3 p-0" style="width: 18rem;">
                    <div class="card-img-top"> 
                        <img src="{{ URL::to('images/product') }}/${product.img?product.img:'default.webp' }"  alt="...">
                    </div>
                    <div class="card-body">
                        <p class="card-text">${product.name}</p>
                        <h5 class="card-title">السعر: ${product.price }</h5>
                        <div class="d-flex">
                            <button class="form-control rounded-0 btn btn-primary" data-product='${JSON.stringify(product)}' onclick="appendProduct(this)">+</button>
                        </div>
                    </div>
                </div>
            `)
        });
    }
    ////////// Apped new row //////////
    function setNewRow() {
        var newRow = $(`
            <tr id="r${i}">
                <td class="counter">${i}</td>
                <td>
                    <select class="selectT pSelect form-select form-select-lg" onchange="" name="product_id[]" aria-label=".form-select-lg example">
                        <option value="">اختر المنتج</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="purchase_price[]" value="0" class="purchase_price form-control form-control-lg"></td>
                <td><input type="text" name="sale_price[]" value="0" class="sale_price form-control form-control-lg"></td>
                <td><input type="text" name="quantity[]" value="1" class="quantity form-control form-control-lg"></td>
                <td><input type="text" value="0" name="discount_p[]" class="discount form-control form-control-lg"></td>
                <td>
                    <select class=" type form-select form-select-lg" name="discount_type_p[]" aria-label=".form-select-lg example">
                        <option value="amount">مبلغ</option>
                        <option value="percent">نسبة مئوية</option>
                    </select>
                </td>
                <td><p class="total">0</p></td>
                <td><a class="btn btn-danger" onclick="deleteRow(this)">حذف</a></td>
            </tr>
        `)
        $('#tbodyPurchase').append(newRow)
        i++
        newRow.find('.selectT').select2({
            width: '300px',
        });
        newRow.find('.selectT').change();
    }
    ////////// Delete row //////////

    function checkproductIsChosed(parent) {
    let current
    let selectorsPro = parent.parent().find('.pSelect');
    let val = parent.find('.pSelect').val()

    let indexs = selectorsPro.filter(function(ele) {
        if (val) {
            return selectorsPro[ele].value == val;
        }
    });
   return indexs.length > 1 ?false:true;
}
    function setCounter() {
        counters = document.getElementsByClassName('counter')
        i = 1
        for (var j = 0; j < counters.length; j++) {
            counters[j].innerHTML = i;
            i++;
        }
    }
    function deleteRow(btn) {
        confirmation = confirm('هل انت متاكد من حذف هذا الصنف من الفاتورة')
        if (confirmation) {
            btn.parentElement.parentElement.remove()
            setCounter()
            setIvoiceDetails()
        }
    }

    ////////// When the user chosing a product /////////// 
    $('#tbodyPurchase').on('change', '.pSelect', function() {
        let currentVal = $(this).val()
        let parent = $(this).parent().parent();
        let check = checkproductIsChosed(parent)
        if (check) {
            parent.find('.purchase_price').val(0)
            parent.find('.sale_price').val(0)
            parent.find('.quantity').val(1);
            parent.find('.discount').val(0);
            parent.find('.total').text(0);
            parent.find('.type').val('amount').change();
        } else {
            $(this).val('').change();
            alert('تم اختيار هذا المنتج من قبل')
        }
        setCounter()
        setIvoiceDetails()
    });
    ////////// When enter a purchase praice //////////
    $('#tbodyPurchase').on('change', '.purchase_price', function() {
        onPurchasePriceOrQuantityOrDiscountOrTypeChandesForAnProduct(this)
    });
    ////////// When enter a quantity //////////
    $('#tbodyPurchase').on('change', '.quantity', function() {
        onPurchasePriceOrQuantityOrDiscountOrTypeChandesForAnProduct(this)
    });
    ////////// When enter a discount //////////
    $('#tbodyPurchase').on('change', '.discount', function() {
        onPurchasePriceOrQuantityOrDiscountOrTypeChandesForAnProduct(this)
    });
    ////////// When chosing a type //////////
    $('#tbodyPurchase').on('change', '.type', function() {
        onPurchasePriceOrQuantityOrDiscountOrTypeChandesForAnProduct(this)
    });
    ////////// when the user performs an event on the product row, this function is triggered to calculate the totla and append it for the selected product //////////
    function onPurchasePriceOrQuantityOrDiscountOrTypeChandesForAnProduct(element) {
        let parent = $(element).parent().parent();
        let type = parent.find('.type').val()
        let quantity = parseFloat(parent.find('.quantity').val());
        let purchasePrice = parseFloat(parent.find('.purchase_price').val() || 0);
        let discount = parseFloat(parent.find('.discount').val());
        let total = 0;
        if (type == 'percent') {
            total = (purchasePrice * quantity) - (purchasePrice * (discount/ 100) * quantity) || 0
        } else {
            total = (purchasePrice * quantity) - (discount)
        }
        parent.find('.total').text(total.toFixed(2) || 0);
        setIvoiceDetails()
    }
    $('#discount_invoice').on('change', function() {
        onTypeOrDiscountChanges()
    });

    $('#discount_type_invoice').on('change', function() {
        onTypeOrDiscountChanges();
    });

    function onTypeOrDiscountChanges() {
        let obj = getTotalDiscountForInvoice()
        $('#total_discount_on_invoice_amount').text(obj.amount || 0);
        $('#total_discount_on_invoice_percent').text(obj.percent || 0);
        $('#net').text(obj.net || 0);
    }

    function getTotalQuantityForInvice() {
        let sum = 0;
        $('#tbodyPurchase').find('.quantity').each(function() {
            let quantityValue = $(this).val() || 0;
            sum += parseFloat(quantityValue);
        });
        return sum.toFixed(2) || 0;
    }

    function getTotalPurchasePriceForInvice() {
        let sum = 0;
        $('#tbodyPurchase').find('.purchase_price').each(function() {
            let purchasePriceValue = parseFloat($(this).val()) || 0;
            let quantity = parseFloat($(this).parent().parent().find('.quantity').val())
            sum += (purchasePriceValue * quantity);
        });
        return sum.toFixed(2) || 0;
    }

    function getTotalDiscountForProduct() {
        let sum = 0;
        $('#tbodyPurchase').find('.discount').each(function() {
            let type = $(this).parent().parent().find('.type').val()
            let quantity = parseFloat($(this).parent().parent().find('.quantity').val())
            let dicountValue = parseFloat($(this).val()) || 0;
            let purchasePriceValue = parseFloat($(this).parent().parent().find('.purchase_price').val() || 0)
            if (type == 'percent') {
                sum += (purchasePriceValue * (dicountValue/ 100) * quantity)  || 0;
            } else {
                sum += dicountValue ;
            }

        });
        let totPurchase = getTotalPurchasePriceForInvice();
        percent = (sum / totPurchase) * 100 || 0;
        return {
            'percent': percent.toFixed(2) || 0,
            'amount': sum.toFixed(2) || 0
        };
    }

    function getTotalNetPurchasePriceForInvice() {
        let sum = 0;
        $('#tbodyPurchase').find('.total').each(function() {
            let total = parseFloat($(this).text()) || 0;
            sum += total;
        });
        return sum.toFixed(2) || 0;
    }

    function getTotalDiscountForInvoice() {
        let type = $('#discount_type_invoice').val()
        let totalAmount = parseFloat(getTotalNetPurchasePriceForInvice());
        let discount = parseFloat($('#discount_invoice').val() || 0);
        let total = 0;
        let percent = 0;
        let amount = 0;

        if (type == 'percent') {
            total = totalAmount - (totalAmount * (discount / 100) || 0);
            amount = (discount/ 100 ) * totalAmount || 0;
            percent = discount;
        } else {
            total = totalAmount - discount;
            percent = (discount / totalAmount) * 100 || 0;
            amount = discount;
        }
        return {
            'amount': amount.toFixed(2) || 0,
            'percent': percent.toFixed(2) || 0,
            'net': total.toFixed(2) || 0
        }
    }

    function setIvoiceDetails() {
       
        let quantity = getTotalQuantityForInvice();
        let netProduct = getTotalNetPurchasePriceForInvice();
        let total = getTotalPurchasePriceForInvice();
        let productDiscountValue = getTotalDiscountForProduct().amount;
        let productDiscountPercent = getTotalDiscountForProduct().percent;
        let dicountValue = getTotalDiscountForInvoice().amount;
        let dicountPercent = getTotalDiscountForInvoice().percent;
        let net = getTotalDiscountForInvoice().net;
        $('#total_invoice').text(total + 'ج')
        $('#total_quantity').text(quantity)
        $('#total_discount_on_product_amount').text(productDiscountValue + 'ج')
        $('#total_discount_on_product_percent').text(productDiscountPercent + '%')
        $('#net_on_products').text(netProduct + 'ج')
        $('#total_discount_on_invoice_amount').text(dicountValue + 'ج')
        $('#total_discount_on_invoice_percent').text(dicountPercent + '%')
        $('#net').text(net + '')
    }
    setIvoiceDetails()
</script>
