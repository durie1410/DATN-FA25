# 🛒 HƯỚNG DẪN HỆ THỐNG GIỎ HÀNG - WAKA LIBRARY

## 📋 Tổng quan

Hệ thống giỏ hàng đã được tích hợp đầy đủ vào website Waka Library, cho phép người dùng:
- ✅ Thêm sách vào giỏ hàng
- ✅ Xem giỏ hàng với giao diện đẹp
- ✅ Cập nhật số lượng sách
- ✅ Xóa sách khỏi giỏ
- ✅ Thanh toán (chuẩn bị)

---

## 🎨 Các tính năng đã triển khai

### 1. **Icon Giỏ Hàng trong Header**

**Vị trí:** `resources/views/home.blade.php` (dòng 36-44)

```html
<!-- Cart Button -->
<a href="{{ route('cart.index') }}" class="cart-btn" aria-label="Giỏ hàng">
  <svg>...</svg>
  <span class="cart-badge" id="cartCount">0</span>
</a>
```

**Tính năng:**
- Icon giỏ hàng luôn hiển thị ở header
- Badge màu đỏ hiển thị số lượng sản phẩm
- Cập nhật real-time khi thêm/xóa sản phẩm
- Animation pulse thu hút sự chú ý

---

### 2. **CSS Styling cho Giỏ Hàng**

**File:** `public/css/cart.css`

**Các thành phần được styling:**
- ✨ Cart page với gradient background
- 💳 Cart items table responsive
- 🎯 Quantity controls (tăng/giảm)
- 📊 Cart summary với tổng tiền
- 🎨 Modern buttons và hover effects
- 📱 Responsive design cho mobile

**Màu sắc chủ đạo:**
- Primary: `#00ff99` (xanh lá neon)
- Background: `#0a0a0a` đến `#1a1a1a` (gradient tối)
- Cards: `#1c1c1c` đến `#2a2a2a` (gradient)

---

### 3. **Nút "Thêm vào Giỏ" trên Trang Sách**

**File:** `resources/views/purchasable-books/index.blade.php`

**Hai nút chính:**

```html
<!-- Nút thêm vào giỏ -->
<button class="btn btn-success btn-sm add-to-cart-btn" 
        data-book-id="{{ $book->id }}">
    <i class="fas fa-cart-plus"></i> Thêm vào giỏ
</button>

<!-- Nút xem chi tiết -->
<button class="btn btn-outline-primary btn-sm buy-details-btn">
    <i class="fas fa-info-circle"></i> Xem chi tiết
</button>
```

**JavaScript Features:**
- Loading state khi đang thêm
- Success feedback animation
- Toast notification
- Auto-update cart badge

---

### 4. **Trang Giỏ Hàng**

**Route:** `/cart`  
**Controller:** `App\Http\Controllers\CartController`  
**View:** `resources/views/cart/index.blade.php`

**Chức năng:**
1. **Hiển thị danh sách sản phẩm**
   - Hình ảnh sách
   - Tên, tác giả
   - Giá đơn vị
   - Số lượng (có thể điều chỉnh)
   - Tổng tiền từng item

2. **Controls**
   - Tăng/giảm số lượng
   - Xóa từng item
   - Xóa toàn bộ giỏ hàng

3. **Cart Summary**
   - Tổng số sản phẩm
   - Tổng tiền
   - Nút thanh toán
   - Nút tiếp tục mua sắm

4. **Support Info**
   - Miễn phí vận chuyển
   - Giao hàng 24h
   - Hỗ trợ 24/7

---

## 🔧 Backend Structure

### Database Tables

#### 1. `carts` Table
```sql
- id (primary key)
- user_id (nullable, foreign key)
- session_id (nullable, cho guest)
- total_amount (decimal)
- total_items (integer)
- status (active/abandoned/converted)
- timestamps
```

#### 2. `cart_items` Table
```sql
- id (primary key)
- cart_id (foreign key)
- purchasable_book_id (foreign key)
- quantity (integer)
- price (decimal, giá tại thời điểm thêm)
- total_price (decimal)
- timestamps
- UNIQUE(cart_id, purchasable_book_id)
```

### Models

#### Cart Model (`app/Models/Cart.php`)
```php
// Relationships
- user() - BelongsTo
- items() - HasMany

// Methods
- recalculateTotals() - Tính lại tổng
- isEmpty() - Kiểm tra giỏ trống
- getOrCreateForUser($userId) - Lấy/tạo cart cho user
- getOrCreateForSession($sessionId) - Lấy/tạo cart cho session

// Scopes
- scopeActive($query)
- scopeForUser($query, $userId)
- scopeForSession($query, $sessionId)
```

#### CartItem Model (`app/Models/CartItem.php`)
```php
// Relationships
- cart() - BelongsTo
- purchasableBook() - BelongsTo

// Methods
- updateQuantity($quantity) - Cập nhật và tính lại
- addOrUpdate($cartId, $bookId, $quantity) - Thêm hoặc cập nhật
```

### Controller Methods

#### CartController (`app/Http/Controllers/CartController.php`)
```php
✅ index() - Hiển thị giỏ hàng
✅ add(Request $request) - Thêm sản phẩm
✅ update(Request $request, $id) - Cập nhật số lượng
✅ remove($id) - Xóa item
✅ clear() - Xóa toàn bộ
✅ count() - Lấy số lượng (AJAX)
✅ transferToUser($userId) - Chuyển từ session sang user
```

---

## 🛣️ Routes

```php
// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
```

---

## 📱 JavaScript Functions

### 1. Update Cart Count (home.blade.php)
```javascript
function updateCartCount() {
  fetch('/cart/count')
    .then(response => response.json())
    .then(data => {
      // Cập nhật badge
      document.getElementById('cartCount').textContent = data.count;
    });
}
```

### 2. Add to Cart (purchasable-books/index.blade.php)
```javascript
// Thêm vào giỏ hàng
fetch('/cart/add', {
  method: 'POST',
  body: JSON.stringify({
    book_id: bookId,
    quantity: 1
  })
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    showToast('success', 'Đã thêm vào giỏ hàng!');
    updateCartCount();
  }
});
```

### 3. Update Quantity (cart/index.blade.php)
```javascript
function updateQuantity(itemId, quantity) {
  fetch(`/cart/update/${itemId}`, {
    method: 'PUT',
    body: JSON.stringify({ quantity: quantity })
  })
  .then(response => response.json())
  .then(data => {
    // Cập nhật UI
    location.reload();
  });
}
```

### 4. Remove Item
```javascript
function removeItem(itemId) {
  fetch(`/cart/remove/${itemId}`, {
    method: 'DELETE'
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      location.reload();
    }
  });
}
```

---

## 🎨 UI/UX Features

### 1. **Toast Notifications**
- Success toast (màu xanh) khi thêm thành công
- Error toast (màu đỏ) khi có lỗi
- Auto dismiss sau 3 giây

### 2. **Loading States**
- Spinner icon khi đang xử lý
- Disable button để tránh double-click
- Success checkmark khi hoàn tất

### 3. **Animations**
```css
/* Badge pulse animation */
@keyframes badgePulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.1); }
}

/* Hover effects */
.cart-btn:hover {
  transform: scale(1.1);
}
```

### 4. **Responsive Design**
- Desktop: Table layout
- Tablet: Optimized grid
- Mobile: Card layout với data-label

---

## 🔐 Security Features

1. **CSRF Protection**
   - Mọi request đều có CSRF token
   - Validation ở backend

2. **Authorization**
   - User chỉ truy cập cart của mình
   - Session-based cho guest users

3. **Validation**
   - Kiểm tra số lượng tồn kho
   - Validate quantity (min: 1, max: 10)
   - Kiểm tra sách tồn tại

---

## 🚀 Cách sử dụng

### Cho người dùng:

1. **Thêm sách vào giỏ:**
   - Vào trang "Sách có thể mua"
   - Click nút "Thêm vào giỏ" màu xanh
   - Thông báo toast xuất hiện
   - Badge giỏ hàng cập nhật

2. **Xem giỏ hàng:**
   - Click icon giỏ hàng ở header
   - Hoặc truy cập `/cart`

3. **Cập nhật số lượng:**
   - Dùng nút +/- hoặc nhập trực tiếp
   - Giá tự động cập nhật

4. **Xóa sản phẩm:**
   - Click icon thùng rác
   - Confirm trong modal

5. **Thanh toán:**
   - Click "Thanh toán" trong cart summary
   - (Chức năng đang phát triển)

---

## 📊 Cart Statistics

### Performance
- ⚡ AJAX requests - không reload page
- 🔄 Auto-update cart count mỗi 30 giây
- 💾 Session/Database persistence

### User Experience
- ✨ Smooth animations
- 📱 Mobile-friendly
- 🎯 Clear visual feedback
- 🔔 Toast notifications

---

## 🔮 Tính năng sắp tới

- [ ] Checkout flow hoàn chỉnh
- [ ] Payment gateway integration
- [ ] Coupon/Discount codes
- [ ] Save for later
- [ ] Cart abandonment email
- [ ] Wishlist integration
- [ ] Quick view từ cart
- [ ] Related products suggestions

---

## 🐛 Troubleshooting

### Giỏ hàng không cập nhật?
```javascript
// Kiểm tra CSRF token
console.log(document.querySelector('meta[name="csrf-token"]'));

// Kiểm tra route
console.log('{{ route("cart.add") }}');
```

### Badge không hiển thị?
```javascript
// Kiểm tra element tồn tại
const badge = document.getElementById('cartCount');
console.log(badge);

// Force update
updateCartCount();
```

### Session cart không chuyển sang user cart?
```php
// Gọi trong AuthController sau khi login
app(CartController::class)->transferToUser(auth()->id());
```

---

## 📝 Notes cho Developer

1. **Guest Cart:**
   - Sử dụng Session ID
   - Tự động chuyển sang User ID khi login
   - Session timeout sau 2 giờ

2. **Stock Management:**
   - Kiểm tra số lượng tồn kho trước khi thêm
   - Prevent overselling
   - Update stock on checkout

3. **Price Locking:**
   - Giá được lưu vào cart_items khi thêm
   - Không thay đổi nếu giá sách thay đổi
   - Đảm bảo consistency

---

## 🎉 Hoàn thành!

Hệ thống giỏ hàng đã sẵn sàng sử dụng với:
- ✅ UI/UX hiện đại
- ✅ Backend robust
- ✅ Mobile responsive
- ✅ Security measures
- ✅ User-friendly interactions

**Developed with ❤️ for Waka Library**

---

## 📞 Support

Nếu có vấn đề, liên hệ:
- 📧 Email: support@waka.vn
- 📱 Hotline: 0877736289







