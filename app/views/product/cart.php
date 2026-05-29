<?php $pageTitle = 'Giỏ hàng'; include 'app/views/shares/header.php'; ?>

<div class="container">
    <h1>Giỏ hàng của bạn</h1>

    <?php if (empty($cart)): ?>
        <div class="empty-state">
            <i class="ph ph-shopping-cart" style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p>Giỏ hàng đang trống.</p>
            <a href="/TH-MNM/" class="btn btn-primary btn-water">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <?php
            $grandTotal = 0;
            foreach ($cart as $item) {
                $grandTotal += (float)$item['price'] * (int)$item['quantity'];
            }
        ?>

        <form id="cartForm" method="POST" action="/TH-MNM/Product/updateCart">
            <div class="glass-panel" style="padding: 1rem;">
                <div style="display:flex; align-items:center; gap:.5rem; margin-bottom:1rem;">
                    <input type="checkbox" id="selectAllItems" checked style="width:18px; height:18px; margin:0;">
                    <label for="selectAllItems" style="margin:0; font-weight:600;">Chọn tất cả sản phẩm để thanh toán</label>
                </div>

                <div style="display: grid; gap: 1rem;">
                    <?php foreach ($cart as $item): ?>
                        <?php $lineTotal = (float)$item['price'] * (int)$item['quantity']; ?>
                        <div class="product-card" style="display: grid; grid-template-columns: 40px 100px 1fr auto; gap: 1rem; align-items: center;">
                            <div style="display:flex; justify-content:center;">
                                <input
                                    type="checkbox"
                                    class="checkout-item"
                                    name="selected_items[]"
                                    value="<?php echo (int)$item['id']; ?>"
                                    checked
                                    style="width:18px; height:18px;"
                                >
                            </div>

                            <div style="width: 100px; height: 100px; border-radius: 12px; overflow: hidden; background: rgba(255,255,255,0.06); display: flex; align-items: center; justify-content: center;">
                                <?php if (!empty($item['image'])): ?>
                                    <img src="/TH-MNM/public/images/<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="ph ph-image" style="font-size: 2rem; opacity: 0.4;"></i>
                                <?php endif; ?>
                            </div>

                            <div>
                                <h3 style="margin-bottom: .4rem;"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                <div style="color: var(--text-muted); margin-bottom: .4rem;">
                                    Đơn giá: <?php echo number_format((float)$item['price'], 0, ',', '.'); ?> đ
                                </div>
                                <div>
                                    <label style="font-size: .9rem; display:block; margin-bottom:.35rem;">Số lượng</label>
                                    <div style="display:flex; align-items:center; gap:.4rem; max-width: 180px;">
                                        <button type="button" class="btn btn-outline btn-water qty-btn qty-minus" data-product-id="<?php echo (int)$item['id']; ?>" style="padding:.45rem .8rem;">-</button>
                                        <input
                                            type="number"
                                            min="0"
                                            step="1"
                                            name="quantity[<?php echo (int)$item['id']; ?>]"
                                            value="<?php echo (int)$item['quantity']; ?>"
                                            data-price="<?php echo (float)$item['price']; ?>"
                                            data-product-id="<?php echo (int)$item['id']; ?>"
                                            class="qty-input"
                                            style="text-align:center; margin-bottom:0;"
                                        >
                                        <button type="button" class="btn btn-outline btn-water qty-btn qty-plus" data-product-id="<?php echo (int)$item['id']; ?>" style="padding:.45rem .8rem;">+</button>
                                    </div>
                                </div>
                            </div>

                            <div style="text-align: right;">
                                <div class="line-total" data-product-id="<?php echo (int)$item['id']; ?>" style="font-weight: 700; margin-bottom: .75rem;">
                                    <?php echo number_format($lineTotal, 0, ',', '.'); ?> đ
                                </div>
                                <a href="/TH-MNM/Product/removeFromCart/<?php echo (int)$item['id']; ?>" class="btn btn-danger btn-water" onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?');">
                                    <i class="ph ph-trash"></i> Xóa
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="glass-panel" style="margin-top: 1rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <div>
                    <strong>Tổng cộng (đã chọn): <span id="grandTotal"><?php echo number_format($grandTotal, 0, ',', '.'); ?> đ</span></strong>
                </div>
                <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
                    <a href="/TH-MNM/Product/clearCart" class="btn btn-danger btn-water" onclick="return confirm('Bạn chắc chắn muốn xóa toàn bộ giỏ hàng?');">
                        <i class="ph ph-x-circle"></i>&nbsp; Xóa giỏ hàng
                    </a>
                    <button type="button" class="btn btn-primary btn-water" id="checkoutBtn">
                        <i class="ph ph-credit-card"></i>&nbsp; Tiến hành đặt hàng
                    </button>
                </div>
            </div>
        </form>

        <script>
            (function () {
                var form = document.getElementById('cartForm');
                if (!form) return;

                var qtyInputs = Array.prototype.slice.call(document.querySelectorAll('.qty-input'));
                var plusBtns = Array.prototype.slice.call(document.querySelectorAll('.qty-plus'));
                var minusBtns = Array.prototype.slice.call(document.querySelectorAll('.qty-minus'));
                var checkoutBtn = document.getElementById('checkoutBtn');
                var grandTotalEl = document.getElementById('grandTotal');
                var selectAllItems = document.getElementById('selectAllItems');
                var checkoutItemCheckboxes = Array.prototype.slice.call(document.querySelectorAll('.checkout-item'));

                function formatCurrency(num) {
                    var n = Number(num || 0);
                    return n.toLocaleString('vi-VN') + ' đ';
                }

                function findInputByProductId(productId) {
                    return document.querySelector('.qty-input[data-product-id="' + productId + '"]');
                }

                function updateLineTotal(productId) {
                    var input = findInputByProductId(productId);
                    if (!input) return;

                    var qty = parseInt(input.value, 10);
                    if (isNaN(qty) || qty < 0) qty = 0;
                    input.value = qty;

                    var price = parseFloat(input.getAttribute('data-price') || '0');
                    var lineTotal = price * qty;
                    var lineTotalEl = document.querySelector('.line-total[data-product-id="' + productId + '"]');
                    if (lineTotalEl) {
                        lineTotalEl.textContent = formatCurrency(lineTotal);
                    }
                }

                function updateGrandTotal() {
                    var total = 0;
                    qtyInputs.forEach(function (input) {
                        var productId = input.getAttribute('data-product-id');
                        var itemCheckbox = document.querySelector('.checkout-item[value="' + productId + '"]');
                        if (!itemCheckbox || !itemCheckbox.checked) return;

                        var qty = parseInt(input.value, 10);
                        if (isNaN(qty) || qty < 0) qty = 0;
                        var price = parseFloat(input.getAttribute('data-price') || '0');
                        total += price * qty;
                    });

                    if (grandTotalEl) {
                        grandTotalEl.textContent = formatCurrency(total);
                    }

                    if (checkoutBtn) {
                        checkoutBtn.style.pointerEvents = total <= 0 ? 'none' : 'auto';
                        checkoutBtn.style.opacity = total <= 0 ? '0.6' : '1';
                    }
                }

                function syncServerCart() {
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                }

                function updateSelectAllState() {
                    if (!selectAllItems) return;
                    var checkedCount = checkoutItemCheckboxes.filter(function (cb) { return cb.checked; }).length;
                    selectAllItems.checked = checkoutItemCheckboxes.length > 0 && checkedCount === checkoutItemCheckboxes.length;
                }

                function submitCheckoutSelected() {
                    var selected = checkoutItemCheckboxes.filter(function (cb) { return cb.checked; });
                    if (selected.length === 0) {
                        alert('Vui lòng chọn ít nhất 1 sản phẩm để thanh toán.');
                        return;
                    }

                    var checkoutForm = document.createElement('form');
                    checkoutForm.method = 'POST';
                    checkoutForm.action = '/TH-MNM/Product/checkout';

                    selected.forEach(function (cb) {
                        var hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = 'selected_items[]';
                        hidden.value = cb.value;
                        checkoutForm.appendChild(hidden);
                    });

                    document.body.appendChild(checkoutForm);
                    checkoutForm.submit();
                }

                function bindSelectionEvents() {
                    if (selectAllItems) {
                        selectAllItems.addEventListener('change', function () {
                            checkoutItemCheckboxes.forEach(function (cb) {
                                cb.checked = selectAllItems.checked;
                            });
                            updateGrandTotal();
                        });
                    }

                    checkoutItemCheckboxes.forEach(function (cb) {
                        cb.addEventListener('change', function () {
                            updateSelectAllState();
                            updateGrandTotal();
                        });
                    });

                    if (checkoutBtn) {
                        checkoutBtn.addEventListener('click', function () {
                            submitCheckoutSelected();
                        });
                    }
                }

                qtyInputs.forEach(function (input) {
                    input.addEventListener('change', function () {
                        var productId = input.getAttribute('data-product-id');
                        updateLineTotal(productId);
                        updateGrandTotal();
                        syncServerCart();
                    });
                });

                plusBtns.forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var productId = btn.getAttribute('data-product-id');
                        var input = findInputByProductId(productId);
                        if (!input) return;
                        var qty = parseInt(input.value, 10);
                        if (isNaN(qty) || qty < 0) qty = 0;
                        input.value = qty + 1;
                        updateLineTotal(productId);
                        updateGrandTotal();
                        syncServerCart();
                    });
                });

                minusBtns.forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var productId = btn.getAttribute('data-product-id');
                        var input = findInputByProductId(productId);
                        if (!input) return;
                        var qty = parseInt(input.value, 10);
                        if (isNaN(qty) || qty < 0) qty = 0;
                        input.value = Math.max(0, qty - 1);
                        updateLineTotal(productId);
                        updateGrandTotal();
                        syncServerCart();
                    });
                });

                bindSelectionEvents();
                updateSelectAllState();
                updateGrandTotal();
            })();
        </script>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>
