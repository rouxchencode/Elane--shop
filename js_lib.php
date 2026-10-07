<script>
document.addEventListener('DOMContentLoaded', function () {
    // 使用事件委派，避免重複綁定或動態元素綁不到的問題
    document.body.removeEventListener('click', handleAddCart);
    document.body.addEventListener('click', handleAddCart);

    function handleAddCart(e) {
        // 找到被點擊的加入購物車按鈕
        const button = e.target.closest('.btn-add-cart');
        if (!button) return;

        // 關鍵：阻止按鈕的預設提交行為（防止原生 form Submit 與 JS 同時觸發）
        e.preventDefault();
        e.stopPropagation();

        const productId = button.getAttribute('data-id');
        const qtyInput = document.getElementById('buy-qty');
        const qty = qtyInput ? qtyInput.value : 1;

        const formData = new FormData();
        formData.append('p_id', productId);
        formData.append('qty', qty);

        fetch('addcart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);

            // 成功後動態更新導覽列購物車數字
            if (data.status === 'success' && data.cart_count !== undefined) {
                const cartBadge = document.querySelector('.nav-link .badge') || document.querySelector('#cart-count');
                if (cartBadge) {
                    cartBadge.textContent = data.cart_count;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('加入購物車時發生錯誤！');
        });
    }
});


function btn_confirmLink(msg, url) {
    if (confirm(msg)) {
        window.location.href = url;
    }
}
</script>
